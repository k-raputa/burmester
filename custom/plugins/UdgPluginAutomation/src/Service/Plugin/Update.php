<?php
declare(strict_types=1);

namespace UdgPluginAutomation\Service\Plugin;

use Exception;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\ContainsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\Plugin\PluginEntity;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Update
 * @desc Service class to (re)install and configure plugins
 * @package UdgPluginAutomation\Service\Plugin
 */
final class Update
{
    /**
     * @var array
     */
    private $installSettings = [
        'install' => true,
        'uninstall' => true,
        'update' => true,
        'activate' => true,
    ];

    /**
     * @var Application
     */
    private $application;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var EntityRepositoryInterface
     */
    private $pluginRepo;

    /**
     * @OutputInterface $output
     * @param array $configuration
     * @return void
     * @throws Exception
     */
    public function execute(InputInterface $input, OutputInterface $output, array $configuration): void
    {
        $this->setSymfonyStyle((new SymfonyStyle($input, $output)));

        foreach ($configuration as $pluginName => $plugin) {
            $ignoreErrors = array_key_exists('ignoreErrors', $plugin);

            if ($ignoreErrors === true) {
                set_error_handler(array(&$this, 'catchError'));
            }

            //install plugins
            $this->enablePlugins($output, $pluginName, $plugin);

            if ($ignoreErrors === true) {
                restore_error_handler();
            }
        }
    }

    /**
     * @param int $severity
     * @param string $message
     * @param string $filename
     * @param int $lineno
     */
    public function catchError(int $severity, string $message, string $filename, int $lineno): void
    {
        $this->getSymfonyStyle()->warning('Error while reinstalling a plugin: ' . $message);
    }

    /**
     * @param EntityRepositoryInterface $pluginRepo
     */
    public function setPluginRepository(EntityRepositoryInterface $pluginRepo)
    {

        $this->pluginRepo = $pluginRepo;
    }

    /**
     * @param OutputInterface $output
     * @param string $pluginName
     * @param array $pluginConfig
     * @throws Exception
     */
    private function enablePlugins(OutputInterface $output, string $pluginName, array $pluginConfig): void
    {
        try {
            $context = Context::createDefaultContext();
            $criteria = new Criteria();
            $criteria->addFilter(new MultiFilter(
                MultiFilter::CONNECTION_OR,
                [
                    new ContainsFilter('name', $pluginName),
                    new ContainsFilter('name', $pluginName),
                ]
            ));
dump($pluginName);
            $plugins = $this->pluginRepo->search($criteria, $context)->getEntities()->getElements();
            /* @var $plugin PluginEntity */
            $plugin = array_pop($plugins);
        } catch (\Exception $e) {
            $output->writeln(sprintf('Plugin by name "%s" was not found.', $pluginName));

            return;
        }

        $pluginActive = $plugin->getActive();
        $pluginInstalled = ($plugin->getInstalledAt() !== null);

        //install settings
        $installSettings = $this->getDefaultInstallSettings();

        if (array_key_exists('installSettings', $pluginConfig) && is_array($pluginConfig['installSettings'])) {
            $installSettings = array_merge($installSettings, $pluginConfig['installSettings']);
        }
        $options = [
            'plugins' => [$pluginName],
             '-r'
        ];


        if ($pluginInstalled) {
            if ($installSettings['uninstall'] === true) {
                $uninstallOptions = $options;

                if (array_key_exists('secureUninstall', $pluginConfig) && $pluginConfig['secureUninstall'] === true) {
                    throw new Exception('secureUnistall does not exists....');
                    #$uninstallOptions = array_merge($options, ['--secure' => true]);
                }

                $this->getRunCommand($output, 'plugin:uninstall', $uninstallOptions);
            }
            if ($installSettings['install'] === true) {

                $migrateOptions = ['--all' => $pluginName];
                $this->getRunCommand($output, 'database:migrate', $migrateOptions);

                $this->getRunCommand($output, 'plugin:update', $options);
            }

        } elseif ($installSettings['install'] === true) {
            $this->getRunCommand($output, 'plugin:install', $options);

            $migrateOptions = ['--all' => $pluginName];
            $this->getRunCommand($output, 'database:migrate', $migrateOptions);
        }

        if (!$pluginActive && $installSettings['activate'] === true) {
            $this->getRunCommand($output, 'plugin:activate', $options);
        }

        $this->getRunCommand($output, 'udg:cache:clear');
    }

    /**
     * @param OutputInterface $output
     * @param string $cliCommand
     * @param null $options
     */
    private function getRunCommand(OutputInterface $output, string $cliCommand, $options = null): void
    {
        $command = $this->getApplication()->find($cliCommand);

        $input = ['command' => $cliCommand];

        if ($options !== null) {
            $input = array_merge($input, $options);
        }

        $newInput = new ArrayInput($input);

        $command->run($newInput, $output);
    }

    /**
     * @return Application
     */
    private function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @param Application $application
     *
     * @return Update
     */
    public function setApplication(Application $application): Update
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return array
     */
    private function getDefaultInstallSettings(): array
    {
        return $this->installSettings;
    }

    /**
     * @return SymfonyStyle
     */
    private function getSymfonyStyle(): SymfonyStyle
    {
        return $this->symfonyStyle;
    }

    /**
     * @param SymfonyStyle $symfonyStyle
     */
    private function setSymfonyStyle(SymfonyStyle $symfonyStyle): void
    {
        $this->symfonyStyle = $symfonyStyle;
    }
}
