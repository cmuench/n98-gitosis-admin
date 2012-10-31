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
     * @return string
     */
    public function getGitosisSshHost()
    {
        return $this->configArray['gitosis']['ssh_host'];
    }

    /**
     * @return string
     */
    public function getGitosisSshUser()
    {
        return $this->configArray['gitosis']['ssh_user'];
    }

    /**
     * @return string
     */
    public function getGitosisAdminRootDirectory()
    {
        return $this->configArray['gitosis']['root_directory'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->configArray;
    }

}