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

namespace spec\Doyo\UserBundle\Util;

use Doyo\UserBundle\Util\Canonicalizer;
use PhpSpec\ObjectBehavior;

class CanonicalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Canonicalizer::class);
    }

    public function it_should_canonicalize_string()
    {
        $this->canonicalize(null)->shouldReturn(null);

        $this->canonicalize('foo@bar')->shouldReturn('foo@bar');
        $this->canonicalize('HelloWorld')->shouldReturn('helloworld');
    }
}
