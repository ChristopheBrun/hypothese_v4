<?php

namespace app\modules\hlib\widgets;

use app\modules\hlib\HLib;
use Yii;


/**
 * Class PageSize
 * @package app\widgets
 *
 * Personnalisation du widget PageSize avec un template légèrement amélioré
 */
class PageSize extends \nterms\pagesize\PageSize
{

    /**
     * PageSize constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!array_key_exists('template', $config)) {
            $config['template'] = "<div class='page-size'>" . HLib::t('labels', "Display") . " {list} {label}</div>";
        }

        if (!array_key_exists('label', $config)) {
            $config['label'] = Yii::t('labels', "items");
        }

        parent::__construct($config);
    }
}