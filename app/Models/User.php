<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.11.2017
 * Time: 19:56
 */

namespace App\Models;

use App\SparkPlug\Models\AbstractBaseModel as Model;

/**
 * Class User
 */
class User extends Model
{
    protected $table = 'users';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'username',
        'email',
    ];
}
