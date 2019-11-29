<?php

namespace ai\face\recognition\providers;

use ai\face\recognition\TCloudInterface;
use TencentCloud\Iai\V20180301\IaiClient;
use TencentCloud\Common\Credential;
use TencentCloud\Iai\V20180301\Models\CreateFaceRequest;
use TencentCloud\Iai\V20180301\Models\CreateGroupRequest;
use TencentCloud\Iai\V20180301\Models\CreatePersonRequest;
use TencentCloud\Iai\V20180301\Models\DeleteFaceRequest;
use TencentCloud\Iai\V20180301\Models\DeletePersonRequest;
use TencentCloud\Iai\V20180301\Models\DetectFaceRequest;
use TencentCloud\Iai\V20180301\Models\DetectLiveFaceRequest;
use TencentCloud\Iai\V20180301\Models\CompareFaceRequest;
use TencentCloud\Iai\V20180301\Models\GetPersonBaseInfoRequest;
use TencentCloud\Iai\V20180301\Models\ModifyPersonBaseInfoRequest;
use TencentCloud\Iai\V20180301\Models\SearchFacesRequest;

class TCloudProvider extends AbstractProvider implements TCloudInterface
{
    public function detect($url)
    {
        $req = new DetectFaceRequest();
        $req->Url = $url;
        $req->NeedFaceAttributes = 1;
        $req->NeedQualityDetection = 1;
        $res = $this->getSDKClient()->DetectFace($req);
        return json_decode($res->toJsonString(), true)['FaceInfos'][0];
    }

    public function compare($urlA, $urlB)
    {
        $req = new CompareFaceRequest();
        $req->UrlA = $urlA;
        $req->UrlB = $urlB;
        $res = $this->getSDKClient()->CompareFace($req);
        return $res;
    }

    public function search($url, $groupIds, $maxFaceNum = 1, $minFaceSize = 80, $maxPersonNum = 1)
    {
        $req = new SearchFacesRequest();
        $req->Url = $url;
        $req->GroupIds = $groupIds;
        $req->MaxFaceNum = $maxFaceNum;
        $req->MinFaceSize = $minFaceSize;
        $req->MaxPersonNum = $maxPersonNum;
        $res = $this->getSDKClient()->SearchFaces($req);
        return $res;
    }

    public function createGroup($groupId, $groupName)
    {
        $req = new CreateGroupRequest();
        $req->GroupId = $groupId;
        $req->GroupName = $groupId;
        $res = $this->getSDKClient()->CreateGroup($req);
        return $res;
    }

    public function createPerson($url, $personId, array $groupIds, $personName = '', $gender = 0)
    {
        $req = new CreatePersonRequest();
        $req->GroupId = $groupIds[0];
        $req->PersonName = $personName;
        $req->PersonId = $personId;
        $req->Url = $url;
        $req->Gender = $gender;
        $res = $this->getSDKClient()->CreatePerson($req);
        return $res;
    }

    public function modifyPerson($personId, $personName)
    {
        $req = new ModifyPersonBaseInfoRequest();
        $req->PersonId = $personId;
        $req->PersonName = $personName;
        $res = $this->getSDKClient()->ModifyPersonBaseInfo($req);
        return $res;
    }

    public function deletePerson($personId)
    {
        $req = new DeletePersonRequest();
        $req->PersonId = $personId;
        $res = $this->getSDKClient()->DeletePerson($req);
        return $res;
    }

    public function getPerson($personId)
    {
        $req = new GetPersonBaseInfoRequest();
        $req->PersonId = $personId;

        $res = $this->getSDKClient()->GetPersonBaseInfo($req);

        return $res;
    }

    public function createFace($personId, array $urls)
    {
        $req = new CreateFaceRequest();
        $req->PersonId = $personId;
        $req->Urls = $urls;
        $res = $this->getSDKClient()->CreateFace($req);
        return $res;
    }

    public function deleteFace($personId, array $faceIds)
    {
        $req = new DeleteFaceRequest();
        $req->PersonId = $personId;
        $req->FaceIds = $faceIds;
        $res = $this->getSDKClient()->DeleteFace($req);
        return $res;
    }

    public function detectLiveFace($image)
    {
        $req = new DetectLiveFaceRequest();
        $req->Url = $image;
        $res = $this->getSDKClient()->DetectLiveFace($req);
        return json_decode($res->toJsonString(), true);
    }

    private function getSDKClient()
    {
        $credential = new Credential($this->config['secret_id'], $this->config['secret_key']);
        $client = new IaiClient($credential, '');
        return $client;
    }
}