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

namespace N98\Gitosis\Admin\Cli;

use Symfony\Component\Console\Application as BaseApplication;
use N98\Gitosis\Admin\ConfigurationLoader;

class Application extends BaseApplication
{
    /**
     * @var string
     */
    const APP_NAME = 'n98-gitosis-admin';

    /**
     * @var string
     */
    const APP_VERSION = '1.0.0';

    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);

        $this->config = new ConfigurationLoader(N98_GITOSIS_ADMIN_CONFIG_FILE);

        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\AddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\ListCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Repository\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\AddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\ListCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\UserAddCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\UserRemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RepoAddWritableCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Group\RepoAddReadonlyCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\Gitosis\PersistCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\User\RemoveCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\User\ListCommand());
        $this->add(new \N98\Gitosis\Admin\Cli\Command\User\EncodePasswordCommand());
    }

    /**
     * @return ConfigurationLoader
     */
    public function getConfig()
    {
        return $this->config;
    }
}