<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Coupon model.
 * `type` is either 'percentage' (value = % off, 0-100) or
 * 'fixed' (value = flat dollar amount off).
 */
class Coupon
{
    /** @return array All coupons, newest first (admin listing). */
    public static function all()
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM coupons ORDER BY created_at DESC, id DESC');
        return $stmt->fetchAll();
    }

    /** @return array|false */
    public static function find($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return array|false */
    public static function findByCode($code)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM coupons WHERE code = :code LIMIT 1');
        $stmt->execute([':code' => $code]);
        return $stmt->fetch();
    }

    /**
     * Validates a coupon code for use right now: must exist, be active,
     * not be past its expiry date, and not have exhausted its usage cap.
     *
     * @return array{valid:bool, message:string, coupon:array|null}
     */
    public static function validate($code)
    {
        $code = trim((string)$code);
        if ($code === '') {
            return ['valid' => false, 'message' => 'Enter a coupon code.', 'coupon' => null];
        }

        $coupon = self::findByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'That coupon code does not exist.', 'coupon' => null];
        }
        if ((int)$coupon['active'] !== 1) {
            return ['valid' => false, 'message' => 'That coupon is no longer active.', 'coupon' => null];
        }
        if (!empty($coupon['expires_at'])) {
            $expiryDate = date('Y-m-d', strtotime($coupon['expires_at']));
            if (strtotime($expiryDate . ' 23:59:59') < time()) {
                return ['valid' => false, 'message' => 'That coupon has expired.', 'coupon' => null];
            }
        }
        if ($coupon['max_uses'] !== null && (int)$coupon['times_used'] >= (int)$coupon['max_uses']) {
            return ['valid' => false, 'message' => 'That coupon has reached its usage limit.', 'coupon' => null];
        }

        return ['valid' => true, 'message' => 'Coupon applied.', 'coupon' => $coupon];
    }

    /**
     * Calculates the discount amount a coupon yields against a subtotal.
     * The discount is always capped at the subtotal itself (a total can
     * never go negative).
     */
    public static function calculateDiscount(array $coupon, $subtotal)
    {
        $subtotal = (float)$subtotal;
        if ($subtotal <= 0) {
            return 0.0;
        }

        if ($coupon['type'] === 'percentage') {
            $discount = $subtotal * ((float)$coupon['value'] / 100);
        } else {
            $discount = (float)$coupon['value'];
        }

        return round(min($discount, $subtotal), 2);
    }

    /** Increments the usage counter — call once, at the moment an order is finalized. */
    public static function recordUse($code)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE coupons SET times_used = times_used + 1 WHERE code = :code');
        $stmt->execute([':code' => $code]);
    }

    /** @return int Newly created coupon id. */
    public static function create($code, $type, $value, $maxUses = null, $expiresAt = null)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO coupons (code, type, value, active, max_uses, expires_at)
             VALUES (:code, :type, :value, 1, :max_uses, :expires_at)'
        );
        $stmt->execute([
            ':code'       => strtoupper(trim($code)),
            ':type'       => $type === 'fixed' ? 'fixed' : 'percentage',
            ':value'      => (float)$value,
            ':max_uses'   => $maxUses !== '' && $maxUses !== null ? (int)$maxUses : null,
            ':expires_at' => $expiresAt !== '' && $expiresAt !== null ? $expiresAt : null,
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function toggleActive($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE coupons SET active = NOT active WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
    }

    public static function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM coupons WHERE id = :id');
        $stmt->execute([':id' => (int)$id]);
    }

    /** @return bool */
    public static function codeExists($code)
    {
        return self::findByCode($code) !== false;
    }
}
