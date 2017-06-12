<?php

namespace RebelCode\Modular\FuncTest\Loader;

use RebelCode\Modular\Loader\AbstractModuleLoader;
use RebelCode\Modular\Module\ModuleInterface;
use Xpmock\TestCase;

/**
 * Tests {@see RebelCode\Modular\Loader\AbstractModuleLoader}.
 *
 * @since [*next-version*]
 */
class AbstractModuleLoaderTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\\Modular\\Loader\\AbstractModuleLoader';

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
     * @return AbstractModuleLoader
     */
    public function createInstance($prepare = null, $canLoad = null, $handleUnloaded = null)
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
            ->_iterate();

        if (!is_null($prepare)) {
            $mock->_prepareModuleList($prepare);
        }

        if (!is_null($canLoad)) {
            $mock->_canLoadModule($canLoad);
        }

        if (!is_null($handleUnloaded)) {
            $mock->_handleUnloadedModule($handleUnloaded);
        }

        return $mock->new();
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
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME, $subject, 'Subject is not a valid instance.'
        );
    }

    public function testLoadModule()
    {
        $subject = $this->createInstance();
        $module  = $this->createModuleInstance('test');

        // Expect load() to be called once on the module.
        $module->mock()->load(null, $this->once());

        $subject->this()->_loadModule($module);
    }

    /**
     * Tests the module loading attempt method.
     *
     * @since [*next-version*]
     */
    public function testAttemptLoadModule()
    {
        $subject = $this->createInstance();
        $module  = $this->createModuleInstance('test');

        // Expect load() to be called once on the module.
        $module->mock()->load(null, $this->once());

        $subject->this()->_attemptLoadModule($module);
    }

    /**
     * Tests the module loading attempt method with a condition that is satisfied.
     *
     * @since [*next-version*]
     */
    public function testAttemptLoadModuleWithCondition()
    {
        $subject = $this->createInstance(
            null,
            function($module) {
                return stripos($module->getKey(), 'test-') === 0;
            }
        );

        $module = $this->createModuleInstance('test-module');

        // Expect load() to be called once on the module.
        $module->mock()->load(null, $this->once());

        $subject->this()->_attemptLoadModule($module);
    }

    /**
     * Tests the module loading attempt method with a condition that fails.
     *
     * @since [*next-version*]
     */
    public function testAttemptLoadModuleWithFailedCondition()
    {
        $subject = $this->createInstance(
            null,
            function($module) {
                return stripos($module->getKey(), 'test-') === 0;
            }
        );

        $module = $this->createModuleInstance('foo-module');

        // Expect load() to be NOT called on the module.
        $module->mock()->load(null, $this->never());

        $subject->this()->_attemptLoadModule($module);
    }
}
