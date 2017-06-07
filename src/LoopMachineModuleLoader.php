<?php

namespace RebelCode\Modular\Loader;

use Dhii\Machine\LoopMachine;
use Dhii\Modular\ModuleLoaderInterface;

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
     * Retrieves the loop machine instance.
     *
     * @since [*next-version*]
     *
     * @return LoopMachine
     */
    public function getLoopMachine()
    {
        return $this->_getLoopMachine();
    }

    /**
     * Sets the loop machine instance.
     *
     * @since [*next-version*]
     *
     * @param LoopMachine $loopMachine The loop machine.
     *
     * @return $this
     */
    public function setLoopMachine(LoopMachine $loopMachine)
    {
        $this->_setLoopMachine($loopMachine);

        return $this;
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
