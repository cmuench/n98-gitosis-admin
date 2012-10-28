<?php

namespace N98\Gitosis\Admin\Cli\Command\Gitosis;

use N98\Gitosis\Admin\Cli\Command\GitosisCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PersistCommand extends GitosisCommand
{
    protected function configure()
    {
        $this->setName('gitosis:persist')
             ->setDescription('Pushes configuration to gitosis');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->getConfig()->persist();
            $output->writeln('<info>Pushed config to gitosis</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }

}
