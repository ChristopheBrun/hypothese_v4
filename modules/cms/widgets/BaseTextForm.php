<?php

namespace app\modules\cms\widgets;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\BaseText;
use yii\bootstrap\Widget;


/**
 * Class BaseTextForm
 * @package app\modules\cms\widgets
 */
class BaseTextForm extends Widget
{
    /** @var BaseText $model  */
    public $model;

    /** @var boolean  */
    public $asNestedForm = false;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('baseTextForm', [
            'model' => $this->model,
            'basePages' => BasePage::find()->orderByCode()->all(),
            'asNestedForm' => $this->asNestedForm,
        ]);
    }

}