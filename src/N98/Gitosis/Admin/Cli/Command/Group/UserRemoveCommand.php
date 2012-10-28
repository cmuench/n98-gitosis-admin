<?php

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class UserRemoveCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:user:remove')
             ->addArgument('group', InputArgument::REQUIRED, 'Name of group')
             ->addArgument('username', InputArgument::REQUIRED, 'Username')
             ->setDescription('Removes a user from a existing group');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getConfig()
             ->getGroup($input->getArgument('group'))
             ->removeUser($input->getArgument('username'));
        $this->getConfig()->save();

        $output->writeln('<info>Removed user <comment>' . $input->getArgument('username') . '</comment> from group <comment>' . $input->getArgument('group') . '</comment></info>');
    }

}