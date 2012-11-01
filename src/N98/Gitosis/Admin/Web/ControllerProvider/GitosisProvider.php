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

                try {
                    $app['gitosis_config']->persist();

                    $app['session']->set('flash', array(
                        'type'    => 'success',
                        'short'   => 'Published',
                        'ext'     => 'New gitosis config is now activated',
                    ));
                } catch (\Exception $e) {
                    $app['session']->set('flash', array(
                        'type'    => 'error',
                        'short'   => 'Error',
                        'ext'     => $e->getMessage(),
                    ));
                }

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_persist');

        /**
         * Revert
         */
        $controllers->get('/revert', function (Application $app) {

            $app['gitosis_config']->revert();

            $app['session']->set('flash', array(
                'type'    => 'info',
                'short'   => 'Feature currently not implemented.',
                'ext'     => 'Feature currently not implemented.',
            ));

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_revert');


        return $controllers;
    }
}