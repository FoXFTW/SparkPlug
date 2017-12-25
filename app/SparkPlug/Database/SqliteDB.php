<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.11.2017
 * Time: 23:21
 */

namespace App\SparkPlug\Database;

use App\SparkPlug\Database\Exceptions\ConnectionException;
use App\SparkPlug\Database\Exceptions\DatabaseNotFoundException;
use App\SparkPlug\Exceptions\MissingPHPExtensionException;
use PDO;
use PDOException;

/**
 * Class SqliteDB
 */
class SqliteDB implements DBAccessInterface
{
    /** @var  \PDO */
    private $db;
    private $dbFile;

    /**
     * SqliteDB constructor.
     *
     * @throws \App\SparkPlug\Database\Exceptions\ConnectionException
     * @throws \App\SparkPlug\Database\Exceptions\DatabaseNotFoundException
     * @throws \App\SparkPlug\Exceptions\MissingPHPExtensionException
     */
    public function __construct()
    {
        $database = config('database.connections.sqlite.database');

        if (!extension_loaded('pdo_sqlite')) {
            throw new MissingPHPExtensionException('SQLite Database needs the php_pdo_sqlite Extension!');
        }

        if (!file_exists($database)) {
            throw new DatabaseNotFoundException("Database {$database} not found in ".database_path(''));
        }

        $this->dbFile = $database;

        try {
            $this->db = new PDO("sqlite:{$this->dbFile}");
        } catch (PDOException $e) {
            throw new ConnectionException("Connecting to DB {$database} failed with Message {$e->getMessage()}");
        }

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, config('database.fetch'));
    }

    /**
     * @return \PDO The created DB Object
     */
    public function getDB(): PDO
    {
        return $this->db;
    }
}
