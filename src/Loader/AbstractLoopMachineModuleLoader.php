<?php

namespace RebelCode\Modular\Loader;

use Dhii\Machine\LoopMachine;
use Dhii\Modular\Module\ModuleInterface;
use SplObserver;
use SplSubject;

/**
 * Basic functionality for a module loader that uses a loop machine.
 *
 * @since [*next-version*]
 */
abstract class AbstractLoopMachineModuleLoader extends AbstractModuleLoader implements SplObserver
{
    /**
     * The loop machine instance.
     *
     * @since [*next-version*]
     *
     * @var LoopMachine
     */
    protected $loopMachine;

    /**
     * Internal parameterless constructor.
     *
     * @since [*next-version*]
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_getLoopMachine()->attach($this);
    }

    /**
     * Retrieves the loop machine instance.
     *
     * @since [*next-version*]
     *
     * @return LoopMachine
     */
    protected function _getLoopMachine()
    {
        return $this->loopMachine;
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
    protected function _setLoopMachine(LoopMachine $loopMachine)
    {
        $this->loopMachine = $loopMachine;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _iterate($modules)
    {
        $this->_getLoopMachine()->process($modules);

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @since [*next-version*]
     */
    public function update(SplSubject $subject)
    {
        // Only continue if the Loop Machine is in "loop state".
        if ($subject->getState() !== LoopMachine::STATE_LOOP) {
            return $this;
        }

        $module = $subject->getCurrent();

        if ($module instanceof ModuleInterface) {
            $this->_attemptLoadModule($module);
        }

        return $this;
    }
}
