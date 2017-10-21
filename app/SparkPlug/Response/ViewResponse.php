<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:23
 */

namespace App\SparkPlug\Response;

use App\SparkPlug\Interfaces\ResponseInterface;

/**
 * Class ViewResponse returns a rendered View
 * @package App\SparkPlug\Response
 */
class ViewResponse implements ResponseInterface
{

    /**
     * Send the rendered Response zo zhe Browser
     */
    public function send(): void
    {
        // TODO: Implement send() method.
    }
}
