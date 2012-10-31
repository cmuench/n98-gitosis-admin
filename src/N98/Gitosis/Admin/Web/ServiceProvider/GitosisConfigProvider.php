<?php

namespace N98\Gitosis\Admin\Web\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use N98\Gitosis\Config\Config as GitosisConfig;

class GitosisConfigProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        if (!is_dir($app['config']->getGitosisAdminRootDirectory())) {
            throw new \RuntimeException('Gitosis root directory does not exist');
        }

        $app['gitosis_config'] = new GitosisConfig(rtrim($app['config']->getGitosisAdminRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'gitosis.conf');
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registers
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {

    }


}