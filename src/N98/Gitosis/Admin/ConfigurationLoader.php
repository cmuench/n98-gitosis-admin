<?php

namespace N98\Gitosis\Admin;

use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    /**
     * @var array
     */
    protected $configArray;

    public function __construct()
    {
        $globalConfig = Yaml::parse(__DIR__ . '/../../../../config.yaml');
        $this->configArray = $globalConfig;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->configArray;
    }
}