<?php

namespace app\modules\hlib\helpers;

use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\i18n\Formatter;


/**
 * Class ModelHelper
 * @package ia\models
 */
class ModelHelper
{
    /**
     * @param string $modelClass
     * @param ActiveRecord[] $existingModels
     * @param Closure|null $skipIf
     * @return array
     * @throws InvalidConfigException
     * @todo_cbn tester & documenter cette méthode
     */
    public static function createMultipleFromPost($modelClass, array $existingModels = null, Closure $skipIf = null)
    {
        /** @var Model $model */
        $model = new $modelClass;
        $formName = $model->formName();

        $addToPreviousModelsList = false;
        if ($existingModels) {
            $keys = array_keys(ArrayHelper::map($existingModels, 'id', 'id'));
            $existingModels = array_combine($keys, $existingModels);
            $addToPreviousModelsList = true;
        }

        $models = [];
        $post = Yii::$app->request->post($formName);
        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if ($addToPreviousModelsList) {
                    if (isset($item['id']) && !empty($item['id']) && isset($existingModels[$item['id']])) {
                        $newModel = $existingModels[$item['id']];
                    } else {
                        $newModel = new $modelClass($item);
                    }
                } else {
                    $newModel = new $modelClass($item);
                }

                if ($skipIf && !$skipIf($newModel)) {
                    $models[] = $model;
                }
            }
        }

        return $models;
    }

    /**
     * Renvoie la liste des attributs du modèle qui ont été modifiés, ainsi que la nouvelle valeur pour chacun d'eux
     *
     * NB : contrairement à la méthode $model->getDirtyAttributes(), la comparaison entre l'ancienne et la nouvelle valeur de chaque
     * attribut se fait avec l'opérateur == (et non avec l'opérateur ===).
     * Ex : une clé étrangère fk_id vaudra par exemple 9 (int) dans $model->$oldAttributes mais "9" (string) dans $model->$attributes
     * après le load() des valeurs du formulaire, parce que le formulaire renvoie toujours des string. Un appel à $model->getDirtyAttributes()
     * renverra alors au moins ce champ fk_id alors qu'il n'a pas réellement changé.
     * La méthode getChangedAttributes() fait donc en sorte de sorte que les attributs chargés depuis un formulaire ne soient pas marqués
     * comme changés dans ce cas.
     *
     * @param ActiveRecord $model
     * @param array $skipAttributes Liste des attributs à ignorer dans le calcul
     * @return array
     */
    public static function getChangedAttributes(ActiveRecord $model, array $skipAttributes = [])
    {
        $out = [];
        $oldAttributes = $model->getOldAttributes();
        foreach ($model->getAttributes() as $name => $value) {
            if (!array_key_exists($name, $oldAttributes) || $value != $oldAttributes[$name]) {
                if (in_array($name, $skipAttributes)) {
                    continue;
                }

                $out[$name] = $value;
            }
        }

        return $out;
    }

    /**
     * Cette méthode tente de trouver un champ ou une méthode permettant d'attribuer un libellé au modèle passé en argument
     *
     * @param ActiveRecord $model
     * @param string $default
     * @return mixed|string
     */
    public static function retrieveDefaultLabel(ActiveRecord $model, $default = '-')
    {
        if ($model->hasAttribute('label')) {
            return $model->label;
        }

        if ($model->hasAttribute('title')) {
            return $model->title;
        }

        if ($model->hasAttribute('name')) {
            return $model->name;
        }

        if ($model->hasMethod('formatName')) {
            return $model->formatName();
        }

        return $default;
    }

    /**
     * Convertit les dates au format court associé à la localisation de l'application
     * @see http://www.yiiframework.com/doc-2.0/guide-output-formatting.html#date-and-time
     *
     * @param ActiveRecord $model
     * @param array $attributes
     * @return ActiveRecord
     * @throws InvalidConfigException
     */
    public static function setShortDates(ActiveRecord $model, array $attributes)
    {
        $fmt = new Formatter(['dateFormat' => 'short']);
        foreach ($attributes as $attr) {
            if ($model->$attr) {
                $model->$attr = $fmt->asDate($model->$attr);
            }
        }

        return $model;
    }

    /**
     * Renvoie un message d'erreur préformatté quand on veut notifier une erreur sur la méthode d'un modèle
     *
     * @param string $methodName
     * @param Model $model
     * @return string
     */
    public static function errMsg($methodName, Model $model)
    {
        return "!$methodName() : " . print_r([$model->getErrors(), $model->toArray()], true);
    }

}