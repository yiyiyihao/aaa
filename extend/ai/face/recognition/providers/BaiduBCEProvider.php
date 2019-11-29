<?php

namespace ai\face\recognition\providers;

use ErrorException;
use InvalidArgumentException;
use ai\face\recognition\BaiduBCEInterface;

class BaiduBCEProvider extends AbstractProvider implements BaiduBCEInterface
{
    private $baseUrl = 'https://aip.baidubce.com';
    private $detectUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/detect';
    private $compareUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/match';
    private $searchUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/search';

    private $userAddUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceset/user/add';
    private $userUpdateUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceset/user/update';
    private $userDeleteUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceset/user/delete';
    private $userGetUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceset/user/get';

    private $faceDeleteUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceset/face/delete';

    private $faceVerifyUrl = 'https://aip.baidubce.com/rest/2.0/face/v3/faceverify';

    public function detect($image, $imageType, $options = [])
    {
        $data = [
            'image' => $image,
            'image_type' => $imageType,
            'face_field' => 'age,beauty,expression,face_shape,gender,glasses,race,quality,eye_status,emotion,face_type',
        ];
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }

        $res = $this->request($this->detectUrl, $data);
        return $res['face_list'][0];
    }

    public function compare(array $images)
    {
        return $this->request($this->compareUrl, $images);
    }

    public function search($image, $imageType, $groupIdList, $options = [])
    {
        $data = [
            'image' => $image,
            'image_type' => $imageType,
            'group_id_list' => implode(',', array_slice($groupIdList, 0, 10)),
        ];
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }

        return $this->request($this->searchUrl, $data);
    }

    public function addUser($image, $imageType, $groupId, $userId, $options = [])
    {
        $data = [
            'image' => $image,
            'image_type' => $imageType,
            'group_id' => $groupId,
            'user_id' => $userId,
        ];
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }

        return $this->request($this->userAddUrl, $data);
    }

    public function updateUser($image, $imageType, $groupId, $userId, $options = [])
    {
        $data = [
            'image' => $image,
            'image_type' => $imageType,
            'group_id' => $groupId,
            'user_id' => $userId,
        ];
        if (!empty($options)) {
            $data = array_merge($data, $options);
        }

        return $this->request($this->userUpdateUrl, $data);
    }

    public function deleteUser($groupId, $userId)
    {
        $data = [
            'group_id' => $groupId,
            'user_id' => $userId,
        ];

        $res = $this->request($this->userDeleteUrl, $data);

        return $res;
    }

    public function getUser($userId, $groupId)
    {
        $data = [
            'user_id' => $userId,
            'group_id' => $groupId,
        ];

        $res = $this->request($this->userGetUrl, $data);

        $user = $res['user_list'][0];
        $user['user_id'] = $userId;

        return $user;
    }

    public function deleteFace($userId, $groupId, $faceToken)
    {
        $data = [
            'user_id' => $userId,
            'group_id' => $groupId,
            'face_token' => $faceToken,
        ];

        $res = $this->request($this->faceDeleteUrl, $data);

        return $res;
    }

    public function faceVerify(array $images)
    {
        return $this->request($this->faceVerifyUrl, $images);
    }

    private function getAccessToken()
    {
        $accessToken = $this->cache()->get('baidubce_access_token');
        if (empty($accessToken)) {
            if (empty($this->config['api_key']) || empty($this->config['secret_key'])) {
                throw new InvalidArgumentException('api_key or secret_key must be not empty');
            }
            $url = $this->baseUrl . '/oauth/2.0/token';
            $response = $this->getHttpClient()->get($url, [
                'query' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->config['api_key'],
                    'client_secret' => $this->config['secret_key'],
                ],
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
            if (isset($res['access_token']) && isset($res['expires_in'])) {
                $accessToken = $res['access_token'];
                $this->cache()->set('baidubce_access_token', $accessToken, $res['expires_in']);
            } else {
                throw new ErrorException("{$res['error_description']}[{$res['error']}]");
            }
        }
        return $accessToken;
    }

    private function request($url, $data)
    {
        $response = $this->getHttpClient()->post($url . '?access_token=' . $this->getAccessToken(), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);
        $res = json_decode($response->getBody()->getContents(), true);
        if ($res['error_code'] > 0) {
            // Access Token错误
            if ($res['error_code'] == 110) {
                $this->cache()->rm('baidubce_access_token');
                return $this->request($url, $data);
            } else {
                throw new InvalidArgumentException("{$res['error_code']}[{$res['error_msg']}]");
            }
        }
        return $res['result'];
    }
}