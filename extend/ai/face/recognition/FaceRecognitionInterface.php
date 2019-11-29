<?php

namespace ai\face\recognition;

interface FaceRecognitionInterface
{
    /**
     * 人脸检测
     */
    public function detect($image);

    /**
     * 人脸对比
     */
    public function compare($imageA, $imageB);

    /**
     * 人脸搜索
     */
    public function search($image, array $groupIds);

    /**
     * 新增用户
     */
    public function addUser($image, $userId, $groupId, $name = null, $gender = null);

    /**
     * 更新用户信息
     */
    public function updateUser($userId, $name);

    /**
     * 删除用户
     */
    public function deleteUser($userId, $groupId = null);

    /**
     * 查询用户信息
     */
    public function getUser($userId, $groupId = null);

    /**
     * 新增人脸
     */
    public function addFace($userId, $image, $groupId = null);

    /**
     * 删除人脸
     */
    public function deleteFace($userId, $faceId, $groupId = null);

    public function liveVerify(array $images);
}