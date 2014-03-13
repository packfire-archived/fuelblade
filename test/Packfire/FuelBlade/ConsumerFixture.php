<?php
namespace Packfire\FuelBlade;

class ConsumerFixture implements ConsumerInterface
{
    protected $container;

    protected $state;

    public function __construct(ContainerInterface $container, ConsumerInterface $state = null)
    {
        $this->container = $container;
        $this->state = $state;
    }

    public function state()
    {
        return $this->state;
    }

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
