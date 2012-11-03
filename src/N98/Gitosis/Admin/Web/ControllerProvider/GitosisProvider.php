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

class GitosisProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        /**
         * Persist
         */
        $controllers->get('/persist', function (Application $app) {

                try {
                    $app['gitosis_config']->persist();

                    $app['session']->set('flash', array(
                        'type'    => 'success',
                        'short'   => 'Published',
                        'ext'     => 'New gitosis config is now activated',
                    ));
                } catch (\Exception $e) {
                    $app['session']->set('flash', array(
                        'type'    => 'error',
                        'short'   => 'Error',
                        'ext'     => $e->getMessage(),
                    ));
                }

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_persist');

        /**
         * Revert
         */
        $controllers->get('/revert', function (Application $app) {

            $app['gitosis_config']->revert();

            $app['session']->set('flash', array(
                'type'    => 'info',
                'short'   => 'Feature currently not implemented.',
                'ext'     => 'Feature currently not implemented.',
            ));

            return $app->redirect($app['url_generator']->generate('homepage'));
        })->bind('gitosis_revert');


        return $controllers;
    }
}