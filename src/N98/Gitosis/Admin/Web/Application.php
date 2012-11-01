<?php

namespace N98\Gitosis\Admin\Web;

use Symfony\Component\Translation\Loader\XliffFileLoader as TranslationLoader;
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
         * Register twig
         */
        $this->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/views'
        ));

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
        $this->register(new TranslationServiceProvider(
            array(
                'translator.messages' => array(),
                'locale' => $this['config']->getLocale(),
                'locale_fallback' => 'en',
                'loader' => new TranslationLoader()
            )
        ));

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