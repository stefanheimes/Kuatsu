<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 18.12.2016
 * Time: 23:04
 */

namespace Kuatsu\Clients;


class Local implements IClient
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
    public function getFile($clientFilePath, $savePath)
    {
        // TODO: Implement getFile() method.
    }

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
    public function putFile($clientFilePath, $tmpFolderPath)
    {
        // TODO: Implement putFile() method.
    }
}