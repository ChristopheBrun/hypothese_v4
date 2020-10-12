<?php
//

namespace app\models;

use app\components\CharStats;
use app\modules\hlib\helpers\hString;
use yii\base\Model;

/**
 * Class TraitementTexte
 * @package app\models
 */
class TraitementTexte extends Model
{
    /** @var string */
    public $text;

    /** @var string Texte nettoyé. Cet attribut est automatiquement renseigné à partir de $text lors du load() */
    private $sanitizedText;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            // on ne touche pas au texte initial mais on copiera la version nettoyée dans sanitizedText
            [['text'],
                'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'text' => "Entrez ici le texte à traiter",
        ];
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $out = parent::load($data, $formName);
        // On copie le texte à traiter dans $sanitizedText en le nettoyant
        // Le texte d'origine est conservé intact dans $text
        $this->setSanitizedText($this->text);
        return $out;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return mixed
     */
    public function getSanitizedText()
    {
        return $this->sanitizedText;
    }

    /**
     * @param string $text
     * @return mixed
     */
    public function setSanitizedText(string $text)
    {
        $this->sanitizedText = hString::sanitize($text);
        return $this;
    }

    /**
     * @return CharStats
     */
    public function getCharStats()
    {
        $out = new CharStats();
        $data = $this->getSanitizedText();
        $out->nbChars = mb_strlen($data);
        $res = preg_match_all('/(\p{L})/', $data, $matches);
        $out->nbLetters = $res !== false ? count($matches[1]) : 0;
        $res = preg_match_all('/(\d)/', $data, $matches);
        $out->nbDigits = $res !== false ? count($matches[1]) : 0;
        $res = preg_match_all('/(\s)/', $data, $matches);
        $out->nbSeparators = $res !== false ? count($matches[1]) : 0;
       $res = preg_match_all('/(\p{P})/', $data, $matches);
        $out->nbPunctuation = $res !== false ? count($matches[1]) : 0;
        return $out;
    }
}