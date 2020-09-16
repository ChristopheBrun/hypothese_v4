<?php
//

namespace app\models;

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

    /** @var string */
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
     *
     */
    public function tagText()
    {
        $this->tagAtoms();
    }

    /**
     *
     */
    private function tagAtoms()
    {
//        foreach ($this->sanitizedText as $i => $char) {
////            if()
//        }
    }

    /**
     * @return mixed
     */
    public function getSanitizedText()
    {
        return $this->sanitizedText;
    }

    /**
     * @return mixed
     */
    public function setSanitizedText($text)
    {
        $this->sanitizedText = hString::sanitize($text);
        return $this;
    }
}