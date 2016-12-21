<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.12.2016
 * Time: 21:22
 */

namespace Kuatsu;

use Database;

class Environment
{
    /**
     * @var Database
     */
    private $database;

    /**
     * Get the database.
     *
     * @return Database Get the database.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Set the database.
     *
     * @param Database $database The database.
     *
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }
}