<?php

/*
 * This file is part of the DoyoUserBundle project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

include __DIR__.'/../../vendor/autoload.php';

$rootDir   = realpath(__DIR__.'/../../');
$targetDir = $rootDir.'/build';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// generate phpspec config
$phpspec  = $rootDir.'/phpspec.yml.dist';
$contents = file_get_contents($phpspec);
$contents .= <<<EOC
extensions:
    Doyo\UserBundle\Test\PhpSpecCodeCoverageExtension:
        format:
            - php
        output:
            php: build/cov/coverage-phpspec.cov
        whitelist:
            - src
        blacklist:
            - src/Test
            - src/Behat
            - tests
            - spec
EOC;
file_put_contents($rootDir.'/phpspec-coverage.yml', $contents, LOCK_EX);

use Symfony\Component\Yaml\Yaml;

$configFile     = $rootDir.'/behat.yml.dist';
$coverageConfig = [];
$config         = Yaml::parseFile($configFile);

$behatContexts = [
    'Doyo\UserBundle\Behat\Contexts\CoverageContext' => [
        'baseDir' => '%paths.base%'
    ]
];
$coverageConfig['coverage']['suites']['default']['contexts']   = $config['default']['suites']['default']['contexts'];
$coverageConfig['coverage']['suites']['default']['contexts'][] = $behatContexts;

$contents = file_get_contents($configFile);
$contents .= PHP_EOL.PHP_EOL.Yaml::dump($coverageConfig, 5);
file_put_contents($rootDir.'/behat-coverage.yml', $contents, LOCK_EX);
