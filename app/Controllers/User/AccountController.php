<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:36
 */

namespace App\Controllers\User;

use App\Controllers\AbstractBaseController;
use App\SparkPlug\Views\View;

/**
 * Class AccountController
 * Controller für Login/Logout/Registrierung
 *
 * @package App\Controllers\User
 */
class AccountController extends AbstractBaseController
{
    /**
     * Gibt Login View zurück
     *
     * @return \App\SparkPlug\Views\View
     */
    public function showLoginView()
    {
        return new View('user.login');
    }

    /**
     * Gibt Registrierungs View zurück
     *
     * @return \App\SparkPlug\Views\View
     */
    public function showRegisterView()
    {
        return new View('user.register');
    }
}
