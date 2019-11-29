<?php

namespace ai\face\recognition;

interface TencentYoutuInterface
{
    public function detect($url, $isBigFace);

    public function compare($urlA, $urlB);

    public function search($url, $groupIds, $topn = 5, $minSize=40);

    public function addPerson($url, $personId, array $groupIds, $personName = '');

    public function updatePerson($personId, $personName);

    public function deletePerson($personId);

    public function getPerson($personId);

    public function addFace($personId, array $urls);

    public function deleteFace($personId, array $faceIds);
}