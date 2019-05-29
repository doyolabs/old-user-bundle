<?php


namespace Doyo\UserBundle\Command;


use Symfony\Component\Console\Command\Command;

class CreateUserCommand extends Command
{
    public function __construct(

    )
    {
        parent::__construct('doyo:user:create');
    }
}