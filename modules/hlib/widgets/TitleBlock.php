<?php

namespace app\modules\hlib\widgets;

use yii\base\Widget;


/**
 * Class TitleBlock
 * @package app\modules\hlib\widgets
 */
class TitleBlock extends Widget
{
    /** @var  string */
    public $templateName = 'titleBlock';

    /** @var string nom de l'icône (id @ Font Awesome Icons) */
    public $iconId;

    /** @var  string */
    public $title;

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->templateName, [
            'iconId' => $this->iconId,
            'title' => $this->title,
        ]);
    }
}