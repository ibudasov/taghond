<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Taghond\Application\PictureApplicationService;
use Taghond\Domain\FileReader;

class RunCommand extends Command
{
    const TAGHOND_WORKING_DIRECTORY = __DIR__ . '/../../var';

    /** @var FileReader */
    private $fileReader;
    /** @var PictureApplicationService */
    private $pictureApplicationService;

    /**
     * @param FileReader                $fileReader
     * @param PictureApplicationService $pictureApplicationService
     */
    public function __construct(FileReader $fileReader, PictureApplicationService $pictureApplicationService)
    {
        parent::__construct();

        // Pictures usually ~20mb of size, so it's quite exhaustive for the memory.
        \ini_set('memory_limit', '-1');

        $this->fileReader = $fileReader;

        $this->pictureApplicationService = $pictureApplicationService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('basicTags', InputArgument::REQUIRED, 'Specify here the tags you want to assign to all the pictures in this location. Suppose to be comma-separated words: "amsterdam, nederlands, landscape"')
            ->addArgument('captionPrefix', InputArgument::REQUIRED, 'Will be added to every picture in the set, e.g.: "Norway, Trondheim: "')
            ->setName('taghond:run')
            ->setDescription('Runs Taghond')
            ->setHelp(
                'Please put your files to '.self::TAGHOND_WORKING_DIRECTORY.' before you start Taghond 
Once running, Taghond will check the specified folder and try to set appropriate tags to the pictures.
Command and parameters could look like this: 
taghond:run "amsterdam, nederlands" "Norway, Trondheim: "');
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
            'Looking for pictures in directory: '.self::TAGHOND_WORKING_DIRECTORY,
        ]);


        $foundPictures = $this->fileReader->readDirectory(self::TAGHOND_WORKING_DIRECTORY);

        $output->writeln('Found '.\count($foundPictures).' pictures');

        $progressBar = new ProgressBar($output, \count($foundPictures));
        $progressBar->start();

        $table = new Table($output);
        $table->setHeaders(['Picture', 'Caption', 'Tags']);

        foreach ($foundPictures as $picture) {
            $progressBar->advance();

            $updatedPicture = $this->pictureApplicationService->updatePicture(
                $picture
//                $input->getArgument('captionPrefix'),
//                $input->getArgument('basicTags')
            );

            $table->addRow([
                $picture->getFileName(),
                $picture->getCaption(),
                \implode(PHP_EOL, $updatedPicture->getTags())
            ]);
        }

        $progressBar->finish();

        $output->writeln('');

        $table->render();
    }
}
