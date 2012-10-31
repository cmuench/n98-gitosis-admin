<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\ControllerProviderInterface;

class RepositoryProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        /**
         * Index
         */
        $controllers->get('/', function (Application $app) {
            return $app['twig']->render('repository.list.twig', array(
                'repositories' => $app['gitosis_config']->getRepositories()
            ));
        })->bind('repository_list');

        /**
         * Edit repository
         */
        $controllers->match('/edit/{repo}', function(Application $app, Request $request, $repo) {

            $data = array(
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                ->add('name')
                ->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    return $app->redirect($app['url_generator']->generate('repositories'));
                }
            }

            // display the form
            return $app['twig']->render('repository.edit.twig', array('form' => $form->createView()));
        })->bind('repository_edit');

        /**
         * Repository info
         */
        $controllers->match('/info/{repo}', function(Application $app, Request $request, $repo) {

            $data = array(
            );

            // display the form
            return $app['twig']->render(
                'repository.info.twig',
                array(
                    'repository' => $app['gitosis_config']->getRepository($repo),
                    'git'        => $app['gitosis_config']->getGitRepository($repo)
                )
            );
        })->bind('repository_edit');

        return $controllers;
    }
}