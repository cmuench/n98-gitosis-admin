<?php
/**
 * Copyright (c) 2012 Chistian Münch
 *
 * https://github.com/cmuench/n98-gitosis-admin
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE
 *
 * @author Christian Münch <christian@muench-worms.de>
 */

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use N98\Gitosis\Config\Repository as GitosisRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
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
                'repositories' => $app['gitosis_config']->getRepositories(),
                'filters'      => $app['config']->getRepositoryListFilters(),
            ));
        })->bind('repository_list');

        $controllers->match('/create', function(Application $app, Request $request) {

            $data = array();

            $form = $app['form.factory']->createBuilder('form', $data)
                ->add('name', 'text', array(
                    'trim' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Regex(array('pattern' => '/^[a-zA-Z0-9-_]+$/')),
                    )
                ))
                ->add('owner', 'text', array(
                    'trim' => true,
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))
                ->add('description', 'text', array(
                    'trim' => true,
                    'constraints' => array(
                    )
                ))
                ->add('gitweb', 'checkbox', array(
                    'required' => false
                ))
                ->add('daemon', 'checkbox', array(
                    'required' => false
                ))
                ->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    try {
                        $repository = new GitosisRepository($data['name']);
                        $repository->setOwner($data['owner']);
                        $repository->setDescription($data['description']);
                        $repository->setDaemon($data['daemon']);
                        $repository->setGitweb($data['gitweb']);
                        $app['gitosis_config']->addRepository($repository)->save();

                        $app['session']->set('flash', array(
                            'type'    => 'success',
                            'short'   => 'Saved',
                            'ext'     => 'New repository was created.',
                        ));
                    } catch (\Exception $e) {
                        $app['session']->set('flash', array(
                            'type'    => 'error',
                            'short'   => 'Could not save repository',
                            'ext'     => $e->getMessage(),
                        ));
                    }

                    return $app->redirect($app['url_generator']->generate('repository_list'));
                }
            }

            return $app['twig']->render('repository.create.twig', array('form' => $form->createView()));

        })->bind('repository_create');

        /**
         * Edit
         */
        $controllers->match('/edit/{repo}', function(Application $app, Request $request, $repo) {

            $repository = $app['gitosis_config']->getRepository($repo);
            $data = array(
                'owner'       => $repository->getOwner(),
                'description' => $repository->getDescription(),
                'daemon'      => $repository->getDaemon(),
                'gitweb'      => $repository->getGitweb(),
            );

            $form = $app['form.factory']->createBuilder('form', $data)
                ->add('owner', 'text', array(
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))
                ->add('description', 'text', array(
                    'trim' => true,
                    'constraints' => array(
                    )
                ))
                ->add('gitweb', 'checkbox', array(
                    'required' => false
                ))
                ->add('daemon', 'checkbox', array(
                    'required' => false
                ))
                ->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $repository->setOwner($data['owner']);
                    $repository->setDaemon($data['daemon']);
                    $repository->setGitweb($data['gitweb']);
                    $repository->setDescription($data['description']);
                    $app['gitosis_config']->save();

                    return $app->redirect($app['url_generator']->generate('repository_list'));
                }
            }

            // display the form
            return $app['twig']->render('repository.edit.twig', array('form' => $form->createView(), 'repo' => $repo));

        })->bind('repository_edit');

        /**
         * View
         */
        $controllers->match('/view/{repo}', function(Application $app, $repo) {
            // display the form
            return $app['twig']->render(
                'repository.view.twig',
                array(
                    'repository'      => $app['gitosis_config']->getRepository($repo),
                    'git'             => $app['gitosis_config']->getGitRepository($repo),
                    'groups_write'    => $app['gitosis_config']->getWritableGroupsByRepository($repo),
                    'groups_readonly' => $app['gitosis_config']->getReadonlyGroupsByRepository($repo),
                    'users_write'     => $app['gitosis_config']->getWritableUsersByRepository($repo),
                    'users_readonly'  => $app['gitosis_config']->getReadonlyUsersByRepository($repo),
                )
            );
        })->bind('repository_view');

        /**
         * Delete
         */
        $controllers->match('/delete/{repo}', function(Application $app, $repo) {

            $app['gitosis_config']->removeRepository($repo)->save();

            return $app->redirect($app['url_generator']->generate('repository_list'));
        })->bind('repository_delete');

        return $controllers;
    }
}