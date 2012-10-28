<?php

namespace N98\Gitosis\Config;

interface ElementInterface
{
    /**
     * @param string $name
     * @param array $data
     */
    public function __construct($name, array $data = array());

    /**
     * @return array
     */
    public function toArray();
}