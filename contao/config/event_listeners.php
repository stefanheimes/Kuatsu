<?php

use Kuatsu\Events\BuildEnvironmentEvent;

return array(
    BuildEnvironmentEvent::NAME => array(
        array(
            'Kuatsu\Listeners\BuildEnvironment::build',
            -200
        )
    )
);
