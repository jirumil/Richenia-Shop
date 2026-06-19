<?php
/**
 * Database connection (PDO singleton).
 *
 * Default credentials match a stock XAMPP install
 * (host: localhost, user: root, password: '').
 * Edit the constants below if your setup differs.
 */

class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {
            $host    = 'localhost';
            $dbName  = 'richenia_db';
            $user    = 'root';
            $pass    = '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};dbname={$dbName};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                http_response_code(500);
                die(
                    'Database connection failed. Make sure MySQL is running in XAMPP and that ' .
                    'the "richenia_db" database has been imported from database/schema.sql. ' .
                    'Details: ' . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }
}
