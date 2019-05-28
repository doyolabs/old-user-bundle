<?php

namespace Doyo\UserBundle\Test;

use Doctrine\Common\Inflector\Inflector;

trait MutableSpecTrait
{
    abstract public function getMutableProperties();

    abstract public function getMutableClassToTest();

    final private function generateGetter($property)
    {
        $r = new \ReflectionClass($this->getMutableClassToTest());
        if($r->hasMethod($method = 'get'.$property)){
            return $method;
        }

        if($r->hasMethod($method = 'is'.$property)){
            return $method;
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function its_properties_should_be_mutable()
    {
        $properties = $this->getMutableProperties();
        $r          = new \ReflectionClass($this->getMutableClassToTest());
        foreach ($properties as $method => $property) {
            $setter = 'set'.$method;
            $getter = $this->generateGetter($method);

            $value = isset($property['value']) ? $property['value']:'some-value';
            $default = isset($property['default']) ? $property['default']:null;

            if(!is_null($default)){
                $this->{$getter}()->shouldReturn($default);
            }

            $singular = Inflector::singularize($method);
            if ($r->hasMethod('add'.$singular)) {
                $this->handleCollectionProperties($singular, $value);
            } else {
                if ($r->hasMethod($setter)) {
                    $this->{$setter}($value)->shouldReturn($this);
                }
                if ($r->hasMethod($getter)) {
                    $this->{$getter}()->shouldReturn($value);
                }
            }
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
