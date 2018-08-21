<?php

namespace app\modules\hlib\widgets;

use app\modules\hlib\helpers\hAssets;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * Class GridListHeader
 *
 * Propriétés du widget :
 * - sortAction : (string) s'il y a des colonnes triables, chemin de l'action à appeler pour le tri. Facultatif.
 *  - columns : liste des colonnes à afficher. Il doit au moins y en avoir une. Les colonnes sont repérés par des clés dont les noms sont
 * arbitraires. Elles servent juste à clarifier le code pour le développeur. Chaque colonne possède les attributs suivants : [
 *      - label : (string) Libellé de la colonne
 *      - width : (int) nombre de "colonnes Bootstrap" à occuper
 *      - cssClass : (string) classes supplémentaires en complément des css bootstrap qui seront calculés. Facultatif.
 * ]
 *
 * Par défaut, les colonnes sont de simples libellés. Si une colonne est triable, les attributs suivants doivent aussi être renseignés pour la rendre
 * utilisable :
 *      - orderBy : (string) nom de la colonne sur laquelle on fait le tri. Sera passé en paramètre à Url::to() pour l'appel de l'action de tri.
 *      - sortIcon : (string) type de l'icône Bootstrap qui illustre le tri en cours sur cette colonne. Facultatif.
 *
 * @package app\modules\hlib\widgets
 */
class GridListHeader extends Widget
{
    /** @var string */
    public $sortAction;

    /** @var string */
    public $sortClausesSessionKey = '';

    /** @var array */
    public $columns;

    /**
     *
     */
    public function init()
    {
        parent::init();

        $columns = [];
        foreach ($this->columns as $columnConfiguration) {
            if (!$this->sortAction) {
                unset($columnConfiguration['orderBy']);
            }

            if (!isset($columnConfiguration['orderBy'])) {
                $columnConfiguration['columnType'] = 'simple';
            }
            else {
                // Tri demandé
                $columnConfiguration['columnType'] = 'sort';
                if($this->sortClausesSessionKey) {
                    if (!isset($columnConfiguration['iconType'])) {
                        $columnConfiguration['iconType'] = 'alphabet';
                    }
                }
            }

            if (!isset($columnConfiguration['cssClass'])) {
                $columnConfiguration['cssClass'] = '';
            }

            $columns[] = $columnConfiguration;
        }

        $this->columns = $columns;
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('gridListHeader', ['columns' => $this->columns]);
    }

    /**
     * @param array $column
     * @return string
     * @throws InvalidConfigException
     */
    public function renderColumnContent(array $column)
    {
        switch ($column['columnType']) {
            case 'simple' :
                $out = $column['label'];
                break;
            case 'sort' :
                $sortClauses = Yii::$app->session->get($this->sortClausesSessionKey);
                $out =
                    Html::a($column['label'], Url::to([$this->sortAction, 'orderBy' => $column['orderBy']])) .
                    '&nbsp;&nbsp;' .
                    hAssets::bootstrapSortGraphicTag(ArrayHelper::getValue($sortClauses,  $column['orderBy']), $column['iconType']);
                break;
            default :
                throw new InvalidConfigException('Type de colonne inconnu : ' . $column['columnType']);
        }

        return $out;
    }

}