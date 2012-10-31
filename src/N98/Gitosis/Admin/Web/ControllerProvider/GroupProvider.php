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

        /**
         * List
         */
        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('group.list.twig', array(
                'groups' => $app['gitosis_config']->getGroups()
            ));
        })->bind('group_list');

        /**
         * View
         */
        $controllers->get('/view/{group}', function (Application $app, $group) {
            return $app['twig']->render('group.view.twig', array(
                'group' => $app['gitosis_config']->getGroup($group)
            ));
        })->bind('group_view');

        return $controllers;
    }
}