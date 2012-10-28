<?php

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class RepoAddReadonlyCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:repo:add:readonly')
            ->addArgument('group', InputArgument::REQUIRED, 'Name of group')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repository')
            ->setDescription('Allow group readonly access to repository');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getConfig()
             ->getGroup($input->getArgument('group'))
             ->addReadonly($input->getArgument('repo'));
        $this->getConfig()->save();

        $output->writeln('<info>Added user <comment>' . $input->getArgument('username') . '</comment> to group <comment>' . $input->getArgument('group') . '</comment></info>');
    }

}