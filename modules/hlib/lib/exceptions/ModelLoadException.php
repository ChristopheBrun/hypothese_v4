<?php


namespace app\modules\hlib\lib\exceptions;

use app\modules\hlib\HLib;
use Exception;
use Yii;
use yii\base\Model;

/**
 * Class ModelLoadException
 * @package app\modules\hlib\lib\exceptions
 */
class ModelLoadException extends Exception
{
    /**
     * ModelLoadException constructor.
     * @param Model $model
     * @param array $data
     */
    public function __construct(Model $model, array $data)
    {
        Yii::error(['!$model->load()', $model, $data]);
        parent::__construct(HLib::t('messages', "Load error"));
    }
}