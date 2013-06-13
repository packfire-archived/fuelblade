<?php
namespace Packfire\FuelBlade;

class ConsumerFixture implements ConsumerInterface
{
    protected $container;

    public function container()
    {
        return $this->container;
    }

    public function __invoke($container)
    {
        $this->container = $container;
        return $this;
    }
}
