<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 26.12.2016
 * Time: 22:54
 */

namespace Kuatsu\Listeners;


use Kuatsu\Events\BuildEnvironmentEvent;

class BuildEnvironment
{
    /**
     * Build the environment.
     *
     * @param BuildEnvironmentEvent $event
     *
     * @return void
     */
    public function build(BuildEnvironmentEvent $event)
    {
        $env = $event->getEnv();
        $env->setDatabase(\Database::getInstance());
    }
}