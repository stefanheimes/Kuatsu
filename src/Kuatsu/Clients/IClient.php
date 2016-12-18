<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 18.12.2016
 * Time: 23:05
 */

namespace Kuatsu\Clients;

/**
 * Interface IClient
 *
 * Try to handel the interactions. Think on one thing:
 *
 * You run on the current system. This is the master system.
 * This master system has two end points and has no knowing if
 * this client is local, remote or a dragon. So the system
 * tries to stream everything from one point to the other.
 *
 * The best way to handel all this is to thing from the master
 * as a middleware. It only execute some functions on the client
 * get a stream and say the other client do something with it.
 *
 * This should solve a lot of problems with the handling of files.
 * I hope it will save ram and execution time as well.
 *
 * After this, the best is to handle all and everything as files.
 *
 * 1. Create a file of something.
 * 2. Send it to the other client.
 * 3. Let work on it.
 * 4. Master get the file to work on the result of both.
 * 5. Repeat.
 *
 * This is the best way to handle all.
 * So you need the file functions and a many of run functions
 * to create some thing.
 *
 * Question:
 * Did we need a third client for the master. I just tell you
 * at the start we have two clients and one master. That the
 * master is one of the clients is crap so I think the best
 * is to handel the master as stand alone client. So it is
 * possible that a middleware can sync more clients or even
 * auto sync without the need of a full contao system. I think
 * this would be a nice idea.
 *
 * @package Kuatsu\Clients
 */
interface IClient
{
    /**
     * Get a file from the client.
     *
     * @param string $clientFilePath The path on the client.
     *
     * @param string $savePath       The path of the temp folder.
     *
     * @return mixed
     */
    public function getFile($clientFilePath, $savePath);

    /**
     * Put a file on the client.
     *
     * @param string $clientFilePath The path on the client.
     *
     * @param string $tmpFolderPath  The path of the temp folder.
     *
     * @return bool State of the action.
     *
     */
    public function putFile($clientFilePath, $tmpFolderPath);
}