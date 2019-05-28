<?php

namespace Doyo\UserBundle\Test;

trait MutableSpecTrait
{
    abstract public function getMutableProperties();

    abstract public function getMutableClassToTest();

    /**
     * @throws \ReflectionException
     */
    public function its_properties_should_be_mutable()
    {
        $properties = $this->getMutableProperties();
        $r          = new \ReflectionClass($this->getMutableClassToTest());
        foreach ($properties as $method => $property) {
            $setter = 'set'.$method;
            $getter = 'get'.$method;
            $value = isset($property['value']) ? $property['value']:'some-value';
            $default = isset($property['default']) ? $property['default']:null;

            if(!is_null($default)){
                $this->{$getter}()->shouldReturn($default);
            }

            if ($r->hasMethod('add'.$method)) {
                $this->handleCollectionProperties($method, $value);
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

        $this->{$add}($value)->shouldReturn($this);
        $this->{$has}($value)->shouldReturn(true);

        $this->{$remove}($value)->shouldReturn($this);
        $this->{$has}($value)->shouldReturn(false);
    }
}
