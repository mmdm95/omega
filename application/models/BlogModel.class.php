<?php
defined('BASE_PATH') OR exit('No direct script access allowed');


require_once LIB_PATH . 'HPayment/vendor/autoload.php';

class BlogModel extends HModel
{
    public function __construct()
    {
        parent::__construct();

        $this->table = 'users';
        $this->db = $this->getDb();
    }

    public function getAllBlog($limit = null, $offset = 0)
    {
        $select = $this->select();
        $select->cols([
            'b.image', 'b.title', 'b.slug', 'b.writer', 'b.created_at',
            'b.updated_at', 'c.category_name'
        ])->from('blog AS b');

        try {
            $select->join(
                'LEFT',
                'categories AS c',
                'b.category_id=c.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        $select->where('publish=:pub')->bindValues(['pub' => 1]);

        if (!empty((int)$limit)) {
            $select->limit($limit);
        }
        $select->orderBy(['b.id DESC'])->offset($offset);

        return $this->db->fetchAll($select->getStatement(), $select->getBindValues());
    }

    public function getBlogDetail($params)
    {
        $select = $this->select();
        $select->cols([
            '*', 'b.id As id', 'b.publish AS publish', 'c.id AS c_id', 'c.publish AS c_publish'
        ])->from('blog AS b');

        try {
            $select->join(
                'LEFT',
                'categories AS c',
                'b.category_id=c.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        if ($params['slug']) {
            $select->where('slug=:slug')->bindValues(['slug' => $params['slug']]);
        }
        $select->where('b.publish=:pub')->bindValues(['pub' => 1]);

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) return $res[0];
        return [];
    }

    public function getSiblingBlog($where, $param = [])
    {
        $select = $this->select();
        $select->cols([
            'b.title', 'b.id AS id', 'c.category_name', 'c.id AS c_id'
        ])->from('blog AS b');

        try {
            $select->join(
                'LEFT',
                'categories AS c',
                'b.category_id=c.id'
            );
        } catch (\Aura\SqlQuery\Exception $e) {
            die('unexpected error: ' . $e->getMessage());
        }

        $select->where($where);
        if (!empty($param) && is_array($param)) {
            $select->bindValues($param);
        }

        $select->where('b.publish=:pub')->bindValues(['pub' => 1]);

        $res = $this->db->fetchAll($select->getStatement(), $select->getBindValues());
        if (count($res)) return $res[0];
        return [];
    }
}