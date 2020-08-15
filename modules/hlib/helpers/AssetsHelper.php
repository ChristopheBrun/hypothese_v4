<?php

namespace app\modules\hlib\helpers;

use app\modules\hlib\interfaces\EnabledInterface;
use app\modules\hlib\lib\enums\YesNo;
use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use app\modules\hlib\Hlib;


/**
 * Class AssetsHelper
 * @package app\modules\ia\helpers
 */
class AssetsHelper
{
    /**
     * Renvoie une balise 'bootstrap' adaptée à la clause de tri.
     * On fait la différence entre un tri ascendant ($clause = 'asc'), descendant ($clause = 'desc') ou pas de tri ($clause = '')
     * Les icônes varient selon le type de données à trier : tri alphabétique ($type = 'alphabet'), numérique ($type = 'order'), autre
     *
     * @param string $clause Indique si le tri est fait par ordre croissant ou décroissant.
     * Valeurs attendues : 'asc' ou desc'
     * @param string $type Type de l'icone demandée : tri alphabétique, tri selon un ordre numérique, tri selon des attributs.
     * Valeurs attendues : 'alphabet', 'order', 'attributes'
     * @return string
     */
    public static function bootstrapSortGraphicTag($clause, $type = 'alphabet'): string
    {
        // Si on ne demande aucun tri, inutile d'afficher quoi que ce soit
        if ($clause != 'asc' && $clause != 'desc') {
            return '';
        }

        // L'icône renvoyée dépend du type de tri
        switch ($type) {
            case 'alphabet' :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-alphabet-alt" aria-hidden="true"></span>';
                break;
            case 'order' :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-order-alt" aria-hidden="true"></span>';
                break;
            default :
                $out = $clause == 'asc' ?
                    '<span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>' :
                    '<span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>';
                break;
        }

        return $out;
    }

    /**
     * @param bool $bool
     * @param bool $showFalse
     * @return string
     */
    public static function getImageTagForBoolean($bool, $showFalse = true): string
    {
        $out = "";
        if ($bool) {
            $out = Html::img(Yii::$app->homeUrl . 'images/ok-16.png', ['alt' => HLib::t('labels', 'Yes')]);
        } else {
            if ($showFalse) {
                $out = Html::img(Yii::$app->homeUrl . 'images/ko-16.png', ['alt' => HLib::t('labels', 'No')]);
            }
        }

        return $out;
    }

    /**
     * Renvoie une liste HTML non ordonnée (<ul>) dont les lignes sont calculées avec la fonction $getLabel
     * @param Model[] $models Liste des modèles dont on dont on veut afficher les libellés
     * @param Closure $getLabel Fonction de calcul du libellé. Elle doit prendre un modèle Model en argument et renvoyer une chaine se caractères
     * susceptible d'être placée dans une balise <li>
     * Ex : function($model) { return $model->label; }
     * @return string Liste HTML non ordonnée
     * @todo_cbn Passer ça dans un widget pour plus de souplesse
     */
    public static function getLabelsListFromModels(array $models, Closure $getLabel): string
    {
        if (!$models) {
            return "";
        }

        $out = "<ul>";
        foreach ($models as $model) {
            $out .= "<li>" . $getLabel($model) . "</li>";
        }

        $out .= "</ul>";
        return $out;
    }

    /**
     * @param string $filename
     * @param int $size
     * @param array $tagOptions
     * @return string
     * @throws InvalidConfigException
     */
    public static function imageTagForFile($filename, array $tagOptions = [], $size = 32): string
    {
        if (!file_exists($filename)) {
            return 'x';
        }

        $path = Yii::$app->assetManager->getBundle('app\modules\hlib\assets\HLibAsset')->baseUrl . "/fineFiles/$size/";
        switch (FileHelper::getMimeType($filename)) {
            case 'application/pdf' :
                $path .= "pdf.png";
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' :
                $path .= "word.png";
                break;
            case 'image/jpeg' :
                $path .= "jpg.png";
                break;
            case 'image/gif' :
                $path .= "gif.png";
                break;
            case 'image/png' :
                $path .= "png.png";
                break;
            case 'text/plain' :
                $path .= "text.png";
                break;
            default :
                $path .= "default.png";
        }

        return Html::img($path, $tagOptions);
    }

    /**
     * @param string $fileType
     * @param int $size
     * @param array $tagOptions
     * @return string
     * @throws InvalidConfigException
     */
    public static function imageTagForFileType($fileType, array $tagOptions = [], $size = 32): string
    {
        $path = Yii::$app->assetManager->getBundle('app\modules\hlib\assets\HLibAsset')->baseUrl . "/fineFiles/$size/";
        switch ($fileType) {
            case 'pdf' :
                $path .= "pdf.png";
                break;
            case 'word' :
                $path .= "word.png";
                break;
            case 'jpeg' :
                $path .= "jpg.png";
                break;
            case 'gif' :
                $path .= "gif.png";
                break;
            case 'png' :
                $path .= "png.png";
                break;
            case 'txt' :
                $path .= "text.png";
                break;
            case 'csv' :
            case 'xls' :
                $path .= "excel.png";
                break;
            default :
                $path .= "default.png";
        }

        return Html::img($path, $tagOptions);
    }

    /**
     * Renvoie un tableau permettant de configurer une ligne de séparation dans une DetailView
     *
     * @return array
     */
    public static function detailViewSeparator(): array
    {
        return [
            'attribute' => '', 'value' => '', 'contentOptions' => ['class' => 'separator']
        ];
    }

    /**
     * Renvoie un tableau permettant de configurer une ligne avec une case à cocher 'activé' dans une DetailView
     *
     * @param EnabledInterface $model Doit avoir un attribut public $enabled
     * @return array
     */
    public static function detailViewEnabled(EnabledInterface $model): array
    {
        return [
            'attribute' => 'enabled',
            'format' => 'html',
            'value' => static::getImageTagForBoolean($model->isEnabled()),
        ];
    }

    /**
     * Renvoie un tableau permettant de configurer une ligne avec une case à cocher 'activé' dans une DetailView
     *
     * @return array
     */
    public static function gridViewEnabled(): array
    {
        return [
            'attribute' => 'enabled',
            'value' => function (EnabledInterface $model) {
                return AssetsHelper::getImageTagForBoolean($model->isEnabled());
            },
            'format' => 'html',
            'filter' => YesNo::getList(),
        ];
    }

}