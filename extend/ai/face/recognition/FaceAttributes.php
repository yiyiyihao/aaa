<?php

namespace ai\face\recognition;

class FaceAttributes
{
    const GENDER_FEMALE = 'female';
    const GENDER_MALE = 'male';

    const EXPRESSION_NONE = 'none';
    const EXPRESSION_SMILE = 'smile';
    const EXPRESSION_LAUGH = 'laugh';

    const GLASSES_NONE = 'none';
    const GLASSES_NORMAL = 'normal';
    const GLASSES_DARK = 'dark';
    const GLASSES_OCCLUSION = 'occlusion';

    const EMOTION_ANGRY= 'angry';
    const EMOTION_DISGUST = 'disgust';
    const EMOTION_FEAR = 'fear';
    const EMOTION_HAPPY = 'happy';
    const EMOTION_SADNESS = 'sadness';
    const EMOTION_SURPRISE = 'surprise';
    const EMOTION_NEUTRAL = 'neutral';

    const RACE_YELLOW = 'yellow';
    const RACE_WHITE = 'white';
    const RACE_BLACK = 'black';
    const RACE_ARABS = 'arabs';

    private static $genderMap = [
        'female' => self::GENDER_FEMALE,
        '0' => self::GENDER_FEMALE,
        'male' => self::GENDER_MALE,
        '100' => self::GENDER_MALE,
        '1' => self::GENDER_MALE,
    ];

    private static $expressionMap = [
        'none' => self::EXPRESSION_NONE,
        '0' => self::EXPRESSION_NONE,
        'smile' => self::EXPRESSION_SMILE,
        '50' => self::EXPRESSION_SMILE,
        'laugh' => self::EXPRESSION_LAUGH,
        '100' => self::EXPRESSION_LAUGH,
    ];

    private static $glassesMap = [
        'none' => self::GLASSES_NONE,
        '0' => self::GLASSES_NONE,
        'common' => self::GLASSES_NORMAL,
        'normal' => self::GLASSES_NORMAL,
        '1' => self::GLASSES_NORMAL,
        'sun' => self::GLASSES_DARK,
        'dark' => self::GLASSES_DARK,
        '2' => self::GLASSES_DARK,
        'occlusion' => self::GLASSES_OCCLUSION,
    ];

    private static $emotionMap = [
        'angry' => self::EMOTION_ANGRY,
        'anger' => self::EMOTION_ANGRY,
        'disgust' => self::EMOTION_DISGUST,
        'fear' => self::EMOTION_FEAR,
        'happy' => self::EMOTION_HAPPY,
        'happiness' => self::EMOTION_HAPPY,
        'sadness' => self::EMOTION_SADNESS,
        'sad' => self::EMOTION_SADNESS,
        'surprise' => self::EMOTION_SURPRISE,
        'neutral' => self::EMOTION_NEUTRAL,
    ];

    private static $raceMap = [
        'yellow' => self::RACE_YELLOW,
        'asian' => self::RACE_YELLOW,
        'white' => self::RACE_WHITE,
        'black' => self::RACE_BLACK,
        'arabs' => self::RACE_ARABS,
    ];

    public static function formatGender($value, $arr = [])
    {
        if (!empty($arr)) {
            $value = self::getNearlyFromArray($value, $arr);
        }
        return self::$genderMap[$value];
    }

    public static function formatExpression($value, $arr = [])
    {
        if (!empty($arr)) {
            $value = self::getNearlyFromArray($value, $arr);
        }
        return self::$expressionMap[$value];
    }

    public static function formatGlasses($value, $arr = [])
    {
        if (!empty($arr)) {
            $value = self::getNearlyFromArray($value, $arr);
        }
        return self::$glassesMap[$value];
    }

    public static function formatEmotion($value, $arr = [])
    {
        if (!empty($arr)) {
            $value = self::getNearlyFromArray($value, $arr);
        }
        return self::$emotionMap[$value];
    }

    public static function formatRace($value, $arr = [])
    {
        if (!empty($arr)) {
            $value = self::getNearlyFromArray($value, $arr);
        }
        return self::$raceMap[$value];
    }

    public static function getKeyByMaxValue($arr)
    {
        $key = '';
        $maxValue = max($arr);
        foreach ($arr as $k => $v) {
            if ($v == $maxValue) {
                $key = $k;
                break;
            }
        }
        return $key;
    }

    private static function getNearlyFromArray($value, array $arr)
    {
        $dvalue = [];
        foreach ($arr as $key => $item) {
            $dvalue[$key] = abs($value - $item);
        }
        return min($dvalue);
    }
}
