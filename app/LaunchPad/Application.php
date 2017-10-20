<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\LaunchPad;

use App\LaunchPad\Interfaces\ResponseInterface;
use App\LaunchPad\Request\Request;

class Application
{
    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function handle(Request $request): ResponseInterface
    {

    }
}
