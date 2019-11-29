<?php

namespace ai\face\recognition\adapters;

use ErrorException;
use ai\face\recognition\Face;
use ai\face\recognition\FaceAttributes;
use ai\face\recognition\FaceRecognitionInterface;
use ai\face\recognition\TencentYoutuInterface;
use ai\face\recognition\User;

class TencentYoutuAdapter implements FaceRecognitionInterface
{
    protected $tencentYoutu;

    public function __construct(TencentYoutuInterface $tencentYoutu)
    {
        $this->tencentYoutu = $tencentYoutu;
    }


    /**
     * 人脸检测
     */
    public function detect($image)
    {
        $face = $this->tencentYoutu->detect($image);
        return $this->mapFaceToObject($face);
    }

    /**
     * 人脸对比
     */
    public function compare($imageA, $imageB)
    {
        return $this->tencentYoutu->compare($imageA, $imageB);
    }

    /**
     * 人脸搜索
     */
    public function search($image, array $groupIds)
    {
        return $this->tencentYoutu->search($image, $groupIds);
    }

    /**
     * 新增用户
     */
    public function addUser($image, $userId, $groupId, $name = null, $gender = null)
    {
        return $this->tencentYoutu->addPerson($image, $userId, [$groupId], $name);
    }

    /**
     * 更新用户信息
     */
    public function updateUser($userId, $name)
    {
        return $this->tencentYoutu->updatePerson($userId, $name);
    }

    /**
     * 删除用户
     */
    public function deleteUser($userId, $groupId = null)
    {
        return $this->tencentYoutu->deletePerson($userId);
    }

    /**
     * 查询用户信息
     */
    public function getUser($userId, $groupId = null)
    {
        $user = $this->tencentYoutu->getPerson($userId);
        return $this->mapUserToObject($user)->merge(['original' => $user]);
    }

    /**
     * 新增人脸
     */
    public function addFace($userId, $image, $groupId = null)
    {
        return $this->tencentYoutu->addFace($userId, [$image]);
    }

    /**
     * 删除人脸
     */
    public function deleteFace($userId, $faceId, $groupId = null)
    {
        return $this->tencentYoutu->deleteFace($userId, [$faceId]);
    }

    public function liveVerify(array $images)
    {
        throw new ErrorException('暂不提供此接口');
    }

    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $user['person_id'],
            'group_id' => $user['group_ids'][0],
            'info' => $user['tag'],
        ]);
    }

    protected function mapFaceToObject(array $face)
    {
        return new Face([
            'id' => $face['face_id'],
            'age' => $face['age'],
            'gender' => FaceAttributes::formatGender($face['gender'], [0, 100]),
            'expression' => FaceAttributes::formatExpression($face['expression'], [0, 50, 100]),
            'beauty' => $face['beauty'],
            'glasses' => FaceAttributes::formatGlasses($face['glasses'], [0, 1, 2]),
            'angle' => [
                'yaw' => $face['yaw'],
                'pitch' => $face['pitch'],
                'roll' => $face['roll'],
            ],
        ]);
    }
}