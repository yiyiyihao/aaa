<?php

namespace ai\face\recognition;

interface FaceInterface
{
    /**
     * 唯一标识
     */
    public function getId();

    /**
     * 年龄
     */
    public function getAge();

    /**
     * 性别
     */
    public function getGender();

    /**
     * 笑容
     */
    public function getExpression();

    /**
     * 情绪
     */
    public function getEmotion();

    /**
     * 颜值
     */
    public function getBeauty();

    /**
     * 眼镜
     */
    public function getGlasses();

    /**
     * 人种
     */
    public function getRace();

    /**
     * 旋转角度
     */
    public function getAngle();
}