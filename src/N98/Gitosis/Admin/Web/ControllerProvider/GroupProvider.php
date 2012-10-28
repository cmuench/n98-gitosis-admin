<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;

class GroupProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('group.index.twig', array(
                'groups' => $app['gitosis_config']->getGroups()
            ));
        })->bind('groups');

        return $controllers;
    }
}