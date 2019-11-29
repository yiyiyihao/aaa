<?php

namespace ai\face\recognition\providers;

use ErrorException;
use InvalidArgumentException;
use ai\face\recognition\TencentYoutuInterface;

class TencentYoutuProvider extends AbstractProvider  implements TencentYoutuInterface
{
    private $baseUrl = 'http://api.youtu.qq.com';
    private $detectUrl = 'http://api.youtu.qq.com/youtu/api/detectface';
    private $compareUrl = 'http://api.youtu.qq.com/youtu/api/facecompare';
    private $searchUrl = 'http://api.youtu.qq.com/youtu/api/multifaceidentify';

    private $personAddUrl = 'http://api.youtu.qq.com/youtu/api/newperson';
    private $personUpdateUrl = 'http://api.youtu.qq.com/youtu/api/setinfo';
    private $personDeleteUrl = 'http://api.youtu.qq.com/youtu/api/delperson';
    private $personGetUrl = 'http://api.youtu.qq.com/youtu/api/getinfo';

    private $faceAddUrl = 'http://api.youtu.qq.com/youtu/api/addface';
    private $faceDeleteUrl = 'http://api.youtu.qq.com/youtu/api/delface';

    public function detect($url, $isBigFace = 0)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'url' => $url,
            'mode' => $isBigFace,
        ];

        $res = $this->request($this->detectUrl, $data);
        return $res['face'][0];
    }

    public function compare($urlA, $urlB)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'urlA' => $urlA,
            'urlB' => $urlB,
        ];

        return $this->request($this->compareUrl, $data);
    }

    public function search($url, $groupIds, $topn = 5, $minSize=40)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'url' => $url,
            'group_ids' => $groupIds,
            'topn' => $topn,
            'min_size' => $minSize,
        ];

        return $this->request($this->searchUrl, $data);
    }

    public function addPerson($url, $personId, array $groupIds, $personName = '', $tag = '')
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'url' => $url,
            'person_id' => $personId,
            'group_ids' => $groupIds,
            'person_name' => $personName,
            'tag' => $tag,
        ];

        return $this->request($this->personAddUrl, $data);
    }

    public function updatePerson($personId, $personName, $tag)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'person_id' => $personId,
            'person_name' => $personName,
            'tag' => $tag,
        ];

        return $this->request($this->personUpdateUrl, $data);
    }

    public function deletePerson($personId)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'person_id' => $personId,
        ];

        return $this->request($this->personDeleteUrl, $data);
    }

    public function getPerson($personId)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'person_id' => $personId,
        ];

        return $this->request($this->personGetUrl, $data);
    }

    public function addFace($personId, array $urls, $tag = '')
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'person_id' => $personId,
            'urls' => $urls,
            'tag' => $tag,
        ];

        return $this->request($this->faceAddUrl, $data);
    }

    public function deleteFace($personId, array $faceIds)
    {
        $data = [
            'app_id' => $this->config['app_id'],
            'person_id' => $personId,
            'face_ids' => $faceIds,
        ];

        return $this->request($this->faceDeleteUrl, $data);
    }

    private function makeAuthSign($expired = 0)
    {
        $sign = $this->cache()->get('tencent-youtu_sign');
        if (empty($sign)) {
            $appid  =  $this->config['app_id'];
            $secretId = $this->config['secret_id'];
            $secretKey = $this->config['secret_key'];
            $userId = $this->config['user_id'];
            if (empty($secretId) || empty($secretKey)) {
                throw new InvalidArgumentException('secret_id or secret_key must be not empty');
            }

            $now = time();
            $rdm = rand();
            $expired = $expired <= $now ? $now + 86400 : $expired;
            $plainText = 'a=' . $appid . '&k=' . $secretId . '&e=' . $expired . '&t=' . $now . '&r=' . $rdm . '&u=' . $userId;
            $bin = hash_hmac("SHA1", $plainText, $secretKey, true);
            $bin = $bin . $plainText;
            $sign = base64_encode($bin);

            $this->cache()->set('tencent-youtu_sign', $sign, $expired);
        }

        return $sign;
    }

    private function request($url, $data)
    {
        $response = $this->getHttpClient()->post($url, [
            'headers' => [
                'Authorization' => $this->makeAuthSign(),
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        ]);
        $res = json_decode($response->getBody()->getContents(), true);
        if ($res['errorcode'] > 0) {
            throw new ErrorException("{$res['errormsg']}");
        }
        return $res;
    }
}