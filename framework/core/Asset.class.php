<?php
defined('BASE_PATH') OR exit('No direct script access allowed');

class Asset
{
    public function script($path)
    {
        return "<script src='" . asset_url($path) . "' type='text/javascript'></script>";
    }

    public function css($path)
    {
        return "<link href='" . asset_url($path) . "' rel='stylesheet'>";
    }

    public function remoteScript($path)
    {
        return "<script src='" . $path . "' type='text/javascript'></script>";
    }

    public function remoteCss($path)
    {
        return "<link href='" . $path . "' rel='stylesheet'>";
    }
}