<?php


namespace app\modules\hlib\lib\exceptions;

use app\modules\hlib\HLib;
use Yii;
use yii\base\Model;

/**
 * Class ModelValidationException
 * @package app\modules\hlib\lib\exceptions
 */
class ModelSaveException extends WarningException
{
    const ERROR_LEVEL_NONE = 0;
    const ERROR_LEVEL_WARNING = 1;
    const ERROR_LEVEL_ERROR = 2;

    /**
     * ModelSaveException constructor.
     * @param Model $model
     * @param int $errorLevel
     */
    public function __construct(Model $model, int $errorLevel = self::ERROR_LEVEL_ERROR)
    {
        switch ($errorLevel) {
            case static::ERROR_LEVEL_WARNING:
                Yii::warning(['!$model->save()', $model, $model->getErrors()]);
                break;
            case static::ERROR_LEVEL_ERROR:
                Yii::error(['!$model->save()', $model, $model->getErrors()]);
                break;
            default:
        }
        parent::__construct(HLib::t('messages', "Save error"));
    }
}