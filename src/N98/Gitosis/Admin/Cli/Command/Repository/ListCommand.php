<?php

namespace N98\Gitosis\Admin\Cli\Command\Repository;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('repo:list')
             ->setDescription('Lists all repositories');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getConfig()->getRepositories() as $repo) {
            $output->writeln($repo->getName());
        }
    }

}