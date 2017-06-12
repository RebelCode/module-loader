<?php

namespace RebelCode\Modular\Loader;

use Dhii\Modular\Module\ModuleInterface;
use RebelCode\Modular\Iterator\AbstractDependencyModuleIterator;
use RebelCode\Modular\Module\ModuleInterface as RebelCodeModuleInterface;

/**
 *  A module iterator that handles module dependencies.
 *
 * @since [*next-version*]
 */
class DependencyModuleIterator extends AbstractDependencyModuleIterator implements \Iterator
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param array $modules An array of modules.
     */
    public function __construct($modules = array())
    {
        $this->_setModules($modules);
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    protected function _getModuleDependencies(ModuleInterface $module)
    {
        if (! $module instanceof RebelCodeModuleInterface) {
            return array();
        }

        $_this     = $this;
        $keys      = $module->getDependencies();
        $instances = array_map(function($key) use ($_this) {
            return $_this->_getModuleByKey($key);
        }, $keys);

        return array_combine($keys, $instances);
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function rewind()
    {
        return $this->_rewind();
    }
    
    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function current()
    {
        return $this->_current();
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function key()
    {
        return $this->_key();
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function next()
    {
        return $this->_next();
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function valid()
    {
        return $this->_valid();
    }
}
