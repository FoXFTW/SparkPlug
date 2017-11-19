<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:36
 */

namespace App\Controllers\User\Auth;

use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Views\View;
use App\SparkPlug\Views\ViewInterface;

/**
 * Class RegisterController
 *
 * @package App\Controllers\User\Auth
 */
class RegisterController extends Controller
{
    /**
     * Register View
     *
     * @return \App\SparkPlug\Views\ViewInterface
     */
    public function showRegisterView(): ViewInterface
    {
        return new View('user.auth.register');
    }
}
