#!/usr/bin/env bash

EXIT_CODE=0
PHPSPEC_OPTS=""
PHPUNIT_OPTS=""
BEHAT_OPTS=""
JEST_OPTS=""

PHP_CMD=php
JEST_CMD="yarn test:unit"
test=`which phpdbg`

if [[ ${test} && ${COVERAGE} = yes ]]; then
    PHP_CMD="${test} -qrr";
fi;

if [[ ${COVERAGE} = yes ]]; then
    PHPSPEC_OPTS="--config=phpspec-coverage.yml";
    PHPUNIT_OPTS="--coverage-php=build/cov/coverage-phpunit.cov";
    BEHAT_OPTS="--config=behat-coverage.yml -p coverage";
    php etc/bin/generate-coverage-config.php
fi;


${PHP_CMD} ./vendor/bin/phpspec run --ansi ${PHPSPEC_OPTS} || EXIT_CODE=1;
${PHP_CMD} ./vendor/bin/phpunit --colors=always ${PHPUNIT_OPTS} || EXIT_CODE=2;
${PHP_CMD} ./vendor/bin/behat --colors ${BEHAT_OPTS} || EXIT_CODE=3;

if [[ ${COVERAGE} = yes ]]; then
    ${PHP_CMD} vendor/bin/phpcov merge --clover build/logs/clover.xml build/cov;
    ${PHP_CMD} vendor/bin/phpcov merge --html build/html build/cov;
fi;

exit ${EXIT_CODE}