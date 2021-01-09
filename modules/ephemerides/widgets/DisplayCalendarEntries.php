<?php


namespace app\modules\ephemerides\widgets;

use app\modules\ephemerides\models\CalendarEntry;
use app\modules\ephemerides\models\Tag;
use app\modules\hlib\widgets\hWidget;
use yii\base\InvalidConfigException;

/**
 * Class DisplayCalendarEntries
 * @package app\modules\ephemerides\widgets
 */
class DisplayCalendarEntries extends hWidget
{
    /** @var CalendarEntry[]|CalendarEntry $models */
    public $models;

    /** @var Tag[] $tags */
    public array $tags = [];

    /** @var bool $showTagsAsButtons */
    public bool $showTagsAsButtons = true;

    /** @var string|array $tagsButtonsRoute */
    public $tagsButtonsRoute = null;

    /** @var string $templateName Préfixe du template à utiliser pour le rendu de la liste */
    public string $templateName = 'displayCalendarEntries';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->checkRequiredAttributes(['models']);
    }

    /**
     * @return string
     */
    public function run(): string
    {
        if (!is_array($this->models)) {
            $this->models = [$this->models];
        }

        return $this->render($this->templateName, [
            'models' => $this->models,
            'tags' => $this->tags,
            'showTagsAsButtons' => $this->showTagsAsButtons,
            'tagsButtonsRoute' => $this->tagsButtonsRoute,
        ]);
    }

}