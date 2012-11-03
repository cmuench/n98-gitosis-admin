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

namespace N98\Gitosis\Admin\Web;

use Symfony\Component\Translation\Loader\YamlFileLoader as TranslationLoader;
use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\AppConfigProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\GitosisConfigProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\FlashMessageProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\IndexProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\RepositoryProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\GroupProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\UserProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\GitosisProvider;

class Application extends SilexApplication
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    /**
     * Init Silex Application.
     *
     * Registers controller providers and twig engine.
     */
    protected function init()
    {
        $this['debug'] = true;

        /**
         * Register app config provider
         */
        $this->register(new AppConfigProvider());

        /**
         * Sessions
         */
        $this->register(new SessionServiceProvider());

        /**
         * Forms
         */
        $this->register(new FormServiceProvider());

        /**
         * Translations
         */
        $this['locale'] = $this['config']->getLocale();
        $this->register(new TranslationServiceProvider(
            array(
                'locale_fallback' => 'en',
            )
        ));
        $this['translator'] = $this->share($this->extend('translator', function($translator) {
            $translator->addLoader('yaml', new TranslationLoader());

            $translator->addResource('yaml', __DIR__ . '/locales/de.yaml', 'de');

            return $translator;
        }));

        /**
         * Security
         */
        $this->register(new SecurityServiceProvider());
        if ($this['config']->isSecurityAuthentificationEnabled()) {
            $this['security.firewalls'] = array(
                'login' => array(
                    'pattern' => '^/login$',
                ),
                'secured' => array(
                    'pattern' => '^.*$',
                    'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
                    'logout' => array('logout_path' => '/logout'),
                    'users' => array(
                        'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
                    )
                )
            );
        } else {
            // No auth
            $this['security.firewalls'] = array(
                'unsecured' => array(
                    'anonymous' => true,
                ),
            );
        }

        /**
         * Register twig template engine
         */
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/views'
        ));

        /**
         * Validation
         */
        $this->register(new ValidatorServiceProvider());

        /**
         * URL generator
         */
        $this->register(new UrlGeneratorServiceProvider());

        /**
         * Register gitosis config provider
         */
        $this->register(new GitosisConfigProvider());

        /**
         * Flash messages
         */
        $this->register(new FlashMessageProvider());


        /**
         * Register controllers
         */
        $this->mount('/', new IndexProvider());
        $this->mount('/repository', new RepositoryProvider());
        $this->mount('/group', new GroupProvider());
        $this->mount('/user', new UserProvider());
        $this->mount('/gitosis', new GitosisProvider());
    }
}