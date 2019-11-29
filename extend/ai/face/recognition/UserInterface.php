<?php

namespace ai\face\recognition;

interface UserInterface
{
    public function getId();

    public function getGroupId();

    public function getName();

    public function getInfo();
}