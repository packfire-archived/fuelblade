<?php
namespace Packfire\FuelBlade;

class IConsumerTest extends \PHPUnit_Framework_TestCase implements IConsumer {
    
    private $loadedContainer;
    
    public function testInvoke(){
        $c = new Container();
        $c['test'] = $this;
        $self = $c['test'];
        $this->assertEquals($this, $self);
        $this->assertEquals($c, $self->loadedContainer);
    }
    
    public function __invoke($container) {
        $this->assertInstanceOf('\\Packfire\\FuelBlade\\Container', $container);
        $this->loadedContainer = $container;
        return $this;
    }
    
}