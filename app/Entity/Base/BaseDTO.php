<?php

namespace App\Entity\Base;

class BaseDTO
{
    protected function __construct(array $values)
    {
        foreach ($values as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public static function create(array $values): static
    {
        return new static($values);
    }

    public function toArray() {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();
        $array = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }

        return $array;
    }

}
