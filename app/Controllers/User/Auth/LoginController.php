<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:38
 */

namespace App\Controllers\User\Auth;

use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Views\View;
use App\SparkPlug\Views\ViewInterface;

/**
 * Class LoginController
 *
 * @package App\Controllers\User\Auth
 */
class LoginController extends Controller
{
    /**
     * Login View
     *
     * @return \App\SparkPlug\Views\ViewInterface
     */
    public function showLoginView(): ViewInterface
    {
        return new View('user.auth.login');
    }
}
