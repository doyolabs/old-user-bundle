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

namespace Doyo\UserBundle\Test;

use Doctrine\Common\Inflector\Inflector;
use PhpSpec\Exception\Example\FailureException;

trait MutableSpecTrait
{
    abstract public function getMutableProperties();

    abstract public function getMutableClassToTest();

    final private function generateGetter($property)
    {
        $r = new \ReflectionClass($this->getMutableClassToTest());
        if ($r->hasMethod($method = 'is'.$property)) {
            return $method;
        }

        return 'get'.$property;
    }

    /**
     * @throws FailureException
     * @throws \ReflectionException
     */
    public function its_properties_should_be_mutable()
    {
        $properties = $this->getMutableProperties();
        $r          = new \ReflectionClass($this->getMutableClassToTest());
        $exceptions = [];

        foreach ($properties as $method => $property) {
            $setter  = 'set'.$method;
            $getter  = $this->generateGetter($method);
            $value   = $property['value'] ?? 'some-value';

            if (\array_key_exists('default', $property)) {
                $default = $property['default'];
                $this->{$getter}()->shouldReturn($default);
            }

            $singular = Inflector::singularize($method);
            if ($r->hasMethod('add'.$singular)) {
                $this->handleCollectionProperties($singular, $value);
            } else {
                if (!$r->hasMethod($getter) && !$r->hasMethod($setter)) {
                    $exceptions[] = sprintf('Method set or get not found for property: %s', $method);
                } else {
                    if ($r->hasMethod($setter)) {
                        $this->{$setter}($value)->shouldReturn($this);
                    }
                    if ($r->hasMethod($getter) && $r->hasMethod($setter)) {
                        $this->{$getter}()->shouldReturn($value);
                    }
                }
            }
        }

        if (\count($exceptions) > 1) {
            throw new FailureException(implode("\n", $exceptions));
        }
    }

    private function handleCollectionProperties($method, $value)
    {
        $has    = 'has'.$method;
        $add    = 'add'.$method;
        $remove = 'remove'.$method;

        $this->{$has}($value)->shouldReturn(false);

        $this->{$add}($value)->shouldReturn($this);
        $this->{$has}($value)->shouldReturn(true);

        $this->{$remove}($value)->shouldReturn($this);
        $this->{$has}($value)->shouldReturn(false);
    }
}
