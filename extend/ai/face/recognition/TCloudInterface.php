<?php

namespace ai\face\recognition;

interface TCloudInterface
{
    public function detect($url);

    public function compare($urlA, $urlB);

    public function search($url, $groupIds, $maxFaceNum = 1, $minFaceSize = 80, $maxPersonNum = 1);

    public function createGroup($groupId, $groupName);

    public function createPerson($url, $personId, array $groupIds, $personName = '', $gender = 0);

    public function modifyPerson($personId, $personName);

    public function deletePerson($personId);

    public function getPerson($personId);

    public function createFace($personId, array $urls);

    public function deleteFace($personId, array $faceIds);

    public function detectLiveFace($image);
}