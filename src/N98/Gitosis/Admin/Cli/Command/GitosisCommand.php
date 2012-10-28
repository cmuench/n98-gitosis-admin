<?php

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
            if (!is_dir($appConfig['gitosis']['root_directory'])) {
                throw new \RuntimeException('Gitosis root directory does not exist');
            }
            $this->config = new GitosisConfig(rtrim($appConfig['gitosis']['root_directory'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'gitosis.conf');
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