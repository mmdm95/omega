<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

use Aura\SqlQuery\QueryFactory;
use Aura\Sql\ConnectionLocator;
use Aura\Sql\ExtendedPdo;

require_once LIB_PATH . 'aura/sql/vendor/autoload.php';
require_once LIB_PATH . 'aura/sql-query/vendor/autoload.php';

abstract class HModel
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    private $types = array();

    /**
     * @var Aura\Sql\ConnectionLocator|null
     */
    private $locator = null;

    /**
     * @var Aura\SqlQuery\QueryFactory|null
     */
    private $factory = null;

    /**
     * @var Aura\Sql\ExtendedPdoInterface|null
     */
    protected $db = null;

    public function __construct()
    {
    	$this->db = $this->getDb();
    }

    /**
     * @param string $name
     * @param string $type
     * @return \Aura\Sql\ExtendedPdoInterface
     */
    protected function getDb($name = 'default', $type = 'read')
    {
        if (isset($this->db)) {
            return $this->db;
        }
        $this->setDb($name, $type);
        return $this->db;
    }

    /**
     * @param string $name
     * @param string $type
     * @return HModel
     */
    private function setDb($name = 'default', $type = 'read')
    {
        if (!isset($this->locator)) {
            $this->setConnectionLocator();
        }

        if (strtolower($name) != 'default' && strtolower($type) == 'read') {
            $this->db = $this->locator->getRead($name);
            $this->type = $this->types[$type][$name];
        } else if (strtolower($name) != 'default' && strtolower($type) == 'write') {
            $this->db = $this->locator->getWrite($name);
            $this->type = $this->types[$type][$name];
        } else {
            $this->db = $this->locator->getDefault();
            $this->type = $this->types[$name];
        }

        if (!$this->db->isConnected()) {
            $this->db->connect();
        }

        $this->factory = new QueryFactory($this->type);

        return $this;
    }

    /**
     * @return Aura\SqlQuery\Common\SelectInterface|Aura\SqlQuery\Common\Select|null
     */
    protected function select()
    {
        if (isset($this->factory)) {
            return $this->factory->newSelect();
        }
        return null;
    }

    /**
     * @return Aura\SqlQuery\Common\InsertInterface|Aura\SqlQuery\Common\Insert|null
     */
    protected function insert()
    {
        if (isset($this->factory)) {
            return $this->factory->newInsert();
        }
        return null;
    }

    /**
     * @return Aura\SqlQuery\Common\UpdateInterface|null
     */
    protected function update()
    {
        if (isset($this->factory)) {
            return $this->factory->newUpdate();
        }
        return null;
    }

    /**
     * @return Aura\SqlQuery\Common\DeleteInterface|Aura\SqlQuery\Common\Delete|null
     */
    protected function delete()
    {
        if (isset($this->factory)) {
            return $this->factory->newDelete();
        }
        return null;
    }

    private function setConnectionLocator()
    {
        $dbs = json_decode(DATABASE_STR, true);
        $default = null;
        $read = [];
        $write = [];
        $types = [];

        foreach ($dbs as $k => $v) {
            if (strtolower($k) == 'default') {
                $types['default'] = $v['type'];
                $dsn = $v['dsn'];
                $uName = $v['username'];
                $pass = $v['password'];
                $opts = $v['options'];
                $default = function () use ($dsn, $uName, $pass, $opts) {
                    return new ExtendedPdo(
                        $dsn,
                        $uName,
                        $pass,
                        $opts
                    );
                };
            } else if (strtolower($k) == 'read') {
                foreach ($k as $k2 => $v2) {
                    $types['read'][$k2] = $v2['type'];
                    $dsn = $v2['dsn'];
                    $uName = $v2['username'];
                    $pass = $v2['password'];
                    $opts = $v2['options'];
                    $read[$k2] = function () use ($dsn, $uName, $pass, $opts) {
                        return new ExtendedPdo(
                            $dsn,
                            $uName,
                            $pass,
                            $opts
                        );
                    };
                }
            } else if (strtolower($k) == 'write') {
                foreach ($k as $k2 => $v2) {
                    $types['write'][$k2] = $v2['type'];
                    $dsn = $v2['dsn'];
                    $uName = $v2['username'];
                    $pass = $v2['password'];
                    $opts = $v2['options'];
                    $write[$k2] = function () use ($dsn, $uName, $pass, $opts) {
                        return new ExtendedPdo(
                            $dsn,
                            $uName,
                            $pass,
                            $opts
                        );
                    };
                }
            }
        }

        $this->types = $types;

        // configure locator at construction time
        $this->locator = new ConnectionLocator($default, $read, $write);
    }
}