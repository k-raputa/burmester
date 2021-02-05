<?php
declare(strict_types=1);

namespace UdgPluginAutomation\Commands;

use Shopware\Core\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CacheClearCommand
 * @desc Savely clear cache and recreate basic cache directory structure to prevent missing folder exceptions
 * @package UdgPluginAutomation\Commands
 */
class CacheClearCommand extends Command
{
    /**
     * @var array
     */
    private $subDirectories = [
        'proxies' => [],
        'general' => [],
        'doctrine' => [
            'proxies' => [],
            'attributes' => [],
        ],
    ];


    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('udg:pluginautomation:cache:clear')
            ->setAliases(['udg:cache:clear'])
            ->setDescription(
                'Clear cache and recreate previously removed cache directory' .
                ' (with $subDirectories) to prevent errors due to non-existing directories'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        try {
            /* @var Kernel $kernel */
            $kernel = $this->getApplication()->getKernel();
            $kernel_cache_dir = $kernel->getContainer()->getParameter('kernel.cache_dir') ;

            //clear cache
            $this->getApplication()->find('cache:clear')->run(new ArrayInput([]), $output);

            //recreate basic cache directory structure - see: $subDirectories
            $this->createSubDirectory(
                array($kernel_cache_dir => $this->getSubDirectories())
            );

        } catch (\Exception $e) {
            $io->note($e->getMessage());
        }
    }


    /**
     * @param array  $directories
     * @param string $rootDirectory
     */
    private function createSubDirectory(array $directories, string $rootDirectory = ''): void
    {
        if (is_array($directories) === false) {
            return;
        }

        /** @var Filesystem $filesystem */
        $filesystem = new Filesystem();

        foreach ($directories as $subDirectory => $subDirectories) {
            $absSubDirectory = $rootDirectory . (empty($rootDirectory) ? '' : DIRECTORY_SEPARATOR) . $subDirectory;

            if ($filesystem->exists($absSubDirectory) === false) {
                $filesystem->mkdir($absSubDirectory);
            }

            $this->createSubDirectory($subDirectories, $absSubDirectory);
        }
    }

    /**
     * @return array
     */
    private function getSubDirectories(): array
    {
        return $this->subDirectories;
    }
}
