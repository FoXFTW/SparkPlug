<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 18.11.2017
 * Time: 20:12
 */

namespace App\Tests;

use App\SparkPlug\Views\Exceptions\ViewNotFoundException;
use App\SparkPlug\Views\View;

class ViewClassTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        require __DIR__.'/../routes/web.php';
    }

    /**
     * @covers \App\SparkPlug\Views\View::getContent()
     * @covers \App\SparkPlug\Views\View::renderView()
     */
    public function testLoadView()
    {
        $view = new View('app');
        $this->assertContains('html', $view->getContent());
    }

    /**
     * @covers \App\SparkPlug\Views\View::getContent()
     * @covers \App\SparkPlug\Views\View::renderView()
     * @covers \App\SparkPlug\Views\View::renderSubViews()
     */
    public function testLoadViewWithTemplate()
    {
        $view = new View('user.auth.login');
        $this->assertContains('html', $view->getContent());
    }

    /**
     * @covers \App\SparkPlug\Views\View::getContent()
     * @covers \App\SparkPlug\Views\View::renderView()
     */
    public function testLoadViewWithoutTemplate()
    {
        $view = new View('modal.uploadRia');
        $this->assertNotContains('html', $view->getContent());
    }

    /**
     * @covers \App\SparkPlug\Views\View::getContent()
     * @covers \App\SparkPlug\Views\View::getRawContent()
     * @covers \App\SparkPlug\Views\View::renderView()
     */
    public function testRenderRoutes()
    {
        $view = new View('user.auth.login');
        $this->assertContains('@route(\'register\'', $view->getRawContent());
        $this->assertContains('/register', $view->getContent());
    }

    public function testViewNotFoundException()
    {
        $this->expectException(ViewNotFoundException::class);
        new View('nichtexistent');
    }
}
