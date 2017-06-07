<?php

namespace RebelCode\Modular;

use Dhii\Modular\ModuleInterface;
use Traversable;

/**
 * Basic functionality for a module loader.
 *
 * @since [*next-version*]
 */
abstract class AbstractModuleLoader
{
    /**
     * Loads the given set of modules.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface[]|Traversable $modules The modules to load.
     *
     * @return $this
     */
    protected function _load($modules)
    {
        $preparedList = $this->_prepareModuleList($modules);

        $this->_iterate($preparedList);

        return $this;
    }

    /**
     * Iterates over the list of modules and attempts to load them.
     *
     * This method should use the {@see AbstractModuleLoader::_attemptLoadModule()} method.
     *
     * @uses AbstractModuleLoader::_attemptLoadModule()
     *
     * @param ModuleInterface[]|\Traversable $modules The modules.
     *
     * @return $this
     */
    protected function _iterate($modules)
    {
        foreach ($modules as $_module) {
            $this->_attemptLoadModule($_module);
        }

        return $this;
    }

    /**
     * Attempts to load a module.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface $module The module instance to try to load.
     */
    protected function _attemptLoadModule(ModuleInterface $module)
    {
        if ($this->_canLoadModule($module)) {
            $this->_loadModule($module);

            return;
        }

        $this->_handleUnloadedModule($module);
    }

    /**
     * Loads a single module.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface $module The module instance.
     *
     * @return $this
     */
    protected function _loadModule(ModuleInterface $module)
    {
        $module->load();

        return $this;
    }

    /**
     * Prepares the module list before loading.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface[]|\Traversable $modules The module collection.
     *
     * @return ModuleInterface[]|\Traversable
     */
    protected function _prepareModuleList($modules)
    {
        return $modules;
    }

    /**
     * Determines if a module can be loaded.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface $module The module instance.
     *
     * @return bool True if the module can be loaded. False if not.
     */
    protected function _canLoadModule(ModuleInterface $module)
    {
        return true;
    }

    /**
     * Triggered when a module was not loaded.
     *
     * @since [*next-version*]
     *
     * @param ModuleInterface $module The module instance.
     *
     * @return $this
     */
    protected function _handleUnloadedModule(ModuleInterface $module)
    {
        // default implementation does nothing
        return $this;
    }
}
