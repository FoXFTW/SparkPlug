<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.10.2017
 * Time: 21:53
 */

namespace App\SparkPlug\Request;

/**
 * Interface RequestInterface
 *
 * @package App\SparkPlug\Request
 */
interface RequestInterface
{
    /**
     * Erstellt eine Request Instanz mit dem aktuellen HTTP Request
     *
     * @return \App\SparkPlug\Request\Request
     */
    public static function capture(): Request;

    /**
     * Erstellt Request anhand von Array
     *
     * @param array $data
     *
     * @return \App\SparkPlug\Request\Request
     */
    public static function createFromArray(array $data): Request;

    /**
     * Gibt URI Des Requests zurück
     *
     * @return string
     */
    public function getUri(): string;

    /**
     * Gibt Methode des Requests zurück [GET|POST]
     *
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * Durchsucht GET und POST Parameter nach gegebenem Key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);
}
