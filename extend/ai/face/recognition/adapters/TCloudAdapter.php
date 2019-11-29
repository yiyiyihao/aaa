<?php

namespace ai\face\recognition\adapters;

use ai\face\recognition\Face;
use ai\face\recognition\FaceAttributes;
use ai\face\recognition\FaceRecognitionInterface;
use ai\face\recognition\TCloudInterface;

class TCloudAdapter implements FaceRecognitionInterface
{

    protected $tClound;

    public function __construct(TCloudInterface $tCloud)
    {
        $this->tClound = $tCloud;
    }

    /**
     * 人脸检测
     */
    public function detect($image)
    {
        $face = $this->tClound->detect($image);
        return $this->mapFaceToObject($face)->merge(['original' => $face]);;
    }

    /**
     * 人脸对比
     */
    public function compare($imageA, $imageB)
    {
        return $this->tClound->compare($imageA, $imageB);
    }

    /**
     * 人脸搜索
     */
    public function search($image, array $groupIds)
    {
        return $this->tClound->search($image, $groupIds);
    }

    public function createGroup($groupId, $groupName)
    {
        return $this->tClound->createGroup($groupId, $groupName);
    }

    /**
     * 新增用户
     */
    public function addUser($image, $userId, $groupId, $name = null, $gender = null)
    {
        return $this->tClound->createPerson($image, $userId, [$groupId], $name, $gender);
    }

    /**
     * 更新用户信息
     */
    public function updateUser($userId, $name)
    {
        return $this->tClound->modifyPerson($userId, $name);
    }

    /**
     * 删除用户
     */
    public function deleteUser($userId, $groupId = null)
    {
        return $this->tClound->deletePerson($userId);
    }

    /**
     * 查询用户信息
     */
    public function getUser($userId, $groupId = null)
    {
        return $this->tClound->getPerson($userId);
    }

    /**
     * 新增人脸
     */
    public function addFace($userId, $image, $groupId = null)
    {
        return $this->tClound->createFace($userId, [$image]);
    }

    /**
     * 删除人脸
     */
    public function deleteFace($userId, $faceId, $groupId = null)
    {
        return $this->tClound->deleteFace($userId, [$faceId]);
    }

    public function liveVerify(array $images)
    {
        return $this->tClound->detectLiveFace($images[0]);
    }

    protected function mapFaceToObject(array $face)
    {
        $attributes = $face['FaceAttributesInfo'];
        return new Face([
            'id' => '',
            'age' => $attributes['Age'],
            'gender' => FaceAttributes::formatGender($attributes['Gender'], [0, 100]),
//            'expression' => FaceAttributes::formatExpression($attributes['Expression'], [0, 50, 100]),
            'expression' => '',
            'beauty' => $attributes['Beauty'],
            'glasses' => FaceAttributes::formatGlasses($attributes['Glass'], [0, 1, 2]),
            'angle' => [
                'yaw' => $attributes['Yaw'],
                'pitch' => $attributes['Pitch'],
                'roll' => $attributes['Roll'],
            ],
        ]);
    }
}