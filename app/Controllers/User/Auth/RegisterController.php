<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:36
 */

namespace App\Controllers\User\Auth;

use App\Models\User;
use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Validation\Validation;
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

    /**
     * @return \App\SparkPlug\Response\Redirect
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     */
    public function register()
    {
        $validator = new Validation();

        $data = $validator->validate(
            [
                'username' => 'username|unique:users|min:3|max:255',
                'password' => 'confirmed|min:3|max:255',
                'email' => 'email|unique:users|max:255',
            ],
            $this->request
        );

        $user = new User($data);
        $user->password = bcrypt($data['password']);

        try {
            $user->save();
        } catch (\PDOException $e) {
            session_set('error', ['Benutzername bereits vergeben']);

            return back();
        }

        session_set('message', 'Erfolgreich registriert');

        return redirect('/login');
    }
}
