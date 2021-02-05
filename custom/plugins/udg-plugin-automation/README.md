# UdgPluginAutomation
With this Plugin you are able to update your shopware environment by env-separated config files automatically by build or by calling the cli command.

## Create Config Files
1. Create your config files separated by environment in shopware app root directory. E.g.: "plugin_config_dev.php", see configuration below.
2. Include the update command into your build process or execute it manually.

Possible envrironment configs:
* production
* staging
* dev

## Update Environment
You can use the following CLI Commands:
* _udg:pluginautomation:cache:clear (udg:cache:clear)_
This one clears the cache and rebuilds a basic cache directory structure right after deleting to prevent non-existing folder exceptions during installations
* _udg:pluginautomation:update:environment (udg:configuration:update)_
Installs, uninstalls or updates plugins, provided in the below structure

## Syntax Documentation
### installSettings
The installSettings array may include the four boolean keys. These will be merged with the default settings to determine, wether one of these commands will be executed or not. So, plugins could be installed only once and won't be tpuched anymore. Furthermore, plugins could be uninstalled within the deployment process.
* install => true
* uninstall => true
* update => true
* activate => true

### configSql

Use Migration files instead of pure sql settings:
https://docs.shopware.com/en/shopware-platform-dev-en/how-to/plugin-migrations

## Example config
The plugins will be installed by given order - see: Structure below

```
<?php

/**
 * add baseconfig for all environments to production
 * overwrite by staging, dev
 * name overwriting files with its postfix
 */
return [
    'UdgRemoteFile' => [],
    'FroshProfiler' => [
        'secureUninstall' => true,
    ],
    'Cron' => [],
];

```
