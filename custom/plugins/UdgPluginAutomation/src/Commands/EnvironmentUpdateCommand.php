<?php
declare(strict_types=1);

namespace UdgPluginAutomation\Commands;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use UdgPluginAutomation\Service\Plugin\Update AS PluginUpdate;
use UdgPluginAutomation\Service\Configuration\Load AS ConfigurationLoad;

/**
 * Class EnvironmentUpdateCommand
 * @desc Load plugin data from plugin config and reinstall plugins
 * @package UdgPluginAutomation\Commands
 */
class EnvironmentUpdateCommand extends Command
{
    /**
     * @var PluginUpdate
     */
    protected $pluginUpdate;

    /**
     * @var ConfigurationLoad
     */
    protected $configurationLoad;

    public function __construct(PluginUpdate $configurationUpdate, ConfigurationLoad $configurationLoad)
    {
        $this->pluginUpdate = $configurationUpdate;
        $this->configurationLoad = $configurationLoad;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('udg:pluginautomation:update:environment')
            ->setAliases(['udg:environment:update'])
            ->setDescription('Update, install, uninstall or reinstall plugins');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        //refresh plugin list
        $this->getApplication()->find('plugin:refresh')->run(new ArrayInput([]), $output);

        // load config and run install/update/uninstall plugins and update settings
        $this->getPluginUpdate()->execute(new ArrayInput([]), $output, $this->getConfiguration($input->getOption('env')));

        //show a final plugin list
        $this->getApplication()->find('plugin:list')->run(new ArrayInput([]), $output);

        //clear cache once again
        $this->getApplication()->find('udg:pluginautomation:cache:clear')->run(new ArrayInput([]), $output);
    }

    /**
     * @return PluginUpdate
     */
    private function getPluginUpdate(): PluginUpdate
    {
        return $this->pluginUpdate
            ->setApplication($this->getApplication());
    }

    /**
     * @param string $env
     * @return array
     */
    private function getConfiguration(string $env): array
    {
        $kernel = $this->getApplication()->getKernel();
        return $this->configurationLoad
            ->setEnv($env)
            ->setRootDir($kernel->getContainer()->getParameter('kernel.project_dir'))
            ->loadConfiguration();
    }
}
