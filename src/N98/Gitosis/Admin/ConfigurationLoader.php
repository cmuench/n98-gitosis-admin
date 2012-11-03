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

namespace N98\Gitosis\Admin;

use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    /**
     * @var array
     */
    protected $configArray;

    public function __construct()
    {
        $globalConfig = Yaml::parse(__DIR__ . '/../../../../config.yaml');
        $this->configArray = $globalConfig;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->configArray['locale'];
    }

    /**
     * @return string
     */
    public function getGitosisSshHost()
    {
        return $this->configArray['gitosis']['ssh_host'];
    }

    /**
     * @return string
     */
    public function getGitosisSshUser()
    {
        return $this->configArray['gitosis']['ssh_user'];
    }

    /**
     * @return string
     */
    public function getGitosisAdminRootDirectory()
    {
        return $this->configArray['gitosis']['root_directory'];
    }

    /**
     * @return bool
     */
    public function isSecurityAuthentificationEnabled()
    {
        return $this->configArray['security']['authentification']['enabled'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->configArray;
    }

}