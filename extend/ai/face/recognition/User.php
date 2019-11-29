<?php

namespace ai\face\recognition;

use ArrayAccess;
use JsonSerializable;

class User implements ArrayAccess, UserInterface, JsonSerializable
{
    use Attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getId()
    {
        return $this->getAttribute('id');
    }

    public function getGroupId()
    {
        return $this->getAttribute('group_id');
    }

    public function getName()
    {
        return $this->getAttribute('name');
    }

    public function getInfo()
    {
        return $this->getAttribute('info');
    }

    public function getOriginal()
    {
        return $this->getAttribute('original');
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->attributes;
    }
}