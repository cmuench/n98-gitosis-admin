<?php

namespace N98\Gitosis\Admin\Cli\Command\Repository;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Text\Table\Table;

class ListCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('repo:list')
             ->setDescription('Lists all repositories');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table(
            array(
                'columnWidths' => array(30, 20, 30),
                'decorator' => 'ascii',
            )
        );

        foreach ($this->getConfig()->getRepositories() as $repo) {
            $table->appendRow(
                array(
                    $repo->getName(),
                    $repo->getOwner(),
                    $repo->getDescription(),
                )
            );
        }

        $output->writeln($table->render());
    }

}