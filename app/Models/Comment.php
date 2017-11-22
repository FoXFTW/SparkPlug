<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 25.11.2017
 * Time: 12:21
 */

namespace App\Models;

use App\SparkPlug\Models\AbstractBaseModel as Model;

class Comment extends Model
{
    protected $table = 'comments';
}
