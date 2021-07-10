#!/usr/bin/env bash

#
# install me:
# $ ln -sf ../../pre_commit.sh .git/hooks/pre-commit
#

BASEDIR=$(dirname "$0")
if [ ! -z $(echo "$BASEDIR" | grep 'git/hooks$') ]; then
    BASEDIR="$BASEDIR/../.."
fi

STAGED_FILES=`git diff --staged --name-only`
if [ ! -z "$STAGED_FILES" ]; then
    STAGED_FILES=`echo "$STAGED_FILES" | xargs ls -d 2>/dev/null`
fi

if [ ! -z "$STAGED_FILES" ]; then
  STAGED_PHP_FILES=`echo "$STAGED_FILES" | grep .php$`

  if [ ! -z "$STAGED_PHP_FILES" ]; then
      echo ""
      echo "phpstan"
      echo "======="
      $BASEDIR/vendor/bin/phpstan analyse $BASEDIR/phpstan.neon || exit 1

      echo ""
      echo "phpunit"
      echo "======="
      $BASEDIR/vendor/bin/phpunit || exit 1
  fi
fi
