<?php
/**
 * Database connection (PDO singleton).
 *
 * Reads credentials from environment variables (set these in Vercel's
 * Project Settings -> Environment Variables). Falls back to a stock
 * XAMPP install when the env vars are not set, so local development
 * still works with zero configuration.
 */

class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {
            $host    = getenv('DB_HOST') ?: 'localhost';
            $port    = getenv('DB_PORT') ?: '3306';
            $dbName  = getenv('DB_NAME') ?: 'richenia_db';
            $user    = getenv('DB_USER') ?: 'root';
            $pass    = getenv('DB_PASS') ?: '';
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";

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
                    'Database connection failed. If running locally, make sure MySQL is ' .
                    'running in XAMPP and that the "richenia_db" database has been imported ' .
                    'from database/schema.sql. If running on Vercel, check that DB_HOST, ' .
                    'DB_PORT, DB_NAME, DB_USER, and DB_PASS are set correctly in Project ' .
                    'Settings -> Environment Variables. Details: ' . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }
}