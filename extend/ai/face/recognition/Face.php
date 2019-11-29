<?php

namespace ai\face\recognition;

use ArrayAccess;
use JsonSerializable;

class Face implements ArrayAccess, FaceInterface, JsonSerializable
{
    use Attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * 唯一标识
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * 年龄
     */
    public function getAge()
    {
        return $this->getAttribute('age');
    }

    /**
     * 性别
     */
    public function getGender()
    {
        return $this->getAttribute('gender');
    }

    /**
     * 笑容
     */
    public function getExpression()
    {
        return $this->getAttribute('expression');
    }

    /**
     * 情绪
     */
    public function getEmotion()
    {
        return $this->getAttribute('emotion');
    }

    /**
     * 颜值
     */
    public function getBeauty()
    {
        return $this->getAttribute('beauty');
    }

    /**
     * 眼镜
     */
    public function getGlasses()
    {
        return $this->getAttribute('glasses');
    }

    /**
     * 人种
     */
    public function getRace()
    {
        return $this->getAttribute('race');
    }

    /**
     * 旋转角度
     */
    public function getAngle()
    {
        return $this->getAttribute('angle');
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