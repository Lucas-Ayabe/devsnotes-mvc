<?php
namespace App\Models;

use PDO;

class Connection
{
    /**
     * Creates a new PDO instance
     *
     * @return \PDO|void
     */
    public static function createConnection()
    {
        $variablesIsSet =
            isset($_ENV['DB_HOST']) &&
            isset($_ENV['DB_NAME']) &&
            isset($_ENV['DB_USER']) &&
            isset($_ENV['DB_PASSWORD']);

        if ($variablesIsSet) {
            return new PDO(
                "mysql:dbname={$_ENV['DB_NAME']};host={$_ENV['DB_HOST']}",
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        }
    }
}
