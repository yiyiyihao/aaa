<?php

namespace ai\face\recognition\adapters;

use ErrorException;
use ai\face\recognition\Face;
use ai\face\recognition\FaceAttributes;
use ai\face\recognition\FaceRecognitionInterface;
use ai\face\recognition\FPPInterface;

class FPPAdapter implements FaceRecognitionInterface
{
    protected $fPP;

    public function __construct(FPPInterface $fPP)
    {
        $this->fPP = $fPP;
    }

    /**
     * 人脸检测
     */
    public function detect($image)
    {
        $face = $this->fPP->detect($image);
        return $this->mapFaceToObject($face)->merge(['original' => $face]);
    }

    /**
     * 人脸对比
     */
    public function compare($imageA, $imageB)
    {
        return $this->fPP->compare($imageA, $imageB);
    }

    /**
     * 人脸搜索
     */
    public function search($image, array $groupIds)
    {
        throw new ErrorException('暂不提供此接口');
        // return $this->fPP->search($image, $groupIds[0]);
    }

    /**
     * 新增用户
     */
    public function addUser($image, $userId, $groupId, $name = null, $gender = null)
    {
        throw new ErrorException('暂不提供此接口');
        // $result = $this->detect($image);
        // return $this->fPP->createFaceset($groupId, $result['faces'][0]['face_token'], $name);
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
    public function deleteUser($userId, $groupId = null)
    {
        throw new ErrorException('暂不提供此接口');
    }

    /**
     * 查询用户信息
     */
    public function getUser($userId, $groupId = null)
    {
        throw new ErrorException('暂不提供此接口');
    }

    /**
     * 新增人脸
     */
    public function addFace($userId, $image, $groupId = null)
    {
        throw new ErrorException('暂不提供此接口');
    }

    /**
     * 删除人脸
     */
    public function deleteFace($userId, $faceId, $groupId = null)
    {
        throw new ErrorException('暂不提供此接口');
    }


    public function liveVerify(array $images)
    {
        throw new ErrorException('暂不提供此接口');
    }

    protected function mapFaceToObject(array $face)
    {
        return new Face([
            'id' => $face['face_token'],
            'age' => $face['attributes']['age']['value'],
            'gender' => FaceAttributes::formatGender(strtolower($face['attributes']['gender']['value'])),
            'expression' => $face['attributes']['smile']['value'] < $face['attributes']['smile']['threshold'] ? 'none' : FaceAttributes::formatExpression($face['attributes']['smile']['value'], [50, 100]),
            'emotion' => FaceAttributes::formatEmotion(FaceAttributes::getKeyByMaxValue($face['attributes']['emotion'])),
            'beauty' => $face['attributes']['beauty'][strtolower($face['attributes']['gender']['value']) . '_score'],
            'glasses' => $this->getGlasses($face['attributes']['eyestatus']),
            'race' => FaceAttributes::formatRace(strtolower($face['attributes']['ethnicity']['value'])),
            'angle' => [
                'yaw' => $face['attributes']['headpose']['yaw_angle'],
                'pitch' => $face['attributes']['headpose']['pitch_angle'],
                'roll' => $face['attributes']['headpose']['roll_angle'],
            ],
        ]);
    }

    private function getGlasses($eyestatus)
    {
        $leftEyeStatus = FaceAttributes::getKeyByMaxValue($eyestatus['left_eye_status']);
        $rightEyeStatus = FaceAttributes::getKeyByMaxValue($eyestatus['right_eye_status']);

        $glasses = 'none';
        if (strpos($leftEyeStatus, 'no_glass') !== false && strpos($rightEyeStatus, 'no_glass') !== false) {
            $glasses = 'none';
        } elseif (strpos($leftEyeStatus, 'normal_glass') !== false && strpos($rightEyeStatus, 'normal_glass') !== false) {
            $glasses = 'normal';
        } elseif (strpos($leftEyeStatus, 'dark_glasses') !== false && strpos($rightEyeStatus, 'dark_glasses') !== false) {
            $glasses = 'dark';
        } else {
            $glasses = 'occlusion';
        }

        return FaceAttributes::formatGlasses($glasses);
    }
}