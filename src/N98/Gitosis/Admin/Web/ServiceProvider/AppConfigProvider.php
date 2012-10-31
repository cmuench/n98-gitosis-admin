<?php

namespace N98\Gitosis\Admin\Web\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;
use N98\Gitosis\Admin\ConfigurationLoader;

class AppConfigProvider implements ServiceProviderInterface
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
        $configLoader = new ConfigurationLoader();
        $app['config'] = $configLoader;
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