<?php

namespace ai\face\recognition;

interface BaiduBCEInterface
{
    public function detect($image, $imageType);

    public function compare(array $images);

    public function search($image, $imageType, $groupIdList);

    public function addUser($image, $imageType, $groupId, $userId);

    // public function updateUser($image, $imageType, $groupId, $userId);

    public function deleteUser($groupId, $userId);

    public function getUser($userId, $groupId);

    public function deleteFace($userId, $groupId, $faceToken);

    public function faceVerify(array $images);
}