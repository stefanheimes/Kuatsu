<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.12.2016
 * Time: 22:49
 */

use Kuatsu\Environment;

/** @var Pimple $container */

$container['kuatsu.environment.default'] = $container->protect(
    function () {
        return new Environment();
    }
);