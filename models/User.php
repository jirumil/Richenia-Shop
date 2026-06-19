<?php
require_once __DIR__ . '/../config/database.php';

/**
 * User model.
 * Thin PDO wrapper around the `users` table. Passwords are hashed
 * with PHP's password_hash() (bcrypt) and verified with
 * password_verify() — the hash itself never leaves this file.
 */
class User
{
    /** @return array|false */
    public static function findById($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch();
    }

    /** @return array|false */
    public static function findByEmail($email)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /** @return array|false */
    public static function findByUsername($username)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /** @return bool */
    public static function emailExists($email)
    {
        return self::findByEmail($email) !== false;
    }

    /** @return bool */
    public static function usernameExists($username)
    {
        return self::findByUsername($username) !== false;
    }

    /**
     * Creates a new user with a securely hashed password.
     * Registration always forces role = 'client' — admin accounts are
     * never created from a public form (see database/seed.php).
     *
     * @return int Newly created user id.
     */
    public static function create($username, $email, $password, $role = 'client')
    {
        $pdo = Database::getConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (username, email, password_hash, role) VALUES (:username, :email, :hash, :role)'
        );
        $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':hash'     => $hash,
            ':role'     => $role === 'admin' ? 'admin' : 'client',
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Verifies a login attempt.
     * @return array|false The user row on success, false on bad credentials.
     */
    public static function attempt($emailOrUsername, $password)
    {
        $user = filter_var($emailOrUsername, FILTER_VALIDATE_EMAIL)
            ? self::findByEmail($emailOrUsername)
            : self::findByUsername($emailOrUsername);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        return $user;
    }
}
