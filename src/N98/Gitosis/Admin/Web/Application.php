<?php

namespace N98\Gitosis\Admin\Web;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\AppConfigProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\GitosisConfigProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\IndexProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\RepositoryProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\GroupProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\UserProvider;

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
        $twigService = new TwigServiceProvider();
        $this->register($twigService, array(
            'twig.path' => __DIR__ . '/views'
        ));

        /**
         * Forms
         */
        $this->register(new FormServiceProvider());

        /**
         * Translations
         */
        $this->register(new TranslationServiceProvider(
            array(
                'locale_fallback' => 'en'
            )
        ));

        /**
         * URL generator
         */
        $this->register(new UrlGeneratorServiceProvider());

        /**
         * Register gitosis config provider
         */
        $this->register(new GitosisConfigProvider());

        /**
         * Register controllers
         */
        $this->mount('/', new IndexProvider());
        $this->mount('/repository', new RepositoryProvider());
        $this->mount('/group', new GroupProvider());
        $this->mount('/user', new UserProvider());
    }
}