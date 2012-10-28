<?php

namespace N98\Gitosis\Admin\Cli\Command\User;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('user:remove')
             ->addArgument('username', InputArgument::REQUIRED, 'Username')
             ->setDescription('Removes user completly. Removed access from all groups!');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getConfig()->getGroups() as $group) {
            $group->removeUser($input->getArgument('username'));
        }
        $this->getConfig()->save();
    }

}