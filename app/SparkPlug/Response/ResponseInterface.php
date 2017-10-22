<?php
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:24
 */

namespace App\SparkPlug\Response;

interface ResponseInterface
{
    /**
     * Send the rendered Response zo zhe Browser
     */
    public function send(): void;
}