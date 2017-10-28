<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:35
 */

namespace App\Controllers;

use App\SparkPlug\Request\RequestInterface;

abstract class AbstractBaseController
{
    /** @var  \App\SparkPlug\Request\RequestInterface */
    private $request;

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
}
