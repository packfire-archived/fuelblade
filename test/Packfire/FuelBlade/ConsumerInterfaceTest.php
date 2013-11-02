<?php
namespace Packfire\FuelBlade;

class ConsumerInterfaceTest extends \PHPUnit_Framework_TestCase implements ConsumerInterface
{
    private $loadedContainer;

    public function testInvoke()
    {
        $c = new Container();
        $c['test'] = $this;
        $self = $c['test'];
        $this->assertEquals($this, $self);
        $this->assertEquals($c, $self->loadedContainer);
    }

    public function __invoke($container)
    {
        $this->assertInstanceOf('\\Packfire\\FuelBlade\\Container', $container);
        $this->loadedContainer = $container;
        return $this;
    }
}
