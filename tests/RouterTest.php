<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use App\SparkPlug\Routing\Router;

/**
 * User: Hannes
 * Date: 23.10.2017
 * Time: 15:09
 */
class RouterTest extends TestCase
{

    public function testAddRoute()
    {
        Router::get('index', 'FakeController@method');
        $this->assertEquals(1, count(Router::$routes['GET']));
    }
}
