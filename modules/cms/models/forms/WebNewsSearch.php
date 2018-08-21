<?php

namespace app\modules\cms\models\forms;

use app\modules\cms\HCms;
use app\modules\cms\models\WebTag;
use app\modules\hlib\helpers\h;
use app\modules\hlib\HLib;
use app\modules\hlib\models\ModelSearchForm;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\cms\models\WebNews;

/**
 * WebNewsSearch represents the model behind the search form about `app\modules\cms\models\WebNews`.
 */
class WebNewsSearch extends ModelSearchForm
{
    /** @var  int */
    public $tagId;
    /** @var  string */
    public $title;
    /** @var  string */
    public $body;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['tagId', 'integer'],
            [['body', 'title'], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title' => HLib::t('labels', 'Title'),
            'body' => HLib::t('labels', 'Text'),
            'tagId' => HCms::t('labels', 'Tag'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function hasActiveFilters()
    {
        return $this->body || $this->title || $this->tagId;
    }

    /**
     * @inheritdoc
     */
    public function displayActiveFilters($sep = ' - ')
    {
        $filters = [];

        if ($this->title) {
            $filters[] = Yii::t('labels', '(title)') . ' ' . $this->title;
        }

        if ($this->body) {
            $filters[] = Yii::t('labels', '(text)') . ' ' . $this->body;
        }

        if ($this->tagId) {
            /** @var WebTag $tag */
            $tag = WebTag::findOne($this->tagId);
            $filters[] = $tag ? $tag->label : '???';
        }

        return implode($sep, $filters);
    }



    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search(array $params)
    {
        $query = WebNews::find()->byLanguageCode(h::getIso639Code())->joinWith('base');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$params) {
            return $dataProvider;
        }

        if (!$this->load($params) || !$this->validate()) {
            $this->error = static::ERR_KO;
            return $dataProvider;
        }

        if ($this->tagId) {
            $query->byTagId($this->tagId, false);
        }

        if (trim($this->title)) {
            $query->titleContains($this->title);
        }

        if (trim($this->body)) {
            $query->bodyContains($this->body);
        }

        return $dataProvider;
    }

}
