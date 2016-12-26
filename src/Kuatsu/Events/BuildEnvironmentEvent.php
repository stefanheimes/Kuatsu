<?php

namespace Kuatsu\Events;

use Kuatsu\Environment;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 26.12.2016
 * Time: 21:47
 */
class BuildEnvironmentEvent extends Event
{
    /**
     * Name.
     */
    const NAME = 'kuatsu.event.buildEnvironment';

    /**
     * The environment.
     *
     * @var Environment
     */
    private $env;

    /**
     * BuildEnvironmentEvent constructor.
     *
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    /**
     * Get the env.
     *
     * @return Environment
     */
    public function getEnv()
    {
        return $this->env;
    }
}