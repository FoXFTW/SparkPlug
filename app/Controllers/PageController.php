<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 15.11.2017
 * Time: 23:20
 */

namespace App\Controllers;

use App\SparkPlug\Views\View;

class PageController extends AbstractBaseController
{
    public function showIndexView()
    {
        return new View('index');
    }
}
