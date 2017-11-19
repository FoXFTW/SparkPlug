<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:24
 */

namespace App\SparkPlug\Response;

/**
 * Interface ResponseInterface
 *
 * @package App\SparkPlug\Response
 */
interface ResponseInterface
{
    /**
     * Send the rendered Response to the Browser
     */
    public function send(): void;
}
