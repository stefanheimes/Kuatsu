<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 18.12.2016
 * Time: 23:05
 */

namespace Kuatsu\Steps;


interface IStep
{

    /**
     * Check if this step has to be execute or not.
     *
     * @return boolean true => Yes | false => Nope.
     */
    public function checkRun();

    /**
     * Execute this step.
     *
     * @return void
     */
    public function runStep();
}