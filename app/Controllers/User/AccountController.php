<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 12.10.2017
 * Time: 20:36
 */

namespace App\Controllers\User;


use App\Controllers\AbstractBaseController;
use App\SparkPlug\Response\ViewResponse;

class AccountController extends AbstractBaseController
{
    public function showLoginView()
    {
        return new ViewResponse('user.login');
    }

    public function showRegisterView()
    {
        return new ViewResponse('user.register');
    }
}
