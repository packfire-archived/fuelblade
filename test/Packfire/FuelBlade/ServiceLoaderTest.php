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
}