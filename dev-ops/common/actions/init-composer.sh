#!/usr/bin/env bash

/opt/plesk/php/7.3/bin/php /opt/psa/var/modules/composer/composer.phar install --no-interaction --optimize-autoloader --no-suggest --no-scripts
/opt/plesk/php/7.3/bin/php /opt/psa/var/modules/composer/composer.phar install --no-interaction --optimize-autoloader --no-suggest --working-dir=dev-ops/analyze
