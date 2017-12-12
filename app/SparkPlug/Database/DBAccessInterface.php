<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.11.2017
 * Time: 23:16
 */

namespace App\SparkPlug\Database;

use PDO;

/**
 * Interface DBAccessInterface
 * @package App\SparkPlug\Database
 */
interface DBAccessInterface
{
    /**
     * @return \PDO The created DB Object
     */
    public function getDB(): PDO;
}
