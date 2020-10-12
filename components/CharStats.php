<?php


namespace app\components;

use Exception;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class CharStats
 * @package app\dataStructures
 */
class CharStats extends Component
{
    /** @var int */
    public $nbChars = 0;
    /** @var int */
    public $nbLetters = 0;
    /** @var int */
    public $nbDigits = 0;
    /** @var int */
    public $nbSeparators = 0;
    /** @var int */
    public $nbPunctuation = 0;

    /**
     * @return string[]
     */
    public static function attributeLabels()
    {
        return [
            'nbChars' => 'Nb de caractères',
            'nbLetters' => 'Nb de lettres',
            'nbDigits' => 'Nb de chiffres',
            'nbSeparators' => 'Nb de séparateurs',
            'nbPunctuation' => 'Nb de ponctuations',
        ];
    }

    /**
     * @param string $attr
     * @return mixed|null
     * @throws Exception
     */
    public static function attributeLabel(string $attr)
    {
        return ArrayHelper::getValue(static::attributeLabels(), $attr, '?');
    }
}