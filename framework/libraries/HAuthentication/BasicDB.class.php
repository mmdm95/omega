<?php

namespace HAuthentication;

abstract class BasicDB extends \HModel
{
    /**
     * Database name
     *
     * @var string|null
     *
     */
    private $dbName = null;

    /**
     * Select data from database
     *
     * @param string $table
     * @param array|string $columns
     * @param string|null $where
     * @param array $bindValues
     * @return array|bool
     *
     */
    protected function getDataFromDB($table, $columns = '*', $where = null, $bindValues = [])
    {
        $select = $this->select();
        $columns = is_string($columns) ? [$columns] : $columns;
        $select->from($table)->cols($columns);
        if (isset($where)) {
            $select->where($where);
        }
        $select->bindValues($bindValues);

        try {
            return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        } catch (HAException $e) {
            return false;
        }
    }

    /**
     *
     * @param string $table
     * @param array $bindValues
     * @return bool
     *
     */
    protected function setDataToDB($table, $bindValues)
    {
        $db = $this->getDb();
        $insert = $this->insert();
        $insert->into($table);
        if (!isset($bindValues[0])) {
            $insert->addRow($bindValues);
        } else {
            $insert->addRows($bindValues);
        }

        try {
            $stmt = $db->prepare($insert->getStatement());
            return $stmt->execute($insert->getBindValues());
        } catch (HAException $e) {
            return false;
        }
    }

    /**
     *
     * @param string $table
     * @param string|null $where
     * @param array|null $bindValues
     * @return bool
     *
     */
    protected function removeDataFromDB($table, $where = null, $bindValues = null)
    {
        $db = $this->getDb();
        $delete = $this->delete();
        $delete->from($table);
        if (isset($where) && $where != '') {
            $delete->where($where);
        }
        $delete->bindValues($bindValues);

        try {
            $stmt = $db->prepare($delete->getStatement());
            return $stmt->execute($delete->getBindValues());
        } catch (HAException $e) {
            return false;
        }
    }

    /**
     * Check if something is exists in database
     *
     * @param string $table
     * @param string $where
     * @param array $bindValues
     * @return bool
     *
     */
    protected function existsDataInDB($table, $where, $bindValues)
    {
        if ($this->countDataInDB($table, $where, $bindValues)) {
            return true;
        }
        return false;
    }

    /**
     * Get count of a specific thing from database
     *
     * @param string $table
     * @param string|null $where
     * @param array|null $bindValues
     * @return int
     *
     */
    protected function countDataInDB($table, $where = null, $bindValues = null)
    {
        $db = $this->getDb();
        $select = $this->select();
        $select->cols(['COUNT(*) AS count'])->from($table)->where($where)->bindValues($bindValues);

        $res = $db->fetchAll($select->getStatement(), $select->getBindValues());

        $res = count($res) ? $res[0]['count'] : 0;
        return $res;
    }

    /**
     * Create authentication tables and required columns
     *
     * @param array $tables
     * @param array $columns
     *
     */
    protected function createAuthTables($tables, $columns)
    {
        $this->_changeDBCollation();
        $this->_getDbName();
        foreach ($tables as $table => $name) {
            // Tables
            $this->_createTableIfNotExists($name);
            $this->_changeTableCollation($name);

            // Columns
            $columnInfo = $columns->$table;
            foreach ($columnInfo as $prop => $value) {
                if (mb_strtolower($prop) == 'constraint') {
                    $this->_alterConstraint($tables->$table, $value);
                } else {
                    $this->_alterColumn($tables->$table, $value->column, $value->type);
                }
            }
        }
    }

    protected function setAuthInformationToDB($authData)
    {
        $tables = $authData->data->tables;
        $columns = $authData->data->columns;
        $pages = $authData->data->pages;
        $roles = $authData->data->roles;
        $permissions = $authData->data->permissions;

        foreach ($pages as $page) {
            $this->setDataToDB($tables->page, [
                $columns->page->name->column => $page
            ]);
        }
        foreach ($roles as $role) {
            $this->setDataToDB($tables->role, [
                $columns->role->name->column => $role
            ]);
        }
        foreach ($permissions as $permission) {
            $this->setDataToDB($tables->permission, [
                $columns->permission->description->column => $permission
            ]);
        }
    }

    /**
     * Create a table if it is not exists
     *
     * @param string $table
     *
     */
    private function _createTableIfNotExists($table)
    {
        $db = $this->getDb();
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $db->quoteName($table);
        $sql .= '(id INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT)';
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Create(alter) column if it is not exists in table
     *
     * @param string $table
     * @param string $columnName
     * @param string $type
     *
     */
    private function _alterColumn($table, $columnName, $type)
    {
        $db = $this->getDb();
        $sql = "IF NOT EXISTS( SELECT NULL FROM INFORMATION_SCHEMA.COLUMNS ";
        $sql .= "WHERE table_name = '" . $table . "' AND table_schema = '" . $this->dbName . "' AND column_name = '" . $columnName . "') ";
        $sql .= "THEN ALTER TABLE `" . $table . "` ADD `" . $columnName . "` {$type}; ";
        $sql .= "END IF;";
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Alter some constraints to a table
     *
     * @param string $table
     * @param string $constraint
     *
     */
    private function _alterConstraint($table, $constraint)
    {
        $db = $this->getDb();
        $sql = "ALTER TABLE {$db->quoteName($table)}";
        $sql .= " {$constraint}";
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Get current database name
     */
    private function _getDbName()
    {
        $db = $this->getDb();
        try {
            $this->dbName = $db->fetchCol('select database()')[0];
        } catch (HAException $e) {
        }
    }

    /**
     * Change database collation to UTF8_General_Ci
     */
    private function _changeDBCollation()
    {
        $db = $this->getDb();
        if ($this->type == 'sqlsrv') {
            $sql = "ALTER DATABASE {$this->dbName} COLLATE utf8_general_ci;";
        } else {
            $sql = "ALTER DATABASE {$this->dbName} CHARACTER SET utf8 COLLATE utf8_general_ci;";
        }
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Change table collation to UTF8_General_Ci
     *
     * @param string $table
     *
     */
    private function _changeTableCollation($table)
    {
        $db = $this->getDb();
        $sql = "ALTER TABLE {$table} CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;";
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Alter foreign key constraint to a table
     *
     * @param string $table
     * @param string $fkName
     * @param string $fkColumn
     * @param string $refTable
     * @param string $refColumn
     *
     */
    private function alterAddForeignKey($table, $fkName, $fkColumn, $refTable, $refColumn)
    {
        $db = $this->getDb();
        $sql = "ALTER TABLE {$db->quoteName($table)}";
        $sql .= " ADD CONSTRAINT {$fkName} FOREIGN KEY ({$db->quoteName($fkColumn)}) REFERENCES {$db->quoteName($refTable)}({$db->quoteName($refColumn)})";
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }

    /**
     * Drop a foreign key constraint from a table
     *
     * @param string $table
     * @param string $fkName
     *
     */
    private function alterDropForeignKey($table, $fkName)
    {
        $db = $this->getDb();
        $sql = "ALTER TABLE {$db->quoteName($table)}";
        if ($this->type == 'sqlsrv') {
            $sql .= " DROP FOREIGN KEY {$fkName}";
        } else {
            $sql .= " DROP CONSTRAINT {$fkName}";
        }
        try {
            $db->exec($sql);
        } catch (HAException $e) {
        }
    }
}
