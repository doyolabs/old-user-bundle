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

namespace Doyo\UserBundle\Behat\Contexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP;

/**
 * Behat coverage.
 *
 * @author Anthonius Munthi <me@itstoni.com>
 * @copyright Adapted from api-platform project
 */
final class CoverageContext implements Context
{
    /**
     * @var CodeCoverage
     */
    private static $coverage;

    /**
     * @BeforeSuite
     */
    public static function setup()
    {
        $filter = new Filter();
        $filter->addDirectoryToWhitelist(__DIR__.'/../../../src');
        $filter->removeDirectoryFromWhitelist(__DIR__.'/../../../src/Behat');
        $filter->removeDirectoryFromWhitelist(__DIR__.'/../../../src/Test');
        $filter->removeDirectoryFromWhitelist(__DIR__.'/../../../src/Resources');
        $filter->removeDirectoryFromWhitelist(__DIR__.'/../../../tests');
        $filter->removeDirectoryFromWhitelist(__DIR__.'/../../../spec');
        self::$coverage = new CodeCoverage(null, $filter);
    }

    /**
     * @AfterSuite
     */
    public static function tearDown()
    {
        $feature = getenv('FEATURE') ?: 'behat';
        (new PHP())->process(self::$coverage, __DIR__."/../../../build/cov/coverage-$feature.cov");
    }

    /**
     * @BeforeScenario
     */
    public function startCoverage(BeforeScenarioScope $scope)
    {
        self::$coverage->start("{$scope->getFeature()->getTitle()}::{$scope->getScenario()->getTitle()}");
    }

    /**
     * @AfterScenario
     */
    public function stopCoverage()
    {
        self::$coverage->stop();
    }
}
