#!/usr/bin/env bash

BASEDIR=$(dirname "$0")

echo ""
echo "Run composer"
echo "============"

if [ ! -f $BASEDIR/composer.phar ]; then
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  mv composer.phar $BASEDIR/composer.phar
fi
$BASEDIR/composer.phar install

echo ""
echo "Set up database"
echo "==============="

$BASEDIR/bin/console app:infrastructure:clear-database -f || exit 1
$BASEDIR/vendor/bin/phpmig migrate

echo ""
echo "Load fixtures"
echo "============="

$BASEDIR/bin/console app:infrastructure:load-fixtures || exit 1

echo ""
echo "Set up pre-commit hook"
echo "======================"

ln -sf ../../pre_commit.sh $BASEDIR/.git/hooks/pre-commit
