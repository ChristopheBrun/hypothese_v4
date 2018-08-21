<?php
namespace app\modules\cms\widgets;

use app\modules\cms\models\WebNews;
use yii\bootstrap\Widget;

/**
 * Class LastNews
 *
 * Affichage d'un bloc de texte simple contenant les résumés des dernières actualités du site
 */
class LastNews extends Widget
{
    /** @var int $count Nombre de news à afficher (par défaut : 3) */
    public $count = 3;

    /** @var int Nombre de mots désirés dans le résumé de l'actu */
    public $maxLength = 35;

    /** @var string */
    public $suffix = '...';

    /** @var string $view */
    public $view = 'lastNewsPortlet';

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->view, [
            'models' => WebNews::find()->lastNews($this->count)->all(),
            'maxLength' => $this->maxLength,
            'suffix' => $this->suffix,
        ]);
    }

}