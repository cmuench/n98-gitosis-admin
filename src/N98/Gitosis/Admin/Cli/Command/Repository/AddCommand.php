<?php

namespace N98\Gitosis\Admin\Cli\Command\Repository;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use N98\Gitosis\Config\Repository as ConfigRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class AddCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('repo:add')
             ->addArgument('name', InputArgument::REQUIRED, 'Name of new repository')
             ->addArgument('owner', InputArgument::OPTIONAL, 'Owner'. 'Admin')
             ->addArgument('description', InputArgument::OPTIONAL, 'No Description')
             ->addArgument('gitweb', InputArgument::OPTIONAL, 'no')
             ->addArgument('daemon', InputArgument::OPTIONAL, 'no')
             ->setDescription('Adds a new repository');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = new ConfigRepository($input->getArgument('name'));
        $repo->setOwner($input->getArgument('owner'))
             ->setDescription($input->getArgument('description'))
             ->setGitweb($input->getArgument('gitweb') == 'yes')
             ->setDaemon($input->getArgument('daemon') == 'yes');
        $this->getConfig()
             ->addRepository($repo)
             ->save();
    }

}