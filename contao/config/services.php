<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.12.2016
 * Time: 22:49
 */

use Kuatsu\Environment;
use Kuatsu\Events\BuildEnvironmentEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/** @var Pimple $container */

/**
 * Build the environment.
 *
 * @param Pimple $c The container.
 *
 * @return Environment
 */
$container['kuatsu.environment.default'] = function ($c) {

    $env = new Environment();
    $event = new BuildEnvironmentEvent($env);
    /** @var EventDispatcher $eventDispatcher */
    $eventDispatcher = $c['event-dispatcher'];
    $eventDispatcher->dispatch($event::NAME, $event);

    return $event->getEnv();
};
