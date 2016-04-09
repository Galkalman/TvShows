<?php

require_once('Constants.php');

class Route
{
    private $_uri = array();

    public function __construct()
    {
        $this->_uri = explode('/', URI_LIST);
    }

    public function submit()
    {
        $uriGetParam = isset($_GET['uri']) ? $_GET['uri'] : '/';

        foreach ($this->_uri as $key => $value)
        {
            if (preg_match("#^$value$#", $uriGetParam))
            {
                return $value;
            }
        }
        return $uriGetParam;
    }
}