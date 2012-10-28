<?php

namespace N98\Gitosis\Admin\Cli\Command\User;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Text\Table\Table;

class ListCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('user:list')
             ->setDescription('Lists all registered users (across all groups)');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table(
            array(
                'columnWidths' => array(60),
                'decorator' => 'ascii',
            )
        );

        $users = array();
        foreach ($this->getConfig()->getGroups() as $group) {
            $users = array_merge($users, $group->getMembers());
        }
        $users = array_unique($users);
        sort($users);

        foreach ($users as $user) {
            $table->appendRow(array($user));
        }

        $output->writeln($table->render());
    }

}