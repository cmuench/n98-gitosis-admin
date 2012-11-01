<?php

namespace N98\Gitosis\Admin\Web\ServiceProvider;

use Silex\ServiceProviderInterface;
use Silex\Application;

class FlashMessageProvider implements ServiceProviderInterface
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
        // TODO: Implement register() method.
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
        /**
         * Before Filters
         */
        $app->before(
            function () use ($app) {
                $flash = $app['session']->get('flash');
                $app['session']->set('flash', null);

                if (!empty($flash)) {
                    $app['twig']->addGlobal('flash', $flash);
                }
            }
        );
    }

}