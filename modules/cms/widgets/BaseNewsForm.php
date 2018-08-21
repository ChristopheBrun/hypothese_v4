<?php

namespace app\modules\cms\widgets;
use app\modules\cms\models\BaseNews;
use app\modules\cms\models\BaseTag;
use Yii;
use yii\bootstrap\Widget;


/**
 * Class BaseNewsForm
 * @package app\modules\cms\widgets
 */
class BaseNewsForm extends Widget
{
    /** @var BaseNews $model  */
    public $model;

    /** @var boolean  */
    public $asNestedForm = false;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('baseNewsForm', [
            'model' => $this->model,
            'baseTags' => BaseTag::find()->orderByCode()->all(),
            'asNestedForm' => $this->asNestedForm,
        ]);
    }

}