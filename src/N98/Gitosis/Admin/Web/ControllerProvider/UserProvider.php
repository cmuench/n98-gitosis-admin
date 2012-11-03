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

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form;

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
            $sshKeyExistArray = array();
            $users = $app['gitosis_config']->getUsers();
            foreach ($users as $user) {
                if ($app['gitosis_config']->sshKeyExists($user)) {
                    $sshKeyExistArray[] = $user;
                }
            }

            return $app['twig']->render('user.list.twig', array(
                'users' => $users,
                'ssh_key_exists' => $sshKeyExistArray
            ));
        })->bind('user_list');

        /**
         * View
         */
        $controllers->get('/view/{user}', function (Application $app, $user) {

            return $app['twig']->render('user.view.twig', array(
                'user' => $user,
                'groups_of_user' => $app['gitosis_config']->getGroupsByUsername($user),
            ));
        })->bind('user_view');

        /**
         * Create
         */
        $controllers->match('/create', function (Application $app, Request $request) {

            $data = array();

            $builder = $app['form.factory']->createBuilder('form', $data)
                ->add('name', 'text', array(
                    'trim' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Regex(
                            array(
                                'pattern' => '/^[.a-zA-Z0-9-_@]+$/'
                            )
                        )
                    )
                ))
                ->add('ssh_publickey_content', 'text', array(
                    'label' => 'SSH Public Key Content',
                    'trim' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    )
                ));

                /**
                 * Check if user already exists
                 */
                $builder->addEventListener(Form\FormEvents::POST_BIND, function(Form\FormEvent $event) use ($app) {
                $form = $event->getForm();
                if ($app['gitosis_config']->userExists($form['name']->getData())) {
                    $form->addError(new Form\FormError('User already exists'));
                }
            });

            $form = $builder->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $app['gitosis_config']->saveSshKey($data['name'], $data['ssh_publickey_content']);

                    return $app->redirect($app['url_generator']->generate('user_view', array('user' => $data['name'])));
                }
            }

            return $app['twig']->render('user.create.twig', array('form' => $form->createView()));

        })->bind('user_create');

        /**
         * Edit
         */
        $controllers->match('/edit/{user}', function (Application $app, Request $request, $user) {

            $data = array(
                'ssh_publickey_content' => $app['gitosis_config']->getSshKeyContent($user),
            );

            $builder = $app['form.factory']->createBuilder('form', $data)
                ->add('ssh_publickey_content', 'text', array(
                    'label' => 'SSH Public Key Content',
                    'trim' => true,
                    'constraints' => array(
                        new Assert\NotBlank(),
                    )
                ));

            $form = $builder->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $app['gitosis_config']->saveSshKey($user, $data['ssh_publickey_content']);

                    return $app->redirect($app['url_generator']->generate('user_view', array('user' => $user)));
                }
            }

            return $app['twig']->render('user.edit.twig', array('form' => $form->createView(), 'user' => $user));

        })->bind('user_edit');

        /**
         * Delete
         */
        $controllers->match('/delete/{user}', function(Application $app, $user) {

                $app['gitosis_config']->removeUser($user)->save();

                return $app->redirect($app['url_generator']->generate('user_list'));
            })->bind('user_delete');

        return $controllers;
    }
}