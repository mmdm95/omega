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
}