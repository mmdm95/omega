<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

require_once LIB_PATH . 'HPayment/vendor/autoload.php';

class FactorModel extends HModel
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'factors AS f';
        $this->db = $this->getDb();
    }

    public function getFactors($where = '', $bindValues = [], $limit = null, $offset = 0)
    {
        $select = $this->select();
        $select->cols([
            'f.user_id AS u_id', 'f.factor_code', 'f.username AS f_username', 'f.full_name AS f_full_name', 'f.options AS f_options',
            'p.id AS p_id', 'p.title', 'p.slug', 'p.image AS p_image', 'u.id AS u_id', 'u.username', 'u.full_name', 'u.image AS u_image'
        ])->from($this->table);

        try {
            $select->join(
                'LEFT',
                'plans AS p',
                'f.plan_id=p.id'
            )->join(
                'LEFT',
                'users AS u',
                'f.user_id=u.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        if (!empty($where) && is_string($where)) {
            $select->where($where);
        }
        if (!empty($bindValues) && is_array($bindValues)) {
            $select->bindValues($bindValues);
        }

        if (!empty($limit) && is_numeric($limit)) {
            $select->limit((int)$limit);
        }
        $select->offset((int)$offset);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    public function getBuyers($params)
    {
        $select = $this->select();
        $select->cols([
            '*', 'f.options AS options', 'f.full_name AS f_full_name', 'f.created_at AS f_created_at', 'f.username AS f_username',
            'u.image AS u_image', 'p.image AS p_image'
        ])->from('factors AS f');

        try {
            $select->join(
                'INNER',
                'plans AS p',
                'p.id=f.plan_id'
            )->join(
                'LEFT',
                'users AS u',
                'f.user_id=u.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        if (isset($params['plan_id'])) {
            $select->where('f.plan_id=:pId')->bindValues(['pId' => $params['plan_id']]);
        }
        if (isset($params['payed']) && (bool)$params['payed']) {
            $select->where('f.payed_amount IS NOT NULL AND f.payed_amount>:pa')->bindValues(['pa' => 0]);
        }

        $select->groupBy(['f.id']);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }
}