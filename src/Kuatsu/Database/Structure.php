<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 20.12.2016
 * Time: 19:30
 */

namespace Kuatsu\Database;

use \Database;
use Kuatsu\Environment;

class Structure
{
    /**
     * The database.
     *
     * @var Database
     */
    private $database;

    /**
     * List of default ignore values
     *
     * @var array
     */
    protected $arrDefaultValueTypIgnore = array(
        'text',
        'tinytext',
        'mediumtext',
        'longtext',
        'blob',
        'tinyblob',
        'mediumblob',
        'longblob',
        'time',
        'date',
        'datetime'
    );

    /**
     * A list with allowed keys for the field.
     *
     * @var array
     */
    protected $arrAllowedFieldKeys = array(
        'name',
        'type',
        'attributes',
        'null',
        'extra',
        'default'
    );

    /**
     * List of default ignore values
     *
     * @var array
     */
    protected $arrDefaultValueFunctionIgnore = array(
        "NOW",
        "CURRENT_TIMESTAMP",
    );

    /**
     * Structure constructor.
     */
    public function __construct()
    {
        /** @var Environment $environment */
        /** @var \Pimple $container */
        $environment    = $GLOBALS['container']['kuatsu.environment.default'];
        $this->database = $environment->getDatabase();
    }

    /**
     * Check if the field is a primary one.
     *
     * @param array $field The field information.
     *
     * @return bool
     */
    private function isPrimary($field)
    {
        return $field['type'] == 'index' && $field['name'] == 'PRIMARY';
    }

    /**
     * Check if the field is a unique one.
     *
     * @param array $field The field information.
     *
     * @return bool
     */
    private function isUnique($field)
    {
        return $field['type'] == 'index' && $field['index'] == 'UNIQUE';
    }

    /**
     * Check if the field is a key one.
     *
     * @param array $field The field information.
     *
     * @return bool
     */
    private function isKey($field)
    {
        return $field['type'] == 'index' && $field['index'] == 'KEY';
    }

    /**
     * Check if the field is ignored.
     *
     * @param array $field The field information.
     *
     * @return bool True ignored.
     */
    protected function isDefaultIgnored($field)
    {
        $type = strtolower($field['type']);

        return (in_array($type, $this->arrDefaultValueTypIgnore) || stristr($field['extra'], 'auto_increment'));
    }

    /**
     * Get all indexes for the field.
     *
     * @param array $indexes The indexes.
     *
     * @param array $field   The field settings.
     *
     * @return string The SQL for the table create.
     */
    private function getKeys($indexes, $field)
    {
        // Run each.
        foreach ($indexes as $valueIndexes) {
            // Find the right for the current field.
            if ($valueIndexes['Key_name'] == $field['name']) {
                // Check the type.
                switch ($valueIndexes['Index_type']) {
                    case 'FULLTEXT':
                        return sprintf(
                            'FULLTEXT KEY `%s` (%s)',
                            $field['name'],
                            $this->getKeyFields($field['index_fields'])
                        );

                    default:
                        return sprintf(
                            'KEY `%s` (%s)',
                            $field['name'],
                            $this->getKeyFields($field['index_fields'])
                        );
                }
            }
        }

        return '';
    }

    /**
     * Helper function which build the field list for the 'KEY' area
     * in the SQL.
     *
     * @param array $fieldList The list of keys.
     *
     * @return string The string.
     */
    private function getKeyFields($fieldList)
    {
        $return = array();

        foreach ($fieldList as $field) {
            if (preg_match('/.*\([0-9]+\)/i', $field)) {
                $cutPosition = stripos($field, '(');
                $name        = substr($field, 0, $cutPosition);
                $sub         = substr($field, $cutPosition);

                $return[] = sprintf('`%s` %s', $name, $sub);
            } else {
                $return[] = sprintf('`%s`', $field);
            }
        }

        return implode(', ', $return);
    }

    /**
     * Build a array with the structure of the database
     *
     * @param string $tableName The name of the table.
     *
     * @return array
     */
    public function getTableStructure($tableName)
    {
        // Get the field definition.
        $fields  = $this->getFieldDefinition($tableName);
        $options = $this->getTableOptions($tableName);

        return array_merge($fields, $options);
    }

    /**
     * Get the field definition.
     *
     * @param string $tableName The name of the table.
     *
     * @return array The list with the fields.
     */
    protected function getFieldDefinition($tableName)
    {
        // Get list of fields.
        $fields = $this
            ->database
            ->listFields($tableName);

        // Get list of indices.
        $arrIndexes = $this
            ->database
            ->prepare(sprintf('SHOW INDEX FROM `%s`', $tableName))
            ->execute()
            ->fetchAllAssoc();

        // Run each field.
        $return = [];
        foreach ($fields as $field) {
            // Get the name of the field.
            $name = $field['name'];

            if ($this->isPrimary($field)) {
                $return['TABLE_CREATE_DEFINITIONS'][$name] = sprintf(
                    'PRIMARY KEY (`%s`)',
                    implode('`,`', $field['index_fields'])
                );

                continue;
            } elseif ($this->isUnique($field)) {
                $return['TABLE_CREATE_DEFINITIONS'][$name] = sprintf(
                    'UNIQUE KEY `%s` (`%s`)',
                    $name,
                    implode('`,`', $field['index_fields'])
                );
                continue;
            } elseif ($this->isKey($field)) {
                $return['TABLE_CREATE_DEFINITIONS'][$name] = $this->getKeys($arrIndexes, $field);

                continue;
            }

            unset($field['index']);

            // Default values
            if ($this->isDefaultIgnored($field)) {
                unset($field['default']);
            } elseif (strtolower($field['default']) == 'null') {
                $field['default'] = 'default NULL';
            } elseif (is_null($field['default'])) {
                $field['default'] = '';
            } elseif (in_array(strtoupper($field['default']), $this->arrDefaultValueFunctionIgnore)) {
                $field['default'] = 'default ' . $field['default'];
            } else {
                $field['default'] = 'default \'' . $field['default'] . '\'';
            }

            // Field type
            if (strlen($field['length'])) {
                $field['type'] .= '(' . $field['length'] . (strlen($field['precision']) ? ',' . $field['precision'] : '') . ')';

                unset($field['length']);
                unset($field['precision']);
            }

            // Remove elements from the list, we did not want.
            foreach (array_diff(array_keys($field), $this->arrAllowedFieldKeys) as $strKeyForUnset) {
                unset($field[$strKeyForUnset]);
            }

            $field['name']                 = '`' . $field['name'] . '`';
            $return['TABLE_FIELDS'][$name] = trim(implode(' ', $field));
        }

        return $return;
    }

    /**
     * Get the table options.
     *
     * @param string $tableName The name of the table.
     *
     * @return array The options.
     */
    protected function getTableOptions($tableName)
    {
        // Table status
        $objStatus = $this
            ->database
            ->prepare('SHOW TABLE STATUS')
            ->execute();

        $return = [];
        while ($row = $objStatus->fetchAssoc()) {
            if ($row['Name'] != $tableName) {
                continue;
            }
            $return['TABLE_OPTIONS'] = ' ENGINE=' . $row['Engine'] . ' DEFAULT CHARSET=' . substr($row['Collation'], 0,
                    strpos($row['Collation'], '_')) . '';

            if ($row['Auto_increment'] != '') {
                $return['TABLE_OPTIONS'] .= ' AUTO_INCREMENT=' . $row['Auto_increment'] . ' ';
            }
        }

        return $return;
    }
}