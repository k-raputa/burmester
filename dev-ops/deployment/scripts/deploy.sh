#!/bin/sh

set -e

DEPLOY_BUILD_FOLDER=$1
CIRCLE_BUILD_NUM=$2
SUBFOLDER=$3 #ex staging_shopware

# Workout the releases path & current release path
DEPLOY_PATH="$HOME/$SUBFOLDER/releases"
SHARED_PATH="$HOME/$SUBFOLDER/shared"


echo "Adding execute permissions for files to user and group"
find $DEPLOY_PATH/ -type f -exec chmod u+x {} \;
echo "Linking shared files and folders into new release"
cd $DEPLOY_PATH/$DEPLOY_BUILD_FOLDER
ln -s $SHARED_PATH/.env .env
ln -s $SHARED_PATH/.env.dist .env.dist

cd config
rm -rf jwt
ln -s $SHARED_PATH/config/jwt jwt
cd ../public
rm -rf media
rm -rf thumbnail
ln -s $SHARED_PATH/public/media media
ln -s $SHARED_PATH/public/thumbnail thumbnail
#ln -s $SHARED_PATH/public/bundles bundles
#ln -s $SHARED_PATH/public/robots.txt robots.txt

cd ..
mkdir var
cd var
ln -s $SHARED_PATH/var/log log
cd ..
mkdir files
cd files
ln -s /home/jusee/shared/files/media media
echo "Running build commands"
cd ..

mkdir platform
./psh.phar init-composer
chmod 777 bin/console
bin/console database:migrate --all core
bin/console bundle:dump
./psh.phar deployment:build
./psh.phar deployment:finish

#./psh.phar administration:init
#./psh.phar storefront:init
#bin/console assets:install
#bin/console theme:compile
#bin/console cache:clear

echo "Enabling maintenance mode"
cd ../..
cd shopware/current
bin/console sales-channel:maintenance:enable --all
echo "Linking current to revision: $DEPLOY_BUILD_FOLDER"
cd ..
rm -f current
ln -s /home/jusee/releases/$DEPLOY_BUILD_FOLDER current
echo "Post deploy commands"
cd current
#cachetool --fcgi=127.0.0.1:9000 opcache:reset
bin/console sales-channel:maintenance:disable --all
cd ../..
##echo "Removing old releases"
##cd releases && ls -t | tail -n +5 | xargs rm -rf

