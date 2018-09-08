<?php

namespace app\modules\hlib\widgets;

use yii\base\Widget;

/**
 * Class DateSelect
 * @package app\modules\hlib\widgets
 */
class DateSelectField extends Widget
{
    /** @var  string */
    public $templateName = 'dateSelectField';

    /** @var \yii\widgets\ActiveForm */
    public $form;

    /** @var \yii\db\ActiveRecord */
    public $model;

    /** @var  bool */
    public $checkActive;

    /** @var  string */
    public $dayField = '';

    /** @var  string */
    public $monthField = '';

    /** @var  string */
    public $yearField;

    /** @var  string */
    public $label;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->templateName, [
            'model' => $this->model,
            'form' => $this->form,
            'label' => $this->label,
            'dayField' => $this->dayField,
            'monthField' => $this->monthField,
            'yearField' => $this->yearField,
            'checkActive' => $this->checkActive,
        ]);
    }
}