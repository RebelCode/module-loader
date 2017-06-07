<?php

namespace RebelCode\Modular;

use Dhii\Machine\LoopMachine;
use SplObserver;
use SplSubject;

/**
 * Basic functionality for a module loader that uses a loop machine.
 *
 * @since [*next-version*]
 */
abstract class AbstractLoopMachineModuleLoader extends AbstractModuleLoader
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
     * Attaches an observer.
     *
     * @since [*next-version*]
     *
     * @param SplObserver $observer The observer to attach.
     * @param int         $priority The priority: higher numbers indicate earlier notification. Default: 0
     *
     * @return $this
     */
    protected function _attach(SplObserver $observer, $priority = 0)
    {
        $this->_getLoopMachine()->attach($observer, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @param SplObserver $observer The observer to detach.
     *
     * @return $this
     */
    protected function _detach(SplObserver $observer)
    {
        $this->_getLoopMachine()->detach($observer);

        return $this;
    }

    /**
     * Notifies the observers.
     *
     * @since [*next-version*]
     *
     * @return $this
     */
    protected function _notify()
    {
        $this->_getLoopMachine()->notify();

        return $this;
    }

    /**
     * Performs updating when a subject notified this instance.
     *
     * @since [*next-version*]
     *
     * @param SplSubject $subject The subject that notified this instance.
     *
     * @return $this
     */
    protected function _update(SplSubject $subject)
    {
        // Only continue is subject is a Loop Machine Module Loader.
        if (!$subject instanceof AbstractLoopMachineModuleLoader) {
            return $this;
        }

        // Only continue if the Loop Machine is in "loop state".
        if ($subject->_getLoopMachine()->getState() !== LoopMachine::STATE_LOOP) {
            return $this;
        }

        $module = $subject->_getLoopMachine()->getCurrent();

        $this->_attemptLoadModule($module);

        return $this;
    }
}
