<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Product model.
 * Thin PDO wrapper around the `products` table — no ORM, no magic,
 * just prepared statements so it's easy to read and extend.
 */
class Product
{
    /** @return array All products, newest first. */
    public static function all()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM products ORDER BY created_at DESC, id DESC');
        return $stmt->fetchAll();
    }

    /** @return array Products belonging to a given category. */
    public static function byCategory($category)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM products WHERE category = :category ORDER BY created_at DESC, id DESC'
        );
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll();
    }

    /** @return array|false Single product by id. */
    public static function find($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return array|false Single product by slug. */
    public static function findBySlug($slug)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE slug = :slug LIMIT 1');
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    /** @return array Featured products for the homepage edit. */
    public static function featured($limit = 4)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM products WHERE is_featured = 1 ORDER BY created_at DESC, id DESC LIMIT :limit'
        );
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        // Fallback: if nothing is marked featured yet, just show the latest.
        if (empty($rows)) {
            $stmt = $pdo->prepare('SELECT * FROM products ORDER BY created_at DESC, id DESC LIMIT :limit');
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
        }

        return $rows;
    }

    /** @return array Distinct category names present in the catalog, A–Z. */
    public static function categories()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category ASC');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** @return array{min:float,max:float} Price bounds across the whole catalog, for the filter UI. */
    public static function priceBounds()
    {
        $pdo = Database::getConnection();
        $row = $pdo->query('SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM products')->fetch();
        return [
            'min' => $row && $row['min_price'] !== null ? (float)$row['min_price'] : 0.0,
            'max' => $row && $row['max_price'] !== null ? (float)$row['max_price'] : 0.0,
        ];
    }

    /**
     * Combined search/category/price filter — powers search.php (the
     * live AJAX search bar + category & price-range sidebar on the shop page).
     * Every clause is optional and built with bound parameters only,
     * so it's safe against SQL injection regardless of which filters
     * are active at once.
     *
     * @param string|null $term      Free-text search against name + description.
     * @param string|null $category  Exact category match, or 'All'/null to skip.
     * @param float|null  $minPrice
     * @param float|null  $maxPrice
     * @return array
     */
    public static function filter($term = null, $category = null, $minPrice = null, $maxPrice = null)
    {
        $pdo    = Database::getConnection();
        $where  = [];
        $params = [];

        $term = trim((string)$term);
        if ($term !== '') {
            $where[] = '(name LIKE :term1 OR description LIKE :term2)';
            $params[':term1'] = '%' . $term . '%';
            $params[':term2'] = '%' . $term . '%';
        }

        if ($category !== null && $category !== '' && strcasecmp($category, 'All') !== 0) {
            $where[] = 'category = :category';
            $params[':category'] = $category;
        }

        if ($minPrice !== null && $minPrice !== '') {
            $where[] = 'price >= :min_price';
            $params[':min_price'] = (float)$minPrice;
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $where[] = 'price <= :max_price';
            $params[':max_price'] = (float)$maxPrice;
        }

        $sql = 'SELECT * FROM products';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC, id DESC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Generates a unique URL-safe slug from a product name (admin create/update). */
    public static function slugify($name, $excludeId = null)
    {
        $base = strtolower(trim($name));
        $base = preg_replace('/[^a-z0-9]+/', '-', $base);
        $base = trim($base, '-');
        $base = $base !== '' ? $base : 'product';

        $pdo  = Database::getConnection();
        $slug = $base;
        $i    = 1;

        while (true) {
            $sql    = 'SELECT id FROM products WHERE slug = :slug' . ($excludeId ? ' AND id != :id' : '');
            $stmt   = $pdo->prepare($sql);
            $params = [':slug' => $slug];
            if ($excludeId) {
                $params[':id'] = (int)$excludeId;
            }
            $stmt->execute($params);

            if (!$stmt->fetch()) {
                return $slug;
            }
            $i++;
            $slug = $base . '-' . $i;
        }
    }

    /**
     * Admin: creates a new product.
     * @return int Newly created product id.
     */
    public static function create(array $data)
    {
        $pdo  = Database::getConnection();
        $slug = self::slugify($data['name']);

        $stmt = $pdo->prepare(
            'INSERT INTO products (name, slug, price, stock, category, image_url, description, is_featured)
             VALUES (:name, :slug, :price, :stock, :category, :image_url, :description, :is_featured)'
        );
        $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $slug,
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
            ':category'    => $data['category'],
            ':image_url'   => $data['image_url'],
            ':description' => $data['description'],
            ':is_featured' => !empty($data['is_featured']) ? 1 : 0,
        ]);

        return (int)$pdo->lastInsertId();
    }

    /** Admin: updates an existing product. Re-slugs only if the name changed. */
    public static function update($id, array $data)
    {
        $pdo     = Database::getConnection();
        $current = self::find($id);
        if (!$current) {
            return false;
        }

        $slug = ($current['name'] !== $data['name'])
            ? self::slugify($data['name'], $id)
            : $current['slug'];

        $stmt = $pdo->prepare(
            'UPDATE products SET
                name = :name, slug = :slug, price = :price, stock = :stock,
                category = :category, image_url = :image_url,
                description = :description, is_featured = :is_featured
             WHERE id = :id'
        );

        return $stmt->execute([
            ':name'        => $data['name'],
            ':slug'        => $slug,
            ':price'       => (float)$data['price'],
            ':stock'       => (int)$data['stock'],
            ':category'    => $data['category'],
            ':image_url'   => $data['image_url'],
            ':description' => $data['description'],
            ':is_featured' => !empty($data['is_featured']) ? 1 : 0,
            ':id'          => (int)$id,
        ]);
    }

    /** Admin: permanently deletes a product (past order_items keep a name/price snapshot, unaffected). */
    public static function delete($id)
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute([':id' => (int)$id]);
    }
}
