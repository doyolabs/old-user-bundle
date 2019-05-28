<?php

namespace spec\Doyo\UserBundle\Util;

use Doyo\UserBundle\Util\Canonicalizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CanonicalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Canonicalizer::class);
    }

    function it_should_canonicalize_string()
    {
        $this->canonicalize(null)->shouldReturn(null);

        $this->canonicalize('foo@bar')->shouldReturn('foo@bar');
        $this->canonicalize('HelloWorld')->shouldReturn('helloworld');
    }
}
