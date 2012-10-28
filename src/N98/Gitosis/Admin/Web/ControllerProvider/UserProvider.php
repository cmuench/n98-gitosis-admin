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

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('user.index.twig', array(
                'users' => $app['gitosis_config']->getUsers()
            ));
        })->bind('users');

        return $controllers;
    }
}