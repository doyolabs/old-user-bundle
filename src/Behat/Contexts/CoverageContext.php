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
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
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
     * @var string
     */
    private static $baseDir;

    public function __construct(
        $baseDir
    )
    {
        static::$baseDir = $baseDir;
    }

    public function beforeScenario(BeforeScenarioScope $scope)
    {

    }

    /**
     * @BeforeSuite
     */
    public static function setup(BeforeSuiteScope $scope)
    {
        $filter = new Filter();

        $filter->addDirectoryToWhitelist('src');
        $filter->removeDirectoryFromWhitelist('src/Behat');
        $filter->removeDirectoryFromWhitelist('src/Test');
        $filter->removeDirectoryFromWhitelist('src/Resources');
        $filter->removeDirectoryFromWhitelist('tests');
        $filter->removeDirectoryFromWhitelist('spec');
        self::$coverage = new CodeCoverage(null, $filter);

        self::$coverage->filter();
    }

    /**
     * @AfterSuite
     */
    public static function tearDown()
    {
        $feature = getenv('FEATURE') ?: 'behat';
        $baseDir = static::$baseDir;

        (new PHP())->process(self::$coverage, $baseDir."/build/cov/coverage-$feature.cov");
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
