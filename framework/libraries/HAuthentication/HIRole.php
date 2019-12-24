<?php

namespace HAuthentication;

interface HIRole
{
    /**
     * Add role(s) for auth
     *
     * @param array|string $roles
     * @return array|string
     *
     */
    public function addRoles($roles);

    /**
     * Remove role(s) from auth
     *
     * @param $roles
     * @return array|string
     *
     */
    public function removeRoles($roles);

    /**
     * Get all roles
     *
     * @return array
     *
     */
    public function getRoles();

    /**
     * Get current/loggedIn user's role
     *
     * @return string
     *
     */
    public function getCurrentUserRole();

    /**
     * Check that entered role is exists
     *
     * @param $role
     * @return bool
     *
     */
    public function hasRole($role);

    /**
     * Check if $role is in admin roles
     * If $role is not set, then check current user
     *
     * @param null|int|string $role
     * @return mixed
     *
     */
    public function isInAdminRole($role = null);
}