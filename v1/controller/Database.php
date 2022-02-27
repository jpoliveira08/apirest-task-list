<?php

declare(strict_types=1);

namespace App\v1\controller;

use PDO;

class Database
{
    /** @var PDO $writeDbConnection Write database on master Database */
    private static $writeDbConnection;
    /** @var PDO $readDbConnection Read Database cluster */
    private static $readDbConnection;

    /**
     * Singleton pattern for database connection
     * Once we've already instantiated a connection,
     * We don't want to keep creating connections, just reuse
     * the connection that's already created
     *
     * @return PDO
     */
    public static function connectWriteDb(): ?PDO
    {
        /**
         * If it hasn't been initiated, then this statement will provide the initiation
         */
        if (is_null(self::$writeDbConnection)) {
            /**
             * Create the database connection
             */
            self::$writeDbConnection = new PDO(
                'mysql:host=localhost;dbname=tasksdb;charset=utf8',
                'admin',
                '!Q3dFKOMkciR'
            );
            /**
             * Setting error mode for the connection to use exceptions
             */
            self::$writeDbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /**
             * Some databases doesn't handles with prepares statements, with that we set the emulate
             * But, we are working with sql, that allows prepares statements so we set the emulate to false
             */
            self::$writeDbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$writeDbConnection;
    }

    /**
     * Read ones probaby have a DNS name or somethin
     *
     * @return PDO
     */
    public static function connectReadDb(): ?PDO
    {
        if (is_null(self::$writeDbConnection)) {
            self::$readDbConnection = new PDO(
                'mysql:host=localhost;dbname=tasksdb;charset=utf8',
                'admin',
                '!Q3dFKOMkciR'
            );
            self::$readDbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$readDbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$readDbConnection;
    }
}
