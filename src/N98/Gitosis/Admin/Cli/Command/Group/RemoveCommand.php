<?php

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:remove')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of group to delete')
             ->setDescription('Removes an existing user group');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getConfig()
             ->removeGroup($input->getArgument('name'))
             ->save();
    }

}