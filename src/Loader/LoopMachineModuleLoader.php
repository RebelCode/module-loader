<?php

namespace RebelCode\Modular\Loader;

use Dhii\Machine\LoopMachine;
use Dhii\Modular\Loader\ModuleLoaderInterface;

/**
 * A module loader that utilises the functionality of a loop machine for loading modules.
 *
 * @since [*next-version*]
 */
class LoopMachineModuleLoader extends AbstractLoopMachineModuleLoader implements ModuleLoaderInterface
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     */
    public function __construct(LoopMachine $loopMachine)
    {
        $this->_setLoopMachine($loopMachine);
        $this->_construct();
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function load($modules = array())
    {
        $this->_load($modules);

        return $this;
    }
}
