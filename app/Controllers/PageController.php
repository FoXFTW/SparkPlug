<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 15.11.2017
 * Time: 23:20
 */

namespace App\Controllers;


use App\SparkPlug\Response\ViewResponse;
use App\SparkPlug\Views\RawView;
use App\SparkPlug\Views\View;
use App\SparkPlug\Views\ViewInterface;

class PageController extends AbstractBaseController
{
    public function showIndexView()
    {
        return new ViewResponse('index');
    }
}
