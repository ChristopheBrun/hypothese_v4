<?php

namespace app\modules\cms\widgets;
use app\modules\cms\models\BaseTag;
use yii\bootstrap\Widget;


/**
 * Class BaseTagForm
 * @package app\modules\cms\widgets
 */
class BaseTagForm extends Widget
{
    /** @var BaseTag $model  */
    public $model;

    /** @var boolean  */
    public $asNestedForm = false;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('baseTagForm', [
            'model' => $this->model,
            'asNestedForm' => $this->asNestedForm,
        ]);
    }

}