<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Model extends HModel
{
    public function insert_it($table, $columnValue, $rawColVal = [], $getLastID = false, $lastColumnName = 'id')
    {
        $db = $this->getDb();
        $insert = $this->insert();
        $insert->into($table);
        if (!isset($columnValue[0])) {
            $insert->addRow($columnValue);
        } else {
            $insert->addRows($columnValue);
        }
        if (count($rawColVal)) {
            foreach ($rawColVal as $col => $value) {
                $insert->set($col, $value);
            }
        }

        $stmt = $db->prepare($insert->getStatement());
        $result = $stmt->execute($insert->getBindValues());

        if ($getLastID) {
            if (isset($lastColumnName) && $lastColumnName != '') {
                $name = $insert->getLastInsertIdName($lastColumnName);
                $res = $db->lastInsertId($name);
                return $res;
            }
        }
        return $result;
    }

    public function update_it($table, $columnValue = [], $where = null, $params = [], $rawColVal = [])
    {
        $db = $this->getDb();
        $update = $this->update();
        $update->table($table);
        if (count($columnValue)) {
            $update->cols($columnValue);
        }
        if (count($rawColVal)) {
            foreach ($rawColVal as $col => $value) {
                $update->set($col, $value);
            }
        }
        if (isset($params) && $where != '') {
            $update->where($where);
        }
        $update->bindValues($params);

        $stmt = $db->prepare($update->getStatement());
        return $stmt->execute($update->getBindValues());
    }

    public function delete_it($table, $where = null, $params = [])
    {
        $db = $this->getDb();
        $delete = $this->delete();
        $delete->from($table);
        if (isset($where) && $where != '') {
            $delete->where($where);
        }
        $delete->bindValues($params);

        $stmt = $db->prepare($delete->getStatement());
        return $stmt->execute($delete->getBindValues());
    }

    public function select_it($subSelect = null, $table = null, $columns = '*', $where = null, $params = [], $groupBy = null, $orderBy = null, $limit = null, $offset = null, $getQuery = false)
    {
        $db = $this->getDb();
        $select = $this->select();
        $columns = !is_array($columns) ? [$columns] : $columns;
        $select->cols($columns);
        if (isset($table) && $table != '') {
            $select->from($table);
        } else if (isset($subSelect) && $subSelect != '') {
            $select->fromSubSelect($subSelect, 'sub_1');
        }
        if (isset($where) && trim($where) != '') {
            $select->where($where);
        }
        if (isset($groupBy) && count($groupBy)) {
            $select->groupBy($groupBy);
        }
        $orderByArr = [];
        if (is_string($orderBy)) {
            $orderByArr[] = $orderBy;
        } else if (is_array($orderBy)) {
            $orderByArr = $orderBy;
        }
        if (isset($orderByArr) && count($orderByArr)) {
            $select->orderBy($orderByArr);
        }
        if (isset($limit) && $limit != 0) {
            $select->limit($limit);
        }
        if ((isset($offset) && $offset != 0)) {
            $select->offset($offset);
        }
        if (isset($params) && count($params)) {
            $select->bindValues($params);
        }

        if ($getQuery) {
            return $select->getStatement();
        }

        $res = $db->fetchAll($select->getStatement(), $select->getBindValues());
        return $res;
    }

    public function join_it($subJoin = null, $table1 = null, $table2 = null, $columns = '*', $condition = null, $where = null, $params = [], $groupByOrHaving = null, $orderBy = null, $limit = null, $offset = null, $getQuery = false, $joinType = 'INNER')
    {
        $db = $this->getDb();
        $columns = !is_array($columns) ? [$columns] : $columns;
        $select = $this->select()->cols($columns);
        if (isset($table1) && $table1 != '') {
            $select->from($table1);
        }
        if (isset($subJoin) && $subJoin != '') {
            $alias = 'subJoin_1';
            if (isset($table2)) {
                $alias = $table2;
            }
            try {
                $select->joinSubSelect(
                    $joinType,
                    $subJoin,
                    $alias,
                    $condition
                );
            } catch (\Aura\SqlQuery\Exception $e) {
                die('unexpected error: ' . $e->getMessage());
            }
        } else {
            try {
                $select->join(
                    $joinType,
                    $table2,
                    $condition
                );
            } catch (\Aura\SqlQuery\Exception $e) {
                die('unexpected error: ' . $e->getMessage());
            }
        }
        if (isset($where) && trim($where) != '') {
            $select->where($where);
        }
        if (isset($groupByOrHaving) && count($groupByOrHaving)) {
            if(isset($groupByOrHaving['groupBy']) || isset($groupByOrHaving['having'])) {
                if (isset($groupByOrHaving['groupBy'])) {
                    $select->groupBy($groupByOrHaving['groupBy']);
                }
                if (isset($groupByOrHaving['having'])) {
                    $select->having($groupByOrHaving['having']);
                }
            } else {
                $select->groupBy($groupByOrHaving);
            }
        }
        $orderByArr = [];
        if (is_string($orderBy)) {
            $orderByArr[] = $orderBy;
        } else if (is_array($orderBy)) {
            $orderByArr = $orderBy;
        }
        if (isset($orderByArr) && count($orderByArr)) {
            $select->orderBy($orderByArr);
        }
        if (isset($limit) && $limit != 0) {
            $select->limit($limit);
        }
        if ((isset($offset) && $offset != 0)) {
            $select->offset($offset);
        }
        if (isset($params) && count($params)) {
            $select->bindValues($params);
        }

        if ($getQuery) {
            return $select->getStatement();
        }

        $res = $db->fetchAll($select->getStatement(), $select->getBindValues());
        return $res;
    }

    public function is_exist($table, $where, $params)
    {
        if ($this->it_count($table, $where, $params)) {
            return true;
        }
        return false;
    }

    public function it_count($table, $where = null, $params = [], $getQuery = false, $isSubSelect = false, $subSelectName = 'sub')
    {
        $db = $this->getDb();
        $select = $this->select();
        $select->cols(['COUNT(*) AS count']);

        if ($isSubSelect) {
            $select->fromSubSelect($table, $subSelectName);
        } else {
            $select->from($table);
        }

        if (isset($where) && trim($where) != '') {
            $select->where($where);
        }

        $select->bindValues($params);

        if ($getQuery) {
            return $select->getStatement();
        }

        $res = $db->fetchAll($select->getStatement(), $select->getBindValues());

        $res = count($res) ? $res[0]['count'] : 0;
        return $res;
    }

    public function execSP($SPName, $params = [])
    {
        $db = $this->getDb();
        $spParams = array_keys($params);
        $spVals = array_values($params);
        for ($i = 0; $i < count($spParams); $i++) {
            $spParams[$i] = trim($spParams[$i]) . '=:param_' . ($i + 1);
        }
        $spParamVals = [];
        for ($i = 0; $i < count($spVals); $i++) {
            $spParamVals['param_' . ($i + 1)][$i] = $spVals[$i];
        }

        $spParams = implode(',', $spParams);
        $sql = "EXEC " . $SPName . " " . $spParams;

        $res = $db->fetchAll($sql, $spParamVals);
        return $res;
    }

    public function transactionBegin()
    {
        $db = $this->getDb();
        return $db->beginTransaction();
    }

    public function transactionComplete()
    {
        $db = $this->getDb();
        return $db->commit();
    }

    public function transactionRollback()
    {
        $db = $this->getDb();
        return $db->rollBack();
    }
}
