<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 25.11.2017
 * Time: 19:47
 */

namespace App\SparkPlug\Response;

/**
 * Class Redirect
 * Erstellt Redirect auf angegebene URL
 */
class Redirect implements ResponseInterface
{
    private $path;

    /**
     * Redirect constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Send the rendered Response to the Browser
     */
    public function send(): void
    {
        $url = config('app.url');

        http_response_code(301);
        header("Location: {$url}{$this->path}");
        echo "Redirecting to {$url}{$this->path}";
        exit;
    }
}
