<?php


namespace app\modules\hlib\lib\exceptions;

use app\modules\hlib\HLib;
use Yii;
use yii\base\Model;

/**
 * Class ModelValidationException
 * @package app\modules\hlib\lib\exceptions
 */
class ModelValidationException extends WarningException
{
    /**
     * ModelLoadException constructor.
     * @param Model $model
     * @param bool $sendWarning
     */
    public function __construct(Model $model, bool $sendWarning = true)
    {
        $sendWarning && Yii::warning(['!$model->validate()', $model, $model->getErrors()]);
        parent::__construct(HLib::t('messages', "Validation error"));
    }
}