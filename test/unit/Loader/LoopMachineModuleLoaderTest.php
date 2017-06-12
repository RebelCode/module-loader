<?php

namespace RebelCode\Modular\FuncTest;

use Dhii\Machine\LoopMachine;
use RebelCode\Modular\Loader\LoopMachineModuleLoader;
use RebelCode\Modular\Module\ModuleInterface;
use Xpmock\TestCase;

/**
 * Tests {@see RebelCode\Modular\Loader\LoopMachineModuleLoader}.
 *
 * @since [*next-version*]
 */
class LoopMachineModuleLoaderTest extends TestCase
{
    /**
     * The name of the module class or interface to use for testing.
     *
     * @since [*next-version*]
     */
    const MODULE_CLASSNAME = 'Dhii\\Modular\\Module\\ModuleInterface';

    /**
     * Create a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return LoopMachineModuleLoader
     */
    public function createInstance()
    {
        return new LoopMachineModuleLoader(new LoopMachine());
    }

    /**
     * Creates an instance of a module.
     *
     * @since [*next-version*]
     *
     * @param string $key The module key.
     *
     * @return ModuleInterface
     */
    public function createModuleInstance($key, $load = null)
    {
        return $this->mock(static::MODULE_CLASSNAME)
            ->getKey($key)
            ->load($load)
            ->new();
    }

    /**
     * Tests the constructor to assert whether the module loader is observing the loop machine.
     *
     * @since [*next-version*]
     */
    public function testConstructor()
    {
        $loopMachine = new LoopMachine();
        $subject     = new LoopMachineModuleLoader($loopMachine);

        $this->assertTrue(
            $loopMachine->getObservers()->contains($subject),
            'The module loader is not attached as an observer of the loop machine.'
        );
    }

    /**
     * Tests the module loading method to assert whether it correctly loads the modules.
     *
     * @since [*next-version*]
     */
    public function testLoad()
    {
        $subject = new LoopMachineModuleLoader(new LoopMachine());
        $modules = array(
            $this->createModuleInstance('mod-1', $this->once()),
            $this->createModuleInstance('mod-2', $this->once()),
            $this->createModuleInstance('mod-3', $this->once()),
            $this->createModuleInstance('mod-4', $this->once()),
        );

        $subject->load($modules);
    }
}
