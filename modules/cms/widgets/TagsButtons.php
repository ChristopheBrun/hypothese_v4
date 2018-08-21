<?php

namespace app\modules\cms\widgets;

use app\modules\cms\models\WebTag;
use yii\bootstrap\Widget;


/**
 * Class TagsButtons
 * @package app\modules\cms\widgets
 */
class TagsButtons extends Widget
{
    /** @var string $view */
    public $view = 'tagsButtons';

    /** @var WebTag[] $tags */
    public $tags;

    /** @var string $buttonSize taille Bootstrap du bouton : '', 'lg', 'sm' ou 'xs' */
    public $buttonSize = 'sm';

    /**
     * @return string
     */
    public function run()
    {
        if (!count($this->tags)) {
            return '';
        }

        switch ($this->buttonSize) {
            case 'lg' :
                $buttonSizeClass = 'btn-lg';
                break;
            case 'sm' :
                $buttonSizeClass = 'btn-sm';
                break;
            case 'xs' :
                $buttonSizeClass = 'btn-xs';
                break;
            default :
                $buttonSizeClass = '';
                break;
        }

        return $this->render($this->view, [
            'tags' => $this->tags,
            'buttonSizeClass' => $buttonSizeClass,
        ]);
    }

}