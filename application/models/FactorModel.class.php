<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use HPayment\PaymentClasses\PaymentZarinPal;

require_once LIB_PATH . 'HPayment/vendor/autoload.php';

class FactorModel extends HModel
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'factors AS f';
        $this->db = $this->getDb();
    }

    public function getFactors($where = '', $params = [])
    {
//        $select = $this->select();
//        $select->cols([
//            ''
//        ])->from($this->table);
//
//        try {
//            $select->join(
//                'INNER',
//                '',
//                ''
//            );
//        } catch (\Aura\SqlQuery\Exception $e) {
//            die('unexpected error: ' . $e->getMessage());
//        }
//
//        if(!empty($where) && is_string($where)) {
//            $select->where('f.user_id=:uId');
//            if(is_array($params) && count($params)) {
//                $select->bindValues($params);
//            }
//        }
//
//        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        return [];
    }

    public function getBuyers($params)
    {
        $select = $this->select();
        $select->cols([
            '*', 'f.options AS options'
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