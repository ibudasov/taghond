<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('taghond:run')
            ->setDescription('Runs Taghond')
            ->setHelp('Once this command is running, Taghond will check the specified folder and try to set appropriate tags to the pictures');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln([
            'Taghond is running...',
            '=====================',
            '',
        ]);
    }
}
