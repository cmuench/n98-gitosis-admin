<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class GitosisProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        /**
         * Persist
         */
        $controllers->get('/persist', function (Application $app) {
            $app['gitosis_config']->persist();

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_persist');

        /**
         * Revert
         */
        $controllers->get('/revert', function (Application $app) {
            $app['gitosis_config']->revert();

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_revert');


        return $controllers;
    }
}