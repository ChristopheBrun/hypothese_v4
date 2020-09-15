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
    public $text;

    private $sanitizedText;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            // string
            [['sanitizedText'],
                'filter', 'filter' => [hString::class, 'sanitize']],
            // on ne touche pas au texte initial
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
        // On copie le texte à traiter dans $sanitizedText pour ne nettoyer que ce dernier attribut
        // Le texte d'origine est conservé intact dans $text
        $this->sanitizedText = $this->text;
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
        foreach ($this->sanitizedText as $i => $char) {
//            if()
        }
    }

    /**
     * @return mixed
     */
    public function getSanitizedText()
    {
        return $this->sanitizedText;
    }
}