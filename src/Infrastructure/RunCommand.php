<?php

declare(strict_types=1);

namespace Taghond\Infrastructure;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Taghond\Application\PictureApplicationService;
use Taghond\Domain\FileReader;

class RunCommand extends Command
{
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
        $this->fileReader = $fileReader;
        $this->pictureApplicationService = $pictureApplicationService;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('directoryWithPictures', InputArgument::REQUIRED, 'Where are your pictures located? Full path please: "/tmp"')
            ->addArgument('basicTags', InputArgument::REQUIRED, 'Specify here the tags you want to assign to all the pictures in this location. Suppose to be comma-separated words: "amsterdam, nederlands, landscape"')
            ->addArgument('geoTag', InputArgument::REQUIRED, 'Latitude and longitude like this: "52.356582, 4.871792". You can get it from https://www.google.nl/maps/')
            ->setName('taghond:run')
            ->setDescription('Runs Taghond')
            ->setHelp(
                'Once this command is running, Taghond will check the specified folder and try to set appropriate tags to the pictures.
Command and parameters could look like this: 
taghond:run /tmp "amsterdam, nederlands" "52.356582, 4.871792"');
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
            'Looking for pictures in directory: '.$input->getArgument('directoryWithPictures'),
        ]);

        $foundPictures = $this->fileReader->readDirectory($input->getArgument('directoryWithPictures'));

        $output->writeln('Found '.\count($foundPictures).' pictures');

        $table = new Table($output);
        $table->setHeaders(['Picture', 'Tags']);

        foreach ($foundPictures as $picture) {
            $updatedPicture = $this->pictureApplicationService->updatePicture($picture);
            $tags = \implode(', '.PHP_EOL, $updatedPicture->getTags());
            $table->addRow([$picture->getFileName(), $tags]);
        }

        $table->render();
    }
}
