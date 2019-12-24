<?php

namespace HAuthentication;

interface HIAuthenticator
{
    /**
     * Storage type constants
     */
    const cookie = 1;
    const session = 2;

    /**
     * Set user login sessions
     *
     * @param $username
     * @param $password
     * @param bool $remember
     * @param bool $checkAdminRoles
     * @return mixed
     */
    public function login($username, $password, $remember = true, $checkAdminRoles = false);

    /**
     * Update login sessions
     *
     * @param $username
     * @param $password
     * @param bool $remember
     * @return mixed
     *
     */
    public function updateLogin($username, $password, $remember = true);

    /**
     * Do logout
     *
     * @return mixed
     *
     */
    public function logout();

    /**
     * Check if current user is logged in
     *
     * @return bool
     *
     */
    public function isLoggedIn();

    /**
     * Set expiration time for user's login
     *
     * @param $timestamp
     * @return mixed
     *
     */
    public function setExpiration($timestamp);

    /**
     * Get expiration time for user's login
     *
     * @return mixed
     *
     */
    public function getExpiration();

    /**
     * Set storage type to store identities
     *
     * @param int $type
     * @return mixed
     *
     */
    public function setStorageType($type = self::session);

    /**
     * Get storage type
     *
     * @return mixed
     *
     */
    public function getStorageType();

    /**
     * Store user identity to cookie/session
     *
     * @param array $identity
     * @return bool
     */
    public function storeIdentity($identity = []);

    /**
     * Return current user identity
     *
     * @return mixed
     *
     */
    public function getIdentity();

    /**
     * Set passed namespace
     * Useful for multiple authentication
     *
     * @param $namespace
     * @return mixed
     *
     */
    public function setNamespace($namespace);

    /**
     * Get current namespace
     * Useful for multiple authentication
     *
     * @return mixed
     *
     */
    public function getNamespace();
}
