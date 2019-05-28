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

putenv('APP_ENV='.$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test');
require dirname(__DIR__, 2).'/fixtures/config/bootstrap.php';
