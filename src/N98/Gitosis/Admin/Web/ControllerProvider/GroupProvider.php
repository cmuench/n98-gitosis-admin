<?php

namespace N98\Gitosis\Admin\Web\ControllerProvider;

use N98\Gitosis\Config\Group as GitosisGroup;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

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
         * Add users
         */
        $controllers->match('/{group}/users', function(Application $app, Request $request, $group) {

            /* @var $gitosisGroup GitosisGroup */
            $gitosisGroup = $app['gitosis_config']->getGroup($group);

            $data = array(
                'members' => $gitosisGroup->getMembers(),
            );

            $users = $app['gitosis_config']->getUsers();
            $choices = array_combine($users, $users);
            $builder = $app['form.factory']->createBuilder('form', $data)
                ->add('members', 'choice', array(
                    'expanded' => true,
                    'multiple' => true,
                    'choices'  => $choices,
                )
            );

            $form = $builder->getForm();

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    try {
                        $data = $form->getData();
                        $gitosisGroup->setMembers($data['members']);
                        $app['gitosis_config']->save();

                        $app['session']->set('flash', array(
                            'type'    => 'success',
                            'short'   => 'Saved',
                            'ext'     => 'Group members was saved',
                        ));
                    } catch (\Exception $e) {
                        $app['session']->set('flash', array(
                            'type'    => 'error',
                            'short'   => 'Error',
                            'ext'     => $e->getMessage(),
                        ));
                    }

                    return $app->redirect($app['url_generator']->generate('group_view', array('group' => $group)));
                }
            }

            return $app['twig']->render(
                'group.users.twig',
                array(
                    'form'  => $form->createView(),
                    'group' => $group,
                )
            );

        })->bind('group_users');

        $createGroupEditForm = function($data) use ($app) {
            $builder = $app['form.factory']->createBuilder('form', $data)
                ->add('name', 'text', array(
                    'constraints' => array(
                        new Assert\NotBlank(),
                        new Assert\Regex(array('pattern' => '/^[a-zA-Z0-9-_]+$/')),
                    )
                ));

            /**
             * Check if group already exists
             */
            $builder->addEventListener(Form\FormEvents::POST_BIND, function(Form\FormEvent $event) use ($app) {
                    $form = $event->getForm();
                    try {
                        if ($app['gitosis_config']->getGroup($form['name']->getData())) {
                            $form->addError(new Form\FormError('Group already exists'));
                        }
                    } catch (\Exception $e) {
                        // ok
                    }
                });


            return $builder->getForm();
        };

        /**
         * Create
         */
        $controllers->match('/create', function (Application $app, Request $request) use($createGroupEditForm) {

            $data = array();
            $form = $createGroupEditForm($data);

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    try {
                        $group = new GitosisGroup($data['name']);
                        $app['gitosis_config']->addGroup($group)->save();

                        $app['session']->set('flash', array(
                            'type'    => 'success',
                            'short'   => 'Saved',
                            'ext'     => 'New group was created.',
                        ));
                    } catch (\Exception $e) {
                        $app['session']->set('flash', array(
                            'type'    => 'error',
                            'short'   => 'Could not create group',
                            'ext'     => $e->getMessage(),
                        ));
                    }

                    return $app->redirect($app['url_generator']->generate('group_view', array('group' => $data['name'])));
                }
            }

            return $app['twig']->render('group.create.twig', array('form' => $form->createView()));

        })->bind('group_create');

        /**
         * Edit
         */
        $controllers->match('/edit/{group}', function (Application $app, Request $request, $group) use ($createGroupEditForm) {

            $data = array(
                'name' => $group
            );
            $form = $createGroupEditForm($data);

            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    try {
                        $app['gitosis_config']->getGroup($group)->setName($data['name']);
                        $app['gitosis_config']->save();

                        $app['session']->set('flash', array(
                                'type'    => 'success',
                                'short'   => 'Saved',
                                'ext'     => 'Group was saved.',
                            ));
                    } catch (\Exception $e) {
                        $app['session']->set('flash', array(
                                'type'    => 'error',
                                'short'   => 'Could not save group',
                                'ext'     => $e->getMessage(),
                            ));
                    }

                    return $app->redirect($app['url_generator']->generate('group_view', array('group' => $data['name'])));
                }
            }

            return $app['twig']->render('group.edit.twig', array('form' => $form->createView(), 'group' => $group));

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
