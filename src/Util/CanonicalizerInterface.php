<?php


namespace Doyo\UserBundle\Util;


interface CanonicalizerInterface
{
    public function canonicalize($value);
}