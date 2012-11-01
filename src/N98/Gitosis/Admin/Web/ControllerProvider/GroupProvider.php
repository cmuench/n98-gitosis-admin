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
        $controllers->get('/{group}/add-user', function(Application $app, $group) {

        })->bind('group_add_user');

        /**
         * Create
         */
        $controllers->match('/create', function (Application $app, Request $request) {

            $data = array();

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


            $form = $builder->getForm();

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
