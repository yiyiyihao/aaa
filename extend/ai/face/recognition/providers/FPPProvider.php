<?php

namespace ai\face\recognition\providers;

use ErrorException;
use InvalidArgumentException;
use ai\face\recognition\FPPInterface;

class FPPProvider extends AbstractProvider  implements FPPInterface
{
    private $baseUrl = 'https://api-cn.faceplusplus.com';
    private $detectUrl = 'https://api-cn.faceplusplus.com/facepp/v3/detect';
    private $compareUrl = 'https://api-cn.faceplusplus.com/facepp/v3/compare';
    private $searchUrl = 'https://api-cn.faceplusplus.com/facepp/v3/search';

    private $facesetCreateUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/create';
    private $facesetUpdateUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/update';
    private $facesetGetUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/getdetail';
    private $facesetDeleteUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/delete';

    private $faceAddUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/addface';
    private $faceRemoveUrl = 'https://api-cn.faceplusplus.com/facepp/v3/faceset/removeface';


    public function detect($imageUrl)
    {
        $data = [
            'image_url' => $imageUrl,
            'return_attributes' => 'gender,age,smiling,headpose,facequality,blur,eyestatus,emotion,ethnicity,beauty,mouthstatus,eyegaze,skinstatus',
        ];
        $data = $this->buildFormData($data);

        $res = $this->request($this->detectUrl, $data);
        return $res['faces'][0];
    }

    public function compare($imageUrl1, $imageUrl2)
    {
        $data = [
            'image_url1' => $imageUrl1,
            'image_url2' => $imageUrl2,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->compareUrl, $data);
    }

    public function search($imageUrl, $outerId)
    {
        $data = [
            'image_url' => $imageUrl,
            'outer_id' => $outerId,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->searchUrl, $data);
    }

    public function createFaceset($outerId, $faceTokens = '', $displayName = '', $userData = '', $tags = '')
    {
        $data = [
            'outer_id' => $outerId,
            'face_tokens' => $faceTokens,
            'display_name' => $displayName,
            'user_data' => $userData,
            'tags' => $tags,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->facesetCreateUrl, $data);
    }

    public function updateFaceset($outerId, $displayName = '', $userData = '', $tags = '')
    {
        $data = [
            'outer_id' => $outerId,
            'display_name' => $displayName,
            'user_data' => $userData,
            'tags' => $tags,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->facesetUpdateUrl, $data);
    }

    public function getFaceset($outerId)
    {
        $data = [
            'outer_id' => $outerId,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->facesetGetUrl, $data);
    }

    public function deleteFaceset($outerId)
    {
        $data = [
            'outer_id' => $outerId,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->facesetDeleteUrl, $data);
    }

    public function addFace($outerId, $faceTokens)
    {
        $data = [
            'outer_id' => $outerId,
            'face_tokens' => $faceTokens,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->faceAddUrl, $data);
    }

    public function removeFace($outerId, $faceTokens)
    {
        $data = [
            'outer_id' => $outerId,
            'face_tokens' => $faceTokens,
        ];
        $data = $this->buildFormData($data);

        return $this->request($this->faceRemoveUrl, $data);
    }

    private function buildFormData($data)
    {
        $apiKey = $this->config['api_key'];
        $apiSecret = $this->config['api_secret'];
        if (empty($apiKey) || empty($apiSecret)) {
            throw new InvalidArgumentException('api_key or api_secret must be not empty');
        }
        return array_merge([
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ], $data);
    }

    private function request($url, $data)
    {
        $response = $this->getHttpClient()->post($url, [
            'form_params' => $data
        ]);
        $res = json_decode($response->getBody()->getContents(), true);
        if (isset($res['error_message'])) {
            throw new ErrorException("{$res['error_message']}");
        }
        return $res;
    }
}