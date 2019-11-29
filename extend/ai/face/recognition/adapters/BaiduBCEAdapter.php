<?php

namespace ai\face\recognition\adapters;

use ErrorException;
use ai\face\recognition\BaiduBCEInterface;
use ai\face\recognition\Face;
use ai\face\recognition\FaceAttributes;
use ai\face\recognition\FaceRecognitionInterface;
use ai\face\recognition\User;

class BaiduBCEAdapter implements FaceRecognitionInterface
{
    protected $baiduBCE;

    public function __construct(BaiduBCEInterface $baiduBCE)
    {
        $this->baiduBCE = $baiduBCE;
    }


    /**
     * 人脸检测
     */
    public function detect($image)
    {
        $face = $this->baiduBCE->detect($image, 'URL');

        return $this->mapFaceToObject($face)->merge(['original' => $face]);
    }

    /**
     * 人脸对比
     */
    public function compare($imageA, $imageB)
    {
        $images = [
            [
                'image' => $imageA,
                'image_type' => 'URL',
            ],
            [
                'image' => $imageB,
                'image_type' => 'URL',
            ]
        ];
        return $this->baiduBCE->compare($images);
    }

    /**
     * 人脸搜索
     */
    public function search($image, array $groupIds)
    {
        return $this->baiduBCE->search($image, 'URL', $groupIds);
    }

    /**
     * 新增用户
     */
    public function addUser($image, $userId, $groupId, $name = '', $gender = 0)
    {
        return $this->baiduBCE->addUser($image, 'URL', $groupId, $userId);
    }

    /**
     * 更新用户信息
     */
    public function updateUser($userId, $name)
    {
        throw new ErrorException('暂不提供此接口');
    }

    /**
     * 删除用户
     */
    public function deleteUser($userId, $groupId = '')
    {
        return $this->baiduBCE->deleteUser($groupId, $userId);
    }

    /**
     * 查询用户信息
     */
    public function getUser($userId, $groupId = '')
    {
        $user =  $this->baiduBCE->getUser($userId, $groupId);

        return $this->mapUserToObject($user)->merge(['original' => $user]);
    }

    /**
     * 新增人脸
     */
    public function addFace($userId, $image, $groupId = '')
    {
        return $this->addUser($image, $userId, $groupId);
    }

    /**
     * 删除人脸
     */
    public function deleteFace($userId, $faceId, $groupId = '')
    {
        return $this->baiduBCE->deleteFace($userId, $groupId, $faceId);
    }

    /**
     * 在线活体检测
     */
    public function liveVerify(array $images)
    {
        $params = [];
        foreach ($images as $image) {
            $params[] = [
                'image' => $image,
                'image_type' => 'URL',
            ];
        }
        return $this->baiduBCE->faceVerify($params);
    }

    protected function mapUserToObject(array $user)
    {
        return new User([
            'id' => $user['user_id'],
            'group_id' => $user['group_id'],
            'info' => $user['user_info'],
        ]);
    }

    protected function mapFaceToObject(array $face)
    {
        return new Face([
            'id' => $face['face_token'],
            'age' => $face['age'],
            'gender' => FaceAttributes::formatGender($face['gender']['type']),
            'expression' => FaceAttributes::formatExpression($face['expression']['type']),
            'emotion' => FaceAttributes::formatEmotion($face['emotion']['type']),
            'beauty' => $face['beauty'],
            'glasses' => FaceAttributes::formatGlasses($face['glasses']['type']),
            'race' => FaceAttributes::formatRace($face['race']['type']),
            'angle' => $face['angle'],
        ]);
    }
}