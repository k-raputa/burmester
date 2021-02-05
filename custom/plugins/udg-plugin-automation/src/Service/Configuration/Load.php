<?php
declare(strict_types=1);

namespace UdgPluginAutomation\Service\Configuration;

use RuntimeException;

/**
 * Class Load
 * @desc Load configuration structure from plugin config - merged data as default
 * @package UdgPluginAutomation\Service\Configuration
 */
final class Load
{
    /**
     * @const string
     */
    const CONF_FILENAME_PATTERN = 'plugin_config_%s.php';

    /**
     * @var array
     */
    private $envFiles = [
        'production',
        'prod',
        'staging',
        'dev',
    ];

    private $projectDir = '';

    /**
     * @var array
     */
    private $configuration = [];

    /**
     * @var string
     */
    private $env = '';


    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }


    /**
     * @return array
     * @throws RuntimeException
     */
    public function loadMergedConfiguration(): array
    {
        foreach ($this->getEnvFiles() as $envFile) {
            $this->loadConfiguration($envFile);

            if ($envFile === $this->getEnv()) {
                break;
            }
        }

        return $this->getConfiguration();
    }

    /**
     * @param string $env
     * @return array
     * @throws RuntimeException
     */
    public function loadConfiguration(string $env = null): array
    {
        if ($env === null) {
            $env = $this->getEnv();
        }

        if (in_array($env, $this->getEnvFiles()) === false) {
            return $this->getConfiguration();
        }

        $file = $this->getConfigFilePath($env);

        if (!file_exists($file)) {
            throw new \RuntimeException(
                'Unknown configuration file provided; File: ' .  "' . $file . ' does not exist."
            );
        }

        $configExtract = include $file;

        if (!\is_array($configExtract)) {
            throw new \RuntimeException(
                'Invalid configuration file provided; PHP file does not return array value. File: ' .  "' . $file . '"
            );
        }

        $this->addConfiguration($configExtract);

        return $this->getConfiguration();
    }


    /**
     * @return Load
     */
    public function setRootDir(string $projectDir): Load
    {
        $this->projectDir = $projectDir . DIRECTORY_SEPARATOR;
        return $this;
    }

    /**
     * @return string
     */
    private function getRootDir(): string
    {
        return $this->projectDir;
    }

    /**
     * @param string $env
     * @return string
     */
    private function getConfigFilePath(string $env): string
    {
        return $this->getRootDir() . sprintf(self::CONF_FILENAME_PATTERN, $env);
    }

    /**
     * @return array
     */
    private function getEnvFiles(): array
    {
        return $this->envFiles;
    }

    /**
     * @param array $configuration
     */
    private function addConfiguration(array $configuration): void
    {
        $this->configuration = array_merge($this->configuration, $configuration);
    }

    /**
     * @return string
     */
    private function getEnv(): string
    {
        return $this->env;
    }

    /**
     * @param string $env
     *
     * @return Load
     */
    public function setEnv(string $env): Load
    {
        $this->env = strtolower(strval($env));

        if (empty($this->env)) {
            $this->env = reset($this->getEnvFiles());
        }

        return $this;
    }
}
