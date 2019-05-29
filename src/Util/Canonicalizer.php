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

namespace Doyo\UserBundle\Util;

class Canonicalizer implements CanonicalizerInterface
{
    /**
     * @param $string
     *
     * @return false|mixed|string|string[]|void|null
     */
    public function canonicalize($string)
    {
        if (null === $string) {
            return;
        }
        $encoding = mb_detect_encoding($string, mb_detect_order(), true);

        return $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);
    }
}
