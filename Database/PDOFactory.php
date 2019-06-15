<?php

namespace Pam\Database;

use PDO;

/**
 * Class PDOFactory
 * @package Pam\Database
 */
class PDOFactory
{
    /**
     * @var null
     */
    static private $pdo = null;

    /**
     * @return PDO|null
     */
    public static function getConnection()
    {
        require_once '../../../../config/bdd.php';

        if (is_null(self::$pdo)) {

            $dsn        = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $options    = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            $pdo->exec('SET NAMES UTF8');
            self::$pdo = $pdo;

            return $pdo;
        }

        return self::$pdo;
    }
}

