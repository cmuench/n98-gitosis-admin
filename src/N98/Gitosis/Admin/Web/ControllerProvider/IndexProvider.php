<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        /**
         * Homepage
         */
        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('index.twig', array(
            ));
        })->bind('homepage');

        /**
         * Login
         */
        $controllers->get('/login', function(Request $request) use ($app) {
            return $app['twig']->render('login.twig', array(
                    'error'         => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                ));
        });

        /**
         * Check
         */
        $controllers->post('/login_check', function(Request $request) use ($app) {
            /*var_dump($request->get('_username'));
            var_dump($request->get('_password'));*/
            return $app->redirect($app['url_generator']->generate('homepage'));
        });

        return $controllers;
    }
}