<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:35
 */

namespace App\SparkPlug\Controllers;

use App\SparkPlug\Request\RequestInterface;

/**
 * Class AbstractBaseController
 *
 * @package App\Controllers
 */
abstract class AbstractController
{
    /** @var  \App\SparkPlug\Request\RequestInterface */
    private $request;

    /**
     * Speichert gegebenen Request in Controller
     *
     * @param \App\SparkPlug\Request\RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }
}
