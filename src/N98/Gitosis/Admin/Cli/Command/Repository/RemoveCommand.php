<?php

namespace N98\Gitosis\Admin\Cli\Command\Repository;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('repo:remove')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of repository to delete')
             ->setDescription('Removes am existing repository');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getConfig()
             ->removeRepository($input->getArgument('name'))
             ->save();
    }

}