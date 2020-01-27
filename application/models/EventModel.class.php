<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class EventModel extends HModel
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'plan_comments AS pc';
        $this->db = $this->getDb();
    }

    public function getEventComments($where, $bindParams = [], $orderBy = [], $limit = 5, $offset = 0)
    {
        $select = $this->select();
        $select->cols([
            'pc.id', 'pc.name', 'pc.body', 'pc.is_buyer', 'pc.respond', 'pc.respond_on', 'pc.created_on', 'u.full_name'
        ])->from($this->table);

        try {
            $select->join(
                'LEFT',
                'users AS u',
                'pc.responder_id=u.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        $select->where($where);

        if (!empty($bindParams) && is_array($bindParams)) {
            $select->bindValues($bindParams);
        }
        if (!empty((int)$limit)) {
            $select->limit($limit)->offset((int)$offset);
        }
        if (!empty($orderBy) && is_array($orderBy)) {
            $select->orderBy($orderBy);
        }

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}