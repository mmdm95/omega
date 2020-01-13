<?php

namespace HAuthentication;

defined('BASE_PATH') OR exit('No direct script access allowed');

use Aura\Sql\Exception;
use http\Cookie;

include_once MODEL_PATH . 'CookieModel.class.php';
spl_autoload_register(function ($class_name) {
    $fileToTest = str_replace('\\', DS, LIB_PATH . $class_name . '.class.php');
    $file = str_replace('\\', DS, LIB_PATH . $class_name . '.php');
    if (file_exists($fileToTest)) {
        include $fileToTest;
    } else if (file_exists($file)) {
        include $file;
    }
});

class Auth extends BasicDB implements HIAuthenticator, HIAuthorizator, HIRole, HIPage, HIPermission
{
    /**
     * Database operation instance
     *
     * @var \Aura\Sql\ExtendedPdoInterface|null
     *
     */
    protected $db = null;

    /**
     * Storage for authentication config
     *
     * @var \stdClass|null
     *
     */
    protected $authData = null;

    /**
     * Storage for user identity
     *
     * @var \stdClass|null
     *
     */
    protected $identity = null;

    /**
     * Storage name
     *
     * @var string
     *
     */
    protected $storageName = 'userIdentity';

    /**
     * Type of storage
     *
     * @var int|null
     *
     */
    protected $storageType = null;

    /**
     * User expiration time
     *
     * @var int|null
     *
     */
    protected $expiration = null;

    /**
     * Name of authentication namespace
     *
     * @var string
     *
     */
    protected $namespace = 'default';

    /**
     * Separator of storage name and namespace for store in cookie
     *
     * @var string
     *
     */
    protected $storageNameAndNamespaceSeparator = '-';

    /**
     * Removable separator of storage name and namespace for store in cookie
     *
     * @var string
     *
     */
    protected $storageNameAndNamespaceSeparatorRestrict = '';

    /**
     * Storage for roles
     *
     * @var array|null
     *
     */
    protected $roles = null;

    /**
     * Storage for pages
     *
     * @var array|null
     *
     */
    protected $pages = null;

    /**
     * Storage for permissions
     *
     * @var array|null
     *
     */
    protected $permissions = null;

    /**
     *
     * Auth constructor.
     *
     * @param bool $setAuthSetting <p>Set this parameter to true just for the first time.</p>
     *
     * @throws HAException
     */
    public function __construct($setAuthSetting = false)
    {
        $this->authData = new \stdClass();
        $this->_parseAuthData($setAuthSetting);
        if ($setAuthSetting) {
            $this->createAuthTables($this->authData->tables, $this->authData->columns);
            $this->setAuthInformationToDB($this->authData);
        }
        $this->db = $this->getDb();
        $this->identity = new \stdClass();
    }

    /**
     * Authenticate user and store identities to cookie/session
     *
     * @param $username
     * @param $password
     * @param bool $remember
     * @param bool $checkAdminRoles
     * @param string $extraWhere
     * @param array $extraParams
     * @return array|Auth
     *
     * @throws HAException
     */
    public function login($username, $password, $remember = false, $checkAdminRoles = false, $extraWhere = '', $extraParams = [])
    {
        if (!$remember) {
            $this->storageType = self::session;
        } else {
            $this->storageType = self::cookie;
        }
        $this->_removeStoredStorageType();
        $this->_storeStorageType();

        $extraWhere = is_string($extraWhere) ? $extraWhere : '';
        $extraWhere = empty($extraWhere) ? '' : ' AND (' . $extraWhere . ')' ;
        $extraParams = is_array($extraParams) ? $extraParams : [];

        $row = $this->getDataFromDB($this->authData->tables->user, '*',
            "{$this->authData->columns->user->username->column}=:username" . $extraWhere,
            array_merge(['username' => $username], $extraParams));

        if (!count($row)) {
            return ['err' => 'نام کاربری یا کلمه عبور اشتباه است.'];
        }
        if (!password_verify($password, $row[0][$this->authData->columns->user->password->column])) {
            return ['err' => 'نام کاربری یا کلمه عبور اشتباه است.'];
        }
        $row = $row[0];

        $roleId = $this->getDataFromDB($this->authData->tables->user_role, '*',
            "{$this->authData->columns->user_role->user_id->column}=:uId",
            ['uId' => $row[$this->authData->columns->user->id->column]]);
        // If we need to check admin roles but we don't have any role
        if ($checkAdminRoles && !count($roleId)) {
            return ['err' => 'نام کاربری یا کلمه عبور اشتباه است.'];
        }
        if (count($roleId)) {
            $roleId = $roleId[0][$this->authData->columns->user_role->role_id->column];
            $row[$this->authData->columns->user_role->role_id->column] = $roleId;

            $role = $this->getDataFromDB($this->authData->tables->role, '*',
                "{$this->authData->columns->role->id->column}=:id",
                ['id' => $roleId]);
            if (count($role)) {
                $role = $role[0];
                $row['role_name'] = $role[$this->authData->columns->role->name->column];
                $row['role_desc'] = $role[$this->authData->columns->role->description->column];
            }

            if ($checkAdminRoles) {
                if (!$this->isInAdminRole($role[$this->authData->columns->role->name->column])) {
                    return ['err' => 'نام کاربری یا کلمه عبور اشتباه است.'];
                }
            }
        }

        $this->identity = (object)$row;
        if (!isset($this->expiration)) {
            throw new HAException('زمان پایان احراز هویت مشخص نشده است.');
        }
        $res = $this->storeIdentity();
        if (!$res) {
            return ['err' => 'نوع ذخیره‌سازی نامشخص است.'];
        }

        return $this;
    }

    /**
     * Update login sessions
     * It's not really necessary! use logout and then login instead
     *
     * @param $username
     * @param $password
     * @param bool $remember
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function updateLogin($username, $password, $remember = true)
    {
        $this->_removeStoredIdentity();
        return $this->login($username, $password, $remember);
    }

    /**
     * Do logout
     *
     * @return bool
     *
     */
    public function logout()
    {
        $this->_removeStoredStorageType();
        return $this->_removeStoredIdentity();
    }

    /**
     * Check if current user is logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        $id = $this->getIdentity();
        if ($id) {
            try {
                $user = $this->_fetchUser($id->id);
                if (count($user)) {
                    return true;
                }
                return false;
            } catch (HAException $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * Set expiration time for user's login
     *
     * @param $timestamp
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function setExpiration($timestamp)
    {
        if (!is_int($timestamp)) {
            throw new HAException('زمان پایان احراز هویت باید از نوع عددی باشد.');
        }

        // Specially load storage type from cookie
        $this->getStorageType();

        $this->expiration = time() + $timestamp;
        return $this;
    }

    /**
     * Get expiration time for user's login
     *
     * @return mixed
     *
     */
    public function getExpiration()
    {
        if (isset($this->expiration)) {
            return $this->expiration;
        }
        return false;
    }

    /**
     * Set storage type to store identities
     *
     * @param int $type
     * @return mixed
     *
     */
    public function setStorageType($type = self::session)
    {
        $this->_removeStoredStorageType();
        $this->storageType = $type;
        $this->_storeStorageType();

        return $this;
    }

    /**
     * Get storage type
     *
     * @return mixed
     *
     */
    public function getStorageType()
    {
        if (!isset($this->storageType)) {
            $storage = $this->_getStoredStorageType();
            if ($storage) {
                $this->storageType = intval($storage);
            }
        }
        return $this->storageType;
    }

    /**
     * Store user identity to cookie/session
     *
     * @param array $identity
     * @return bool
     */
    public function storeIdentity($identity = [])
    {
        if (!empty($identity) && is_array($identity)) {
            $tmp = json_decode(json_encode($this->getIdentity()), true);
            $this->identity = (object)array_merge_recursive_distinct($tmp, $identity);
        }

        switch ($this->storageType) {
            case self::cookie:
                $cookie = new \CookieModel();

                $this->_removeStoredIdentity();

                $res = $cookie->set_cookie($this->storageName . $this->storageNameAndNamespaceSeparator . $this->namespace, json_encode($this->identity), $this->expiration, '/', null, null, true, \CookieModel::COOKIE_ENCRYPT_DECRYPT);
                return $res;
                break;
            case self::session:
                $_SESSION[$this->namespace][$this->storageName]['expiration'] = encryption_decryption(ED_ENCRYPT, $this->expiration);
                $_SESSION[$this->namespace][$this->storageName]['identity'] = encryption_decryption(ED_ENCRYPT, json_encode($this->identity));
                return true;
                break;
        }
        return false;
    }

    /**
     * Return current user identity
     *
     * @return bool|mixed
     *
     */
    public function getIdentity()
    {
        switch ($this->storageType) {
            case self::cookie:
                $cookie = new \CookieModel();
                $res = $cookie->is_cookie_set($this->storageName . $this->storageNameAndNamespaceSeparator . $this->namespace, true, true, \CookieModel::COOKIE_ENCRYPT_DECRYPT);
                if (!$res) {
                    $this->_removeStoredStorageType();
                    return false;
                }
                return json_decode($res);
                break;
            case self::session:
                if (!isset($_SESSION[$this->namespace][$this->storageName]['expiration']) ||
                    !isset($_SESSION[$this->namespace][$this->storageName]['identity'])) {
                    return false;
                }
                $expiration = encryption_decryption(ED_DECRYPT, $_SESSION[$this->namespace][$this->storageName]['expiration']);
                $identity = encryption_decryption(ED_DECRYPT, $_SESSION[$this->namespace][$this->storageName]['identity']);
                if ((time() - $expiration) >= 0) {
                    $this->_removeStoredIdentity();
                    return false;
                }
                return json_decode($identity);
                break;
        }
        return false;
    }

    /**
     * Set passed namespace
     * Useful for multiple authentication
     *
     * @param $namespace
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function setNamespace($namespace)
    {
        if (!is_string($namespace)) {
            throw new HAException('محدوده باید از نوع رشته باشد.');
        }
        $this->namespace = str_replace($this->storageNameAndNamespaceSeparator, $this->storageNameAndNamespaceSeparatorRestrict, $namespace);
        return $this;
    }

    /**
     * Get current namespace
     * Useful for multiple authentication
     *
     * @return mixed
     *
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Allow a user to resource(s) - page(s) - with privilege(s)
     *
     * @param string|int $resource
     * @param string|int $privilege
     * @param string|int|null $username
     * @return array|Auth
     *
     * @throws HAException
     *
     */
    public function allow($resource, $privilege, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username, $resource, $privilege);
        if (isset($result['err'])) {
            return $result;
        }
        list($uId, $resId, $priId) = $result;
        $this->_removeUPaPe($uId, $resId, $priId);
        $this->setDataToDB($this->authData->tables->user_page_perm, [
            $this->authData->columns->user_page_perm->user_id->column => $uId,
            $this->authData->columns->user_page_perm->page_id->column => $resId,
            $this->authData->columns->user_page_perm->perm_id->column => $priId,
            $this->authData->columns->user_page_perm->allow->column => 1,
        ]);

        return $this;
    }

    /**
     * Prevent a user from access resource(s) - page(s) - and restrict to do privilege(s)
     *
     * @param string|int $resource
     * @param string|int $privilege
     * @param string|int|null $username
     * @return array|Auth
     *
     * @throws HAException
     *
     */
    public function deny($resource, $privilege, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username, $resource, $privilege);
        if (isset($result['err'])) {
            return $result;
        }
        list($uId, $resId, $priId) = $result;
        $this->_removeUPaPe($uId, $resId, $priId);
        $this->setDataToDB($this->authData->tables->user_page_perm, [
            $this->authData->columns->user_page_perm->user_id->column => $uId,
            $this->authData->columns->user_page_perm->page_id->column => $resId,
            $this->authData->columns->user_page_perm->perm_id->column => $priId,
            $this->authData->columns->user_page_perm->allow->column => -1,
        ]);

        return $this;
    }

    /**
     * Remove a user_page_perm whether allow or deny from database
     *
     * @param string|int $resource
     * @param string|int $privilege
     * @param string|int|null $username
     * @return array|Auth
     *
     * @throws HAException
     *
     */
    public function removeAllowOrDeny($resource, $privilege, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username, $resource, $privilege);
        if (isset($result['err'])) {
            return $result;
        }
        list($uId, $resId, $priId) = $result;
        $this->_removeUPaPe($uId, $resId, $priId);
        return $this;
    }

    /**
     * Set a role to a/current user
     *
     * @param $role
     * @param null $username
     * @return Auth|array
     *
     * @throws HAException
     *
     */
    public function setRoleToUser($role, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username);
        if (isset($result['err'])) {
            return $result;
        }
        $uId = $result[0];
        $roleId = $this->_fetchRole($role);

        if (!$this->existsDataInDB($this->authData->tables->user_role,
            "{$this->authData->columns->user_role->user_id->column}=:uId AND {$this->authData->columns->user_role->role_id->column}=:roleId", [
                'uId' => $uId,
                'roleId' => $roleId
            ])) {
            $this->setDataToDB($this->authData->tables->user_role, [
                $this->authData->columns->user_role->user_id->column => $uId,
                $this->authData->columns->user_role->role_id->column => $roleId,
            ]);
        }
        return $this;
    }

    /**
     * Remove a user_role from database(Remove a role from a/current user)
     *
     * @param string|int $role
     * @param string|int|null $username
     * @return Auth|array
     *
     * @throws HAException
     *
     */
    public function removeUserRole($role, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username);
        if (isset($result['err'])) {
            return $result;
        }
        $uId = $result[0];
        $roleId = $this->_fetchRole($role);
        $this->_removeUR($uId, $roleId);
        return $this;
    }

    /**
     * Check if current user's role is allow specific privilege to specific resource
     * For role-based applications
     *
     * @param string|int $resource
     * @param string|int $privilege
     * @param string|int|null $username <p>If this parameter is null this means the current logged in user.</p>
     * @return mixed
     *
     * @throws HAException
     *
     */
    public function isAllow($resource, $privilege, $username = null)
    {
        if (is_null($username)) {
            $username = $this->_getCurrentUserID();
        }
        $result = $this->_checkUserParams($username, $resource, $privilege);
        if (isset($result['err'])) {
//            return $result;
            return false;
        }
        list($uId, $resId, $priId) = $result;

        $UPaPe = $this->getDataFromDB($this->authData->tables->user_page_perm, '*',
            "{$this->authData->columns->user_page_perm->user_id->column}=:uId AND {$this->authData->columns->user_page_perm->page_id->column}=:paId AND {$this->authData->columns->user_page_perm->perm_id->column}=:peId", [
                'uId' => $uId,
                'paId' => $resId,
                'peId' => $priId
            ]);
        if (count($UPaPe)) {
            if ($UPaPe[0][$this->authData->columns->user_page_perm->allow->column] == 1) {
                return true;
            } else if ($UPaPe[0][$this->authData->columns->user_page_perm->allow->column] == -1) {
                return false;
            }
        }

        $role = $this->getDataFromDB($this->authData->tables->user_role, '*',
            "{$this->authData->columns->user_role->user_id->column}=:user", [
                'user' => $uId
            ]);
        if (!count($role)) {
//            return ['err' => 'نقشی برای این کاربر در نظر گرفته نشده است.'];
            return false;
        }
        $role = $role[0][$this->authData->columns->user_role->role_id->column];

        $RoPaPe = $this->getDataFromDB($this->authData->tables->role_page_perm, '*',
            "{$this->authData->columns->role_page_perm->role_id->column}=:rId AND {$this->authData->columns->role_page_perm->page_id->column}=:paId AND {$this->authData->columns->role_page_perm->perm_id->column}=:peId", [
                'rId' => $role,
                'paId' => $resId,
                'peId' => $priId
            ]);
        if (count($RoPaPe)) {
            return true;
        }
        return false;
    }

    /**
     * Check for types and fetch data and error handling about user
     *
     * @param string|int $username
     * @param string|int|null $resource
     * @param string|int|null $privilege
     * @return array
     *
     * @throws HAException
     *
     */
    protected function _checkUserParams($username, $resource = null, $privilege = null)
    {
        if ((!is_string($username) && !is_numeric($username)) ||
            (!is_string($resource) && !is_numeric($resource)) ||
            (!is_string($privilege) && !is_numeric($privilege))) {
            throw new HAException('نوع داده‌های وارد شده صحیح نمی‌باشد.');
        }

        $uId = $username;
        $user = $this->_fetchUser($username);
        if (!count($user)) {
            return ['err' => 'کاربر وارد شده وجود ندارد!'];
        }
        if (is_string($username)) {
            $uId = $user[0][$this->authData->columns->user->id->column];
        }

        $resId = $resource;
        if (!is_null($resId)) {
            $res = $this->_fetchPage($resource);
            if (!count($res)) {
                return ['err' => 'صفحه/منبع وارد شده وجود ندارد!'];
            }
            if (is_string($resource)) {
                $resId = $res[0][$this->authData->columns->page->id->column];
            }
        }

        $priId = $privilege;
        if (!is_null($priId)) {
            $pri = $this->_fetchPermission($privilege);
            if (!count($pri)) {
                return ['err' => 'سطح دسترسی وارد شده وجود ندارد!'];
            }
            if (is_string($privilege)) {
                $priId = $pri[0][$this->authData->columns->permission->id->column];
            }
        }

        return [$uId, $resId, $priId];
    }

    /**
     * Remove a user_page_perm item from database if its exists
     *
     * @param $uId
     * @param $resId
     * @param $priId
     *
     */
    protected function _removeUPaPe($uId, $resId, $priId)
    {
        if ($this->existsDataInDB($this->authData->tables->user_page_perm,
            "{$this->authData->columns->user_page_perm->user_id->column}=:uId AND {$this->authData->columns->user_page_perm->page_id->column}=:paId AND {$this->authData->columns->user_page_perm->perm_id->column}=:peId", [
                'uId' => $uId,
                'paId' => $resId,
                'peId' => $priId
            ])
        ) {
            $this->removeDataFromDB($this->authData->tables->user_page_perm,
                "{$this->authData->columns->user_page_perm->user_id->column}=:uId AND {$this->authData->columns->user_page_perm->page_id->column}=:paId AND {$this->authData->columns->user_page_perm->perm_id->column}=:peId", [
                    'uId' => $uId,
                    'paId' => $resId,
                    'peId' => $priId
                ]);
        }
    }

    /**
     * Remove a user_role item from database if its exists
     *
     * @param $uId
     * @param $roleId
     *
     */
    protected function _removeUR($uId, $roleId)
    {
        if ($this->existsDataInDB($this->authData->tables->user_role,
            "{$this->authData->columns->user_role->user_id->column}=:uId AND {$this->authData->columns->user_role->role_id->column}=:roleId", [
                'uId' => $uId,
                'roleId' => $roleId
            ])
        ) {
            $this->removeDataFromDB($this->authData->tables->user_role,
                "{$this->authData->columns->user_role->user_id->column}=:uId AND {$this->authData->columns->user_role->role_id->column}=:roleId", [
                    'uId' => $uId,
                    'paId' => $roleId
                ]);
        }
    }

    /**
     * Get current user id
     *
     * @return bool
     *
     */
    protected function _getCurrentUserID()
    {
        $identity = $this->getIdentity();
        if (!$identity) {
//                throw new HAException('کاربری برای احراز هویت وجود ندارد!');
            return false;
        }
        $idName = $this->authData->columns->user->id->column;
        return $identity->$idName;
    }

    /**
     * Fetch a user from database
     *
     * @param string|int $username
     * @return mixed
     *
     * @throws HAException
     */
    protected function _fetchUser($username)
    {
        if (!is_string($username) && !is_numeric($username)) {
            throw new HAException('نام کاربری باید از نوع رشته یا عدد باشد.');
        }

        if (is_numeric($username)) {
            if (!$this->existsDataInDB($this->authData->tables->user,
                "{$this->authData->columns->user->id->column}=:user", [
                    'user' => $username
                ])
            ) {
                return [];
            }
            return [0 => [$this->authData->columns->user->id->column => $username]];
        } else {
            if (!$this->existsDataInDB($this->authData->tables->user,
                "{$this->authData->columns->user->username->column}=:user", [
                    'user' => $username
                ])
            ) {
                return [];
            }
            return $this->getDataFromDB($this->authData->tables->user, '*',
                "{$this->authData->columns->user->username->column}=:user", [
                    'user' => $username
                ]);
        }
    }

    /**
     * Add role(s) for auth
     *
     * @param array|string $roles
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function addRoles($roles)
    {
        if (!is_array($roles) && !is_string($roles)) {
            throw new HAException('نقش باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $addRoles = [];
        if (is_string($roles)) {
            $addRoles[] = $roles;
        } else {
            $addRoles = $roles;
        }

        foreach ($addRoles as $role) {
            if ($this->hasRole($role)) {
                $this->setDataToDB($this->authData->tables->role, [
                    $this->authData->columns->role->name->column => $role
                ]);
            }
        }
        $this->roles = $this->getRoles();
        return $this;
    }

    /**
     * Remove role(s) from auth
     *
     * @param array|string $roles
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function removeRoles($roles)
    {
        if (!is_array($roles) && !is_string($roles)) {
            throw new HAException('نقش باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $delRoles = [];
        if (is_string($roles)) {
            $delRoles[] = $roles;
        } else {
            $delRoles = $roles;
        }

        foreach ($delRoles as $role) {
            if (!$this->hasRole($role)) {
                $this->removeDataFromDB($this->authData->tables->role,
                    "{$this->authData->columns->role->name->column}=:role",
                    [
                        'role' => $role
                    ]);
            }
        }
        $this->roles = $this->getRoles();
        return $this;
    }

    /**
     * Get all roles
     *
     * @return array
     *
     */
    public function getRoles()
    {
        if (!isset($this->roles)) {
            $this->_fetchRoles();
        }
        return $this->roles;
    }

    /**
     * Get current/loggedIn user's role
     *
     * @return string
     *
     * @throws HAException
     *
     */
    public function getCurrentUserRole()
    {
        $roleId = $this->getCurrentUserRoleID();
        $role = $this->getDataFromDB($this->authData->tables->role, $this->authData->columns->role->name->column,
            "{$this->authData->columns->role->id->column}=:roleId", ['roleId' => $roleId]);
        if (!count($role)) {
            throw new HAException('نقش مورد نظر وجود ندارد!');
        }
        $role = $role[0][$this->authData->columns->role->name->column];

        return $role;
    }

    /**
     * Get current/loggedIn user's role's id
     *
     * @return int
     *
     * @throws HAException
     *
     */
    public function getCurrentUserRoleID()
    {
        $uId = $this->_getCurrentUserID();
        $roleId = $this->getDataFromDB($this->authData->tables->user_role, $this->authData->columns->user_role->role_id->column,
            "{$this->authData->columns->user_role->user_id->column}=:uId", ['uId' => $uId]);
        if (!count($roleId)) {
            throw new HAException('کاربر فاقد نقش است!');
        }
        $roleId = $roleId[0][$this->authData->columns->user_role->role_id->column];

        return $roleId;
    }

    /**
     * Check that entered role is exists
     *
     * @param $role
     * @return bool
     *
     * @throws HAException
     *
     */
    public function hasRole($role)
    {
        if (!is_string($role)) {
            throw new HAException('نقش باید از نوع رشته باشد.');
        }
        $this->roles = $this->getRoles();
        if (array_search($role, array_column($this->roles, $this->authData->columns->role->name->column)) !== false) {
            return true;
        }
        return false;
    }

    /**
     * Check if $role is in admin roles
     * If $role is not set, then check current user
     *
     * @param null|int|string $role
     * @return mixed
     *
     * @throws HAException
     */
    public function isInAdminRole($role = null)
    {
        if (!is_numeric($role) && !is_string($role)) {
            throw new HAException('نقش وارد شده باید از نوع رشته یا عدد باشد.');
        }
        if (empty($role)) {
            $role = $this->getCurrentUserRole();
        } else {
            $role = $this->_fetchRole($role);
        }

        if (in_array($role[0][$this->authData->columns->role->name->column], $this->authData->data->admin_roles)) {
            return true;
        }
        return false;
    }

    /**
     * Fetch all roles from database
     *
     * @return Auth
     *
     */
    protected function _fetchRoles()
    {
        $this->roles = $this->getDataFromDB($this->authData->tables->role);
        return $this;
    }

    /**
     * Fetch a role from database
     *
     * @param string|int $role
     * @return mixed
     *
     * @throws HAException
     */
    protected function _fetchRole($role)
    {
        if (!is_string($role) && !is_numeric($role)) {
            throw new HAException('نقش باید از نوع رشته یا عدد باشد.');
        }
        if (is_numeric($role)) {
            if (!$this->existsDataInDB($this->authData->tables->role,
                "{$this->authData->columns->role->id->column}=:role", [
                    'role' => $role
                ])
            ) {
                return [];
            }
            return [0 => [$this->authData->columns->role->id->column => $role]];
        } else {
            if (!$this->existsDataInDB($this->authData->tables->role,
                "{$this->authData->columns->role->name->column}=:role", [
                    'role' => $role
                ])
            ) {
                return [];
            }
            return $this->getDataFromDB($this->authData->tables->role, '*',
                "{$this->authData->columns->role->name->column}=:role", [
                    'role' => $role
                ]);
        }
    }

    /**
     * Add page(s) to database
     *
     * @param array|string $pages
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function addPages($pages)
    {
        if (!is_array($pages) && !is_string($pages)) {
            throw new HAException('صفحه باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $addPages = [];
        if (is_string($pages)) {
            $addPages[] = $pages;
        } else {
            $addPages = $pages;
        }

        $internalPages = $this->getPages();
        foreach ($addPages as $page) {
            if (array_search($page, array_column($internalPages, $this->authData->columns->page->name->column)) !== false) {
                $this->setDataToDB($this->authData->tables->page, [
                    $this->authData->columns->page->name->column => $page
                ]);
            }
        }
        $this->pages = $this->getPages();
        return $this;
    }

    /**
     * Remove page(s) from database
     *
     * @param array|string $pages
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function removePages($pages)
    {
        if (!is_array($pages) && !is_string($pages)) {
            throw new HAException('صفحه باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $delPages = [];
        if (is_string($pages)) {
            $delPages[] = $pages;
        } else {
            $delPages = $pages;
        }

        $internalPages = $this->getPages();
        foreach ($delPages as $page) {
            if (array_search($page, array_column($internalPages, $this->authData->columns->page->name->column)) === false) {
                $this->removeDataFromDB($this->authData->tables->page,
                    "{$this->authData->columns->page->name->column}=:page",
                    [
                        'page' => $page
                    ]);
            }
        }
        $this->pages = $this->getPages();
        return $this;
    }

    /**
     * Get all pages
     *
     * @return mixed
     *
     */
    public function getPages()
    {
        if (!isset($this->pages)) {
            $this->_fetchPages();
        }
        return $this->pages;
    }

    /**
     * Fetch all pages from database
     *
     * @return Auth
     *
     */
    protected function _fetchPages()
    {
        $this->pages = $this->getDataFromDB($this->authData->tables->page);
        return $this;
    }

    /**
     * Fetch a page from database
     *
     * @param string $page
     * @return mixed
     *
     * @throws HAException
     */
    protected function _fetchPage($page)
    {
        if (!is_string($page) && !is_numeric($page)) {
            throw new HAException('صفحه باید از نوع رشته یا عدد باشد.');
        }
        if (is_numeric($page)) {
            if (!$this->existsDataInDB($this->authData->tables->page,
                "{$this->authData->columns->page->id->column}=:page", [
                    'page' => $page
                ])
            ) {
                return [];
            }
            return [0 => [$this->authData->columns->page->id->column => $page]];
        } else {
            if (!$this->existsDataInDB($this->authData->tables->page,
                "{$this->authData->columns->page->name->column}=:page", [
                    'page' => $page
                ])
            ) {
                return [];
            }
            return $this->getDataFromDB($this->authData->tables->page, '*',
                "{$this->authData->columns->page->name->column}=:page", [
                    'page' => $page
                ]);
        }
    }

    /**
     * Add permission(s) to database
     *
     * @param array|string $permissions
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function addPermissions($permissions)
    {

        if (!is_array($permissions) && !is_string($permissions)) {
            throw new HAException('نوع دسترسی باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $addPerms = [];
        if (is_string($permissions)) {
            $addPerms[] = $permissions;
        } else {
            $addPerms = $permissions;
        }

        $internalPerms = $this->getPermissions();
        foreach ($addPerms as $perm) {
            if (array_search($perm, array_column($internalPerms, $this->authData->columns->role->name->column)) !== false) {
                $this->setDataToDB($this->authData->tables->permission, [
                    $this->authData->columns->permission->description->column => $perm
                ]);
            }
        }
        $this->permissions = $this->getPermissions();
        return $this;
    }

    /**
     * Remove permission(s) from database
     *
     * @param array|string $permissions
     * @return Auth
     *
     * @throws HAException
     *
     */
    public function removePermissions($permissions)
    {
        if (!is_array($permissions) && !is_string($permissions)) {
            throw new HAException('نوع دسترسی باید از نوع رشته یا آرایه‌ای از رشته‌ها باشد.');
        }
        $delPerms = [];
        if (is_string($permissions)) {
            $delPerms[] = $permissions;
        } else {
            $delPerms = $permissions;
        }

        $internalPerms = $this->getPermissions();
        foreach ($delPerms as $perm) {
            if (array_search($perm, array_column($internalPerms, $this->authData->columns->role->name->column)) === false) {
                $this->removeDataFromDB($this->authData->tables->permission,
                    "{$this->authData->columns->permission->description->column}=:perm",
                    [
                        'perm' => $perm
                    ]);
            }
        }
        $this->permissions = $this->getPermissions();
        return $this;
    }

    /**
     * Get all permissions
     *
     * @return array
     *
     */
    public function getPermissions()
    {
        if (!isset($this->permissions)) {
            $this->_fetchPermissions();
        }
        return $this->permissions;
    }

    /**
     * Fetch all permissions from database
     *
     * @return Auth
     *
     */
    protected function _fetchPermissions()
    {
        $this->permissions = $this->getDataFromDB($this->authData->tables->permission);
        return $this;
    }

    /**
     * Fetch a permission from database
     *
     * @param string|int $permission
     * @return mixed
     *
     * @throws HAException
     *
     */
    protected function _fetchPermission($permission)
    {
        if (!is_string($permission) && !is_numeric($permission)) {
            throw new HAException('سطح دسترسی باید از نوع رشته یا عدد باشد.');
        }

        if (is_numeric($permission)) {
            if (!$this->existsDataInDB($this->authData->tables->permission,
                "{$this->authData->columns->permission->id->column}=:perm", [
                    'perm' => $permission
                ])
            ) {
                return [];
            }
            return [0 => [$this->authData->columns->permission->id->column => $permission]];
        } else {
            if (!$this->existsDataInDB($this->authData->tables->permission,
                "{$this->authData->columns->permission->description->column}=:perm", [
                    'perm' => $permission
                ])
            ) {
                return [];
            }
            return $this->getDataFromDB($this->authData->tables->permission, '*',
                "{$this->authData->columns->permission->description->column}=:perm", [
                    'perm' => $permission
                ]);
        }
    }

    /**
     * Remove(delete) stored identity from storage (cookie/session)
     *
     * @return bool
     *
     */
    protected function _removeStoredIdentity()
    {
        switch ($this->storageType) {
            case self::cookie:
                $cookie = new \CookieModel();
                return $cookie->set_cookie($this->storageName . $this->storageNameAndNamespaceSeparator . $this->namespace, '', time() - 3600);
                break;
            case self::session:
                unset($_SESSION[$this->namespace][$this->storageName]['expiration']);
                unset($_SESSION[$this->namespace][$this->storageName]['identity']);
                return true;
                break;
        }
        return false;
    }

    /**
     * Store storage type for future usage in cookie
     *
     * @return bool
     *
     */
    protected function _storeStorageType()
    {
        $cookie = new \CookieModel();
        $res = $cookie->set_cookie('storageType' . $this->storageNameAndNamespaceSeparator . $this->namespace, $this->storageType, $this->expiration, '/', null, null, true, \CookieModel::COOKIE_ENCRYPT_DECRYPT);
        return $res;
    }

    /**
     * Get stored storage type from cookie
     *
     * @return bool|string
     *
     */
    protected function _getStoredStorageType()
    {
        $cookie = new \CookieModel();
        $res = $cookie->is_cookie_set('storageType' . $this->storageNameAndNamespaceSeparator . $this->namespace, true, true, \CookieModel::COOKIE_ENCRYPT_DECRYPT);
        if (!$res) {
            return false;
        }
        return intval($res);
    }

    /**
     * Remove stored storage type's cookie
     *
     * @return bool
     *
     */
    protected function _removeStoredStorageType()
    {
        $cookie = new \CookieModel();
        $res = $cookie->set_cookie('storageType' . $this->storageNameAndNamespaceSeparator . $this->namespace, '', time() - 3600, '/', null, null, true, \CookieModel::COOKIE_ENCRYPT_DECRYPT);
        return $res;
    }

    /**
     * Parse authentication configuration
     *
     * @param bool $checkIfValid
     *
     * @throws HAException
     *
     */
    protected function _parseAuthData($checkIfValid)
    {
        global $HConfig;
        $this->authData->data = json_decode($HConfig['h_config']['AUTH_DATA']);
        $this->authData->tables = (object)$this->authData->data->tables;
        $this->authData->columns = (object)$this->authData->data->columns;
        if ($checkIfValid) {
            if (!isset($this->authData->tables)) {
                throw new HAException('تنظیم جداول احراز هویت به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->user)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'user' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->role)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'role' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->permission)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'permission' . 'ابه درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->page)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'page' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->user_role)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'user_role' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->role_page_perm)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'role_page_perm' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->tables->user_page_perm)) {
                throw new HAException('تنظیم جدول احراز هویت ' . 'user_page_perm' . 'به درستی انجام نشده است!');
            }
            if (!isset($this->authData->columns)) {
                throw new HAException('تنظیم ستون‌های جداول احراز هویت به درستی انجام نشده است!');
            }
            foreach ($this->authData->tables as $table => $info) {
                if (!key_exists($table, $this->authData->columns)) {
                    throw new HAException('تنظیم ستون برای جدول ' . $table . ' صورت نگرفته است!');
                }
            }
        }
    }
}
