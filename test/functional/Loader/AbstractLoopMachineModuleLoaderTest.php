<?php

namespace RebelCode\Modular\FuncTest\Loader;

use Dhii\Machine\LoopMachine;
use RebelCode\Modular\Loader\AbstractLoopMachineModuleLoader;
use RebelCode\Modular\Module\ModuleInterface;
use Xpmock\MockWriter;
use Xpmock\TestCase;

/**
 * Tests {@see RebelCode\Modular\Loader\AbstractLoopMachineModuleLoader}.
 *
 * @since [*next-version*]
 */
class AbstractLoopMachineModuleLoaderTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\\Modular\\Loader\\AbstractLoopMachineModuleLoader';

    /**
     * The name of the module class or interface to use for testing.
     *
     * @since [*next-version*]
     */
    const MODULE_CLASSNAME = 'Dhii\\Modular\\Module\\ModuleInterface';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return AbstractLoopMachineModuleLoader
     */
    public function createInstance(LoopMachine $loopMachine = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->new();

        $mock->this()->loopMachine = is_null($loopMachine)
            ? new LoopMachine()
            : $loopMachine;

        return $mock;
    }

    /**
     * Creates an instance of a module.
     *
     * @since [*next-version*]
     *
     * @param string   $key  The module key.
     * @param callable $load The load callback.
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
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'Subject is not a valid instance.'
        );

        $this->assertInstanceOf(
            'RebelCode\\Modular\\Loader\\AbstractModuleLoader',
            $subject,
            'Subject is not a valid AbstractModuleLoader instance.'
        );
    }

    /**
     * Tests the loop machine getter and setter methods.
     *
     * @since [*next-version*]
     */
    public function testGetSetLoopMachine()
    {
        $subject     = $this->createInstance();
        $loopMachine = new LoopMachine();

        $subject->this()->_setLoopMachine($loopMachine);

        $this->assertSame($loopMachine, $subject->this()->_getLoopMachine());
    }

    /**
     * Tests the module loading method.
     *
     * @since [*next-version*]
     */
    public function testLoad()
    {
        $mock = new MockWriter(static::TEST_SUBJECT_CLASSNAME, $this, array(
            'loopMachine'    => new LoopMachine(),

            // Only load modules with keys prefixed with "test-"
            '_canLoadModule' => function($module) {
                return stripos($module->getKey(), 'test-') === 0;
            }
        ));

        // Expect the update method to be called exactly 3 times.
        $mock->update($this->atLeast(3));

        $subject = $mock->new();
        $subject->this()->_construct();

        // Module 1: expected to be loaded.
        $module1 = $this->createModuleInstance('test-1')->mock()->load(null, $this->once());
        // Module 2: expected to be ignored.
        $module2 = $this->createModuleInstance('module2')->mock()->load(null, $this->never());
        // Module 3: expected to be loaded.
        $module3 = $this->createModuleInstance('test-3')->mock()->load(null, $this->once());

        $subject->this()->_load(array($module1, $module2, $module3));
    }
}
