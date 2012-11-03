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

namespace N98\Gitosis\Admin\Cli\Command;

use Symfony\Component\Console\Command\Command;
use N98\Gitosis\Config\Config as GitosisConfig;

abstract class GitosisCommand extends Command
{
    /**
     * @var GitosisConfig
     */
    private $config = null;

    /**
     * @return GitosisConfig
     */
    protected function getConfig()
    {
        if ($this->config === null) {
            $appConfig = $this->getApplication()->getConfig();
            if (!is_dir($appConfig->getGitosisAdminRootDirectory())) {
                throw new \RuntimeException('Gitosis root directory does not exist');
            }
            $this->config = new GitosisConfig(rtrim($appConfig->getGitosisAdminRootDirectory(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'gitosis.conf');
        }

        return $this->config;
    }

    /**
     * @param string $input
     * @return array
     */
    protected function normalizeListInput($input)
    {
        $data = array();
        $array = explode(',', $input);
        foreach ($array as $row) {
            $data[] = trim(str_replace(' ', '', $row));
        }

        return $data;
    }
}