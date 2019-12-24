<?php

namespace HAuthentication;

interface HIPermission
{
    /**
     * Add permission(s) to database
     *
     * @param $permissions
     * @return mixed
     *
     */
    public function addPermissions($permissions);

    /**
     * Remove permission(s) from database
     *
     * @param $permissions
     * @return mixed
     *
     */
    public function removePermissions($permissions);

    /**
     * Get all permissions
     *
     * @return mixed
     *
     */
    public function getPermissions();
}
