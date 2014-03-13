<?php
namespace Packfire\FuelBlade;

class ServiceLoadertest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWithoutParameters()
    {
        $services = array(
            'test' => array(
                'class' => 'stdClass'
            )
        );
        $container = new Container();
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
        $this->assertInstanceOf('\\stdClass', $container['test']);
    }

    public function testLoadWithParameters()
    {
        $services = array(
            'test' => array(
                'class' => '\\ReflectionMethod',
                'parameters' => array(
                    __CLASS__,
                    'testLoadWithParameters'
                )
            )
        );
        $container = new Container();
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
        $this->assertInstanceOf('\\ReflectionMethod', $container['test']);
        $this->assertEquals(array(), $container['test']->getParameters());
        $this->assertEquals('testLoadWithParameters', $container['test']->getName());
    }

    /**
     * @expectedException \Packfire\FuelBlade\ServiceLoadingException
     */
    public function testLoadException()
    {
        $services = array(
            'test' => array(
            )
        );
        $container = new Container();
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
    }

    public function testLoadConsumer()
    {
        $container = new Container();
        $services = array(
            'test' => array(
                'class' => '\\Packfire\\FuelBlade\\ConsumerFixture',
                'parameters' => array(
                    $container
                )
            )
        );
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
        $this->assertInstanceOf('\\Packfire\\FuelBlade\\ConsumerFixture', $container['test']);
        $this->assertEquals($container, $container['test']->container());
    }

    public function testLoadNonExists()
    {
        $services = array(
            'test' => array(
                'class' => '\\Packfire\\Dummy\\Mistake\\You\\No\\Me'
            )
        );
        $container = new Container();
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
        $this->assertNull($container['test']);
    }
    public function testLoadServiceParameters()
    {
        $services = array(
            'test' => array(
                'class' => '\\ReflectionMethod',
                'parameters' => array(
                    '@main',
                    'testLoadWithParameters'
                )
            ),
            'main' => array(
                'class' => '\\Packfire\\FuelBlade\\ServiceLoaderTest'
            )
        );
        $container = new Container();
        ServiceLoader::load($container, $services);
        $this->assertTrue(isset($container['test']));
        $this->assertInstanceOf('\\ReflectionMethod', $container['test']);
        $this->assertEquals(array(), $container['test']->getParameters());
        $this->assertEquals('testLoadWithParameters', $container['test']->getName());
    }
}
