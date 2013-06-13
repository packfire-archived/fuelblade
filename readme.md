#Packfire FuelBlade

##What is FuelBlade?

Packfire FuelBlade is a library that helps you the power of dependency injection into your PHP application. Through the [Inversion of Control](http://en.wikipedia.org/wiki/Inversion_of_control) (IoC) technique, you can decouple class dependencies and build better test-friendly code. 

##What is IoC?

Traditionally, a class is easily coupled like this:

````php

    class ConsoleOutput
    {
        public function write($message)
        {
            echo $message;
        }        
    }

    class TaskManager
    {
        protected $output;

        public function __construct()
        {
            $this->output = new ConsoleOutput();
        }
    }

    class Application
    {
        public function run()
        {
            $manager = new TaskManager();
            $manager->run();                  
        }
    }

````

However, `TaskManager` is now coupled to `ConsoleOutput`. Any output made by `TaskManager` can only go to console output and there is no flexibility in choosing which output to use. Hence, we can perform some abstraction magic and write some code like this:

````php

    interface OutputInterface
    {
        public function write($message);
    }

    class ConsoleOutput implements OutputInterface
    {
        public function write($message)
        {
            echo $message;
        }        
    }

    class FileOutput implements OutputInterface
    {
        public function write($message)
        {
            // write to file
        }        
    }

    class TaskManager
    {
        protected $output;

        public function __construct(OutputInterface $output)
        {
            $this->output = $output;
        }

    }

    class Application
    {
        public function run()
        {
            $ioc = new \Packfire\FuelBlade\Container();
            $ioc['manager'] = $this->share(
                function ($ioc) {
                    return new TaskManager($ioc['output']);
                }
            );
            $ioc['output'] = $this->share(new FileOutput());
            $ioc['manager']->run();                    
        }
    }

````

[Nettuts+ has a great article](http://net.tutsplus.com/tutorials/php/dependency-injection-huh/) on explaining the various methods of dependency injections, and ultimately explaining IoC as the ultimate solution. 

##Sounds Great! How do I install FuelBlade?

You can install FuelBlade from [Packagist](https://packagist.org/packages/packfire/fuelblade) via [Composer](https://getcomposer.org).

    {
        "require": {
            "packfire/fuelblade": "1.1.*"
        }
    }

All of the releases of Packfire FuelBlade are described on the package's Packagist page. Through Composer's autoloader (or your own), you will be able to use FuelBlade directly into your application. 