<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:38
 */

namespace App\Controllers\User\Auth;

use App\SparkPlug\Auth\Auth;
use App\SparkPlug\Controllers\AbstractController as Controller;
use App\SparkPlug\Session;
use App\SparkPlug\Validation\Validation;
use App\SparkPlug\Views\View;
use App\SparkPlug\Views\ViewInterface;

/**
 * Class LoginController
 *
 * @package App\Controllers\User\Auth
 */
class LoginController extends Controller
{
    protected $redirectTo = '/profile';

    /**
     * Login View
     *
     * @return \App\SparkPlug\Views\ViewInterface
     * @throws \App\SparkPlug\Views\Exceptions\ViewNotFoundException
     */
    public function showLoginView(): ViewInterface
    {
        if (login_check()) {
            return redirect($this->redirectTo);
        }

        return new View('auth.login');
    }

    /**
     * @return mixed
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     */
    public function login()
    {
        $validator = new Validation();

        $data = $validator->validate(
            [
                'username' => 'username|min:3|max:255',
                'password' => 'min:3|max:255',
            ],
            $this->request
        );

        /** @var Auth $auth */
        $auth = app()->make(Auth::class);
        if (!$auth->attempt(
            $data
        )) {
            /** @var Session $session */
            session_set('error', ['Username or Password wrong']);
            session_set('username', $this->request->get('username'));

            return back();
        }

        return redirect($this->redirectTo);
    }

    /**
     * @return \App\SparkPlug\Response\Redirect
     */
    public function logout()
    {
        $session = app()->make(Session::class);
        $session->user = null;
        session_write_close();

        return redirect('/');
    }
}
