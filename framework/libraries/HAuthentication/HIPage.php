<?php

namespace HAuthentication;

interface HIPage
{
    /**
     * Add page(s) to database
     *
     * @param $pages
     * @return mixed
     */
    public function addPages($pages);

    /**
     * Remove page(s) from database
     *
     * @param $pages
     * @return mixed
     */
    public function removePages($pages);

    /**
     * Get all pages
     *
     * @return mixed
     *
     */
    public function getPages();
}
