<?php


namespace spec\Doyo\UserBundle\Model;


use Doyo\UserBundle\Model\Group;

class TestGroup extends Group
{
    public function __construct()
    {
        parent::__construct('some-group', ['ROLE_USER']);
    }
}