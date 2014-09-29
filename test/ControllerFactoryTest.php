<?php
namespace Croute;

use Symfony\Component\HttpFoundation\Request;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexController()
    {
        $factory = $this->getFactory();

        $request = Request::create('/');
        $controllerName = $factory->getControllerName($request);
        $controller = $factory->getController($request, $controllerName);
        $this->assertTrue($controller instanceof IndexController);
    }

    public function testNamedController()
    {
        $factory = $this->getFactory();

        $request = Request::create('/named/');
        $controllerName = $factory->getControllerName($request);
        $controller = $factory->getController($request, $controllerName);
        $this->assertTrue($controller instanceof NamedController);
    }

    public function testSanitization()
    {
        $factory = $this->getFactory();

        $request = Request::create('/nam..\..ed/');
        $controllerName = $factory->getControllerName($request);
        $this->assertEquals('Named', $controllerName);
    }

    public function testNamespacedControllers()
    {
        $factory = $this->getFactory();

        $request = Request::create('/myNamespace/');
        $controllerName = $factory->getControllerName($request);
        $controller = $factory->getController($request, $controllerName);
        $this->assertTrue($controller instanceof \Croute\MyNamespace\IndexController);

        $request = Request::create('/myNamespace/named/');
        $controllerName = $factory->getControllerName($request);
        $controller = $factory->getController($request, $controllerName);
        $this->assertTrue($controller instanceof \Croute\MyNamespace\NamedController);
    }

    public function testControllerNotFound()
    {
        $factory = $this->getFactory();

        $request = Request::create('/asdf/');
        $controllerName = $factory->getControllerName($request);
        $controller = $factory->getController($request, $controllerName);
        $this->assertNull($controller);
    }

    protected function getFactory()
    {
        return new ControllerFactory(['Croute'], []);
    }
}

class IndexController extends Controller
{
}

class NamedController extends Controller
{
}

namespace Croute\MyNamespace;

use Croute\Controller;

class IndexController extends Controller
{
}

class NamedController extends Controller
{
}