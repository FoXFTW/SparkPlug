<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:36
 */

namespace App\Controllers\User;

use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Views\View;

/**
 * Class AccountController
 * Controller für Login/Logout/Registrierung
 *
 * @package App\Controllers\User
 */
class AccountController extends Controller
{
    /**
     * Gibt Profil-View zurück
     *
     * @return \App\SparkPlug\Views\View
     */
    public function showProfileView()
    {
        return new View('user.profile');
    }
}
