<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 15.11.2017
 * Time: 23:20
 */

namespace App\Controllers;

use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Views\View;

/**
 * Class PageController
 */
class PageController extends Controller
{
    /**
     * Startseite des Shops
     *
     * @return \App\SparkPlug\Views\View
     * @throws \App\SparkPlug\Views\Exceptions\ViewNotFoundException
     */
    public function showIndexView()
    {
        $view = new View('index');
        $view->setVars([
            'varFromView' => 'Hello World!',
        ]);

        return $view;
    }
}
