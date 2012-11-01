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

        /**
         * Create
         */
        $controllers->get('/create', function (Application $app) {
        })->bind('group_create');

        /**
         * Edit
         */
        $controllers->get('/edit/{group}', function (Application $app, $group) {
        })->bind('group_edit');

        /**
         * Delete
         */
        $controllers->match('/delete/{group}', function(Application $app, $group) {

            $app['gitosis_config']->removeGroup($group)->save();

            return $app->redirect($app['url_generator']->generate('group_list'));
        })->bind('group_delete');

        return $controllers;
    }
}