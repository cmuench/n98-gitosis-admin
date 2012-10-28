<?php

namespace N98\Gitosis\Admin\Cli\Command\Group;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Text\Table\Table;

class ListCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('group:list')
             ->setDescription('Lists all user groups');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table(
            array(
                'columnWidths' => array(30, 50),
                'decorator' => 'ascii',
            )
        );

        foreach ($this->getConfig()->getGroups() as $group) {
            $table->appendRow(
                array(
                    $group->getName(),
                    implode(PHP_EOL, $group->getMembers())
                )
            );
        }

        $output->writeln($table->render());
    }

}