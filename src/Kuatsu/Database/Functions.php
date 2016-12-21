<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.12.2016
 * Time: 19:29
 */

namespace Kuatsu\Database;


class Functions
{
    /**
     * Drop tables
     *
     * @param array   $arrTables List with tables
     * @param boolean $blnBackup if true the system will make a bakup from all tables
     */
    public function dropTable($arrTables, $blnBackup = true)
    {
        if ($blnBackup == true) {
            $this->strSuffixZipName = 'Auto-DB-Backup_RPC-Drop.zip';
            $this->runDump($arrTables, false);
        }

        $arrKnownTables = $this->Database->listTables();

        foreach ($arrTables as $value) {
            if (in_array($value, $arrKnownTables)) {
                $this->Database->query("DROP TABLE $value");
            }
        }
    }
}