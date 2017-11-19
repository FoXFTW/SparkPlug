<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:32
 */

namespace App\SparkPlug\Views;

/**
 * Interface ViewInterface
 * @package App\SparkPlug\Views
 */
interface ViewInterface
{
    /**
     * Gibt den gerenderten Inhalt des Views zurück
     *
     * @return string Rendered View
     */
    public function getContent(): string;

    /**
     * HTTP Code des Views
     *
     * @return int http code
     */
    public function getHttpCode(): int;
}
