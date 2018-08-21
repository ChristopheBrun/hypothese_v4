<?php

namespace app\modules\hlib\widgets;

use app\modules\hlib\HLib;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;


/**
 * Class DeleteLink
 * @package app\modules\hlib\widgets
 */
class DeleteLink extends Widget
{

    /** @var string $url URL à appeler por le delete */
    public $url;

    /** @var string $text Texte d'avertissement */
    public $text;

    /** @var string $data Texte complémentaire */
    public $data;

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        if(!isset($this->url)) {
            throw new InvalidConfigException(HLib::t('messages', 'The URL for the delete action is mandatory'));
        }

        $text = isset($this->text) ? $this->text : HLib::t('messages', 'Are you sure you want to delete this item?');
        if (isset($this->data)) {
            $text .= "\n" . $this->data;
        }

        return $this->render('deleteLink', [
            'url' => $this->url,
            'text' => $text,
        ]);
    }
}