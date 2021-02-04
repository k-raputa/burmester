<?php declare(strict_types=1);

namespace UdgMediaCommand\Command;

use Shopware\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UdgMediaCommand\Service\MediaUploader;

class MediaUploadCommand extends Command
{

    /**
     * @var MediaUploader
     */
    private $mediaUploader;

    public function __construct(MediaUploader $mediaUploader
    )
    {
        $this->mediaUploader = $mediaUploader;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('udg:media:upload')
            ->addArgument('filename');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $filename = $input->getArgument('filename');
        $output->writeln('File: ' . $filename);

        $datas = $this->getDataFromCsv($filename);
        $output->writeln('Importing media...');

        foreach ($datas as $data) {
            $this->mediaUploader->upload($data['url'], $data['file_name'], $data['folder'], $data['file_extension']);
        }
    }


    /**
     * @param string $filename
     * @return array
     */
    private function getDataFromCsv(string $filename): array
    {

        if (($handle = fopen($filename, 'r')) === false) {
            die('Error opening file');
        }

        $headers = fgetcsv($handle, 256, ';');
        $array = array();

        while ($row = fgetcsv($handle, 256, ';')) {
            $array[] = array_combine($headers, $row);
        }

        fclose($handle);

        return $array;
    }

}
