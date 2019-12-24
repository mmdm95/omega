<?php

namespace HAuthentication;

interface HIAuthorizator
{
    /**
     * Check if current user's role is allow specific privilege to specific resource
     * For role-based applications
     *
     * @param $resource
     * @param $privilege
     * @param string|int|null $username <p>If this parameter is null this means we poin to current user.</p>
     * @return mixed
     *
     */
    public function isAllow($resource, $privilege, $username = null);
}
