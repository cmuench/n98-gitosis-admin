<?php

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use N98\Gitosis\Config\Group as ConfigGroup;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:add')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of new repository')
             ->addArgument('members', InputArgument::REQUIRED, 'Members of the group (comma seperated)')
             ->addArgument('writable', InputArgument::OPTIONAL, 'List of writeable repos (comma seperated)', '')
             ->addArgument('readonly', InputArgument::OPTIONAL, 'List of writeable repos (comma seperated)', '')
             ->setDescription('Add a new user group');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $group = new ConfigGroup($input->getArgument('name'));
        $group->setMembers($this->normalizeListInput($input->getArgument('members')))
              ->setWritable($this->normalizeListInput($input->getArgument('writable')))
              ->setReadonly($this->normalizeListInput($input->getArgument('readonly')));
        $this->getConfig()
             ->addGroup($group)
             ->save();
    }

}