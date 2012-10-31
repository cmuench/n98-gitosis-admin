<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class UserProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        /**
         * List
         */
        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('user.list.twig', array(
                'users' => $app['gitosis_config']->getUsers()
            ));
        })->bind('user_list');

        /**
         * View
         */
        $controllers->get('/view/{user}', function (Application $app, $user) {
            return $app['twig']->render('user.view.twig', array(
                'user' => $user
            ));
        })->bind('user_view');

        return $controllers;
    }
}