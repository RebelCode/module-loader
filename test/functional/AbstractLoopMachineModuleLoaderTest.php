<?php

namespace RebelCode\Modular\FuncTest;

use Dhii\Machine\LoopMachine;
use Dhii\Modular\ModuleInterface;
use RebelCode\Modular\AbstractLoopMachineModuleLoader;
use SplObserver;
use Xpmock\TestCase;

/**
 * Tests {@see RebelCode\Modular\AbstractLoopMachineModuleLoader}.
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
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\\Modular\\AbstractLoopMachineModuleLoader';

    /**
     * The name of the module class or interface to use for testing.
     *
     * @since [*next-version*]
     */
    const MODULE_CLASSNAME = 'Dhii\\Modular\\ModuleInterface';

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
     * @param string $id The ID of the module.
     *
     * @return ModuleInterface
     */
    public function createModuleInstance($id, $load = null)
    {
        return $this->mock(static::MODULE_CLASSNAME)
            ->getId(function() use ($id) { return $id; })
            ->load($load)
            ->new();
    }

    /**
     * Creates an {@see SplObserver} instance.
     *
     * @since [*next-version*]
     *
     * @param callable $update The notify-update callback. Default: null
     *
     * @return SplObserver The created instance.
     */
    public function createSplObserver($update = null)
    {
        $mock = $this->mock('\\SplObserver')
            ->update($update)
            ->new();

        return $mock;
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
            'RebelCode\\Modular\\AbstractModuleLoader',
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
     * Tests the observer attaching method.
     *
     * @since [*next-version*]
     */
    public function testAttach()
    {
        $subject     = $this->createInstance();
        $observer1   = $this->createSplObserver();
        $observer2   = $this->createSplObserver();

        $subject->this()->_attach($observer1);
        $subject->this()->_attach($observer2);

        /* @var $loopMachine LoopMachine */
        $loopMachine = $subject->this()->_getLoopMachine();
        $observers   = iterator_to_array($loopMachine->getObservers());

        $this->assertEquals(array($observer1, $observer2), $observers);
    }

    /**
     * Tests the observer detaching method.
     *
     * @since [*next-version*]
     */
    public function testDetach()
    {
        $subject     = $this->createInstance();
        $observer1   = $this->createSplObserver();
        $observer2   = $this->createSplObserver();
        $observer3   = $this->createSplObserver();

        $subject->this()->_attach($observer1);
        $subject->this()->_attach($observer2);
        $subject->this()->_attach($observer3);

        $subject->this()->_detach($observer2);

        /* @var $loopMachine LoopMachine */
        $loopMachine = $subject->this()->_getLoopMachine();
        $observers   = iterator_to_array($loopMachine->getObservers());

        $this->assertEquals(array($observer1, $observer3), $observers);
    }

    /**
     * Tests the observer notification method.
     *
     * @since [*next-version*]
     */
    public function testNotify()
    {
        $subject     = $this->createInstance();
        $invokedBy   = false;
        $observer    = $this->createSplObserver(function($notifier) use(&$invokedBy) {
            $invokedBy = $notifier;
        });

        $subject->this()->_attach($observer);
        $subject->this()->_notify();

        $this->assertSame($invokedBy, $subject->this()->_getLoopMachine());
    }

    /**
     * Tests the module loading method.
     *
     * @since [*next-version*]
     */
    public function testLoad()
    {
        $subject = $this->createInstance(
            null,
            null,
            null
        );
        $observer = $this->createSplObserver(function(LoopMachine $loopMachine) use ($subject) {
            if ($loopMachine->getState() === LoopMachine::STATE_LOOP) {
                $subject->this()->_attemptLoadModule($loopMachine->getCurrent());
            }
        });

        $subject->this()->_attach($observer);

        $expected = array('test-1', 'num-two', 'test-3');
        $loaded   = array();
        $onLoad   = function() use (&$loaded) {
            $loaded[] = $this->getId();
        };
        $modules = array(
            $this->createModuleInstance('test-1', $onLoad),
            $this->createModuleInstance('num-two', $onLoad),
            $this->createModuleInstance('test-3', $onLoad),
        );

        $subject->this()->_load($modules);

        $this->assertEquals($expected, $loaded);
    }
}
