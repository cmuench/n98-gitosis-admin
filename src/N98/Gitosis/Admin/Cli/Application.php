<?php

namespace N98\Gitosis\Admin\Cli;

use Symfony\Component\Console\Application as BaseApplication;
use N98\Gitosis\Admin\ConfigurationLoader;

class Application extends BaseApplication
{
    /**
     * @var string
     */
    const APP_NAME = 'n98-gitosis-admin';

    /**
     * @var string
     */
    const APP_VERSION = '1.0.0';

    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
        $configLoader = new ConfigurationLoader();
        $this->config = $configLoader->toArray();

        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\AddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\ListCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\AddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\ListCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\UserAddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\UserRemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RepoAddWritableCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RepoAddReadonlyCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Gitosis\PersistCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\User\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\User\ListCommand());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}