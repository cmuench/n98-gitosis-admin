<?php

namespace N98\Gitosis\Admin\Web;

use Silex\Application as SilexApplication;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use N98\Gitosis\Admin\Web\ServiceProvider\GitosisConfigProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\IndexProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\RepositoryProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\GroupProvider;
use N98\Gitosis\Admin\Web\ControllerProvider\UserProvider;

class Application extends SilexApplication
{
    /**
     * @var array
     */
    protected $config;

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
        /**
         * Register twig
         */
        $twigService = new TwigServiceProvider();
        $this->register($twigService, array(
            'twig.path' => __DIR__ . '/views'
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
        $this->mount('/groups', new GroupProvider());
        $this->mount('/users', new UserProvider());
    }
}