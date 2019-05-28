<?php


namespace Doyo\UserBundle\Util;


class Canonicalizer implements CanonicalizerInterface
{
    /**
     * @param $string
     * @return false|mixed|string|string[]|void|null
     */
    public function canonicalize($string)
    {
        if (null === $string) {
            return;
        }
        $encoding = mb_detect_encoding($string);
        $result = $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);
        return $result;
    }
}