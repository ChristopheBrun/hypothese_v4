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
     */
    public function __construct(Model $model)
    {
        Yii::warning(['!$model->validate()', $model]);
        parent::__construct(HLib::t('messages', "Validation error"));
    }
}