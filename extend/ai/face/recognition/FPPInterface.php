<?php

namespace ai\face\recognition;

interface FPPInterface
{
    public function detect($imageUrl);

    public function compare($imageUrl1, $imageUrl2);

    public function search($imageUrl, $outerId);

    public function createFaceset($outerId, $faceTokens, $displayName);

    public function updateFaceset($outerId, $displayName);

    public function getFaceset($outerId);

    public function deleteFaceset($outerId);

    public function addFace($outerId, $faceTokens);

    public function removeFace($outerId, $faceTokens);
}