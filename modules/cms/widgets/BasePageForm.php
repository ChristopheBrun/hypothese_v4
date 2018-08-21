<?php

namespace app\modules\cms\widgets;
use app\modules\cms\models\BasePage;
use app\modules\cms\models\BaseText;
use yii\bootstrap\Widget;


/**
 * Class BasePageForm
 * @package app\modules\cms\widgets
 */
class BasePageForm extends Widget
{
    /** @var BasePage $model  */
    public $model;

    /** @var boolean  */
    public $asNestedForm = false;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('basePageForm', [
            'model' => $this->model,
            'baseTexts' => BaseText::find()->orderByCode()->all(),
            'parentPages' => BasePage::find()->enabled()->orderBy('code')->all(),
            'asNestedForm' => $this->asNestedForm,
        ]);
    }

}