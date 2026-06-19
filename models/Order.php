<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Order model.
 * Owns the checkout transaction (orders + order_items + stock
 * decrement as a single atomic unit) plus the read queries used by
 * order history, receipts, and the admin analytics dashboard.
 */
class Order
{
    /**
     * Creates an order from the current cart contents inside a single
     * database transaction. Stock is decremented per line item with an
     * atomic guard (`stock >= quantity`) so two simultaneous checkouts
     * can never oversell the same item.
     *
     * @param int        $userId
     * @param array      $items       Output of cart_items(): [['product'=>row,'qty'=>int,'line_total'=>float], ...]
     * @param float      $subtotal
     * @param float      $discount
     * @param float      $total
     * @param string|null $couponCode
     * @return int Newly created order id.
     * @throws Exception If any line item no longer has enough stock.
     */
    public static function create($userId, array $items, $subtotal, $discount, $total, $couponCode = null)
    {
        $pdo = Database::getConnection();
        $pdo->beginTransaction();

        try {
            $orderStmt = $pdo->prepare(
                'INSERT INTO orders (user_id, subtotal, discount_applied, coupon_code, total_price, status)
                 VALUES (:user_id, :subtotal, :discount, :coupon_code, :total, :status)'
            );
            $orderStmt->execute([
                ':user_id'     => (int)$userId,
                ':subtotal'    => $subtotal,
                ':discount'    => $discount,
                ':coupon_code' => $couponCode,
                ':total'       => $total,
                ':status'      => 'completed',
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $itemStmt = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, quantity, price)
                 VALUES (:order_id, :product_id, :product_name, :quantity, :price)'
            );

            $stockStmt = $pdo->prepare(
                'UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty2'
            );

            foreach ($items as $item) {
                $product = $item['product'];
                $qty     = (int)$item['qty'];

                $stockStmt->execute([
                    ':qty'  => $qty,
                    ':qty2' => $qty,
                    ':id'   => $product['id'],
                ]);

                if ($stockStmt->rowCount() === 0) {
                    throw new Exception('"' . $product['name'] . '" no longer has enough stock available.');
                }

                $itemStmt->execute([
                    ':order_id'     => $orderId,
                    ':product_id'   => $product['id'],
                    ':product_name' => $product['name'],
                    ':quantity'     => $qty,
                    ':price'        => $product['price'],
                ]);
            }

            $pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    /** @return array|false */
    public static function find($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return array Line items belonging to an order. */
    public static function items($orderId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = :id ORDER BY id ASC');
        $stmt->execute([':id' => (int)$orderId]);
        return $stmt->fetchAll();
    }

    /** @return array All orders placed by a given user, newest first. */
    public static function allForUser($userId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC, id DESC');
        $stmt->execute([':uid' => (int)$userId]);
        return $stmt->fetchAll();
    }

    /** @return array Every order in the system with the buyer's username attached (admin view). */
    public static function allWithBuyer()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query(
            'SELECT o.*, u.username, u.email
             FROM orders o
             JOIN users u ON u.id = o.user_id
             ORDER BY o.created_at DESC, o.id DESC'
        );
        return $stmt->fetchAll();
    }

    /** @return float Sum of total_price across every completed order. */
    public static function totalRevenue()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT COALESCE(SUM(total_price), 0) FROM orders WHERE status = 'completed'");
        return (float)$stmt->fetchColumn();
    }

    /** @return int Number of orders placed. */
    public static function totalCount()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT COUNT(*) FROM orders');
        return (int)$stmt->fetchColumn();
    }

    /** @return float Average order value. */
    public static function averageOrderValue()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT COALESCE(AVG(total_price), 0) FROM orders WHERE status = 'completed'");
        return (float)$stmt->fetchColumn();
    }

    /**
     * Top-selling products by total quantity sold, derived from
     * order_items so historical totals survive even if a product is
     * later deleted or re-priced.
     *
     * @return array [['product_name'=>, 'total_qty'=>, 'revenue'=>], ...]
     */
    public static function topSellingProducts($limit = 5)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'SELECT product_name,
                    SUM(quantity)        AS total_qty,
                    SUM(quantity * price) AS revenue
             FROM order_items
             GROUP BY product_name
             ORDER BY total_qty DESC
             LIMIT :limit'
        );
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
