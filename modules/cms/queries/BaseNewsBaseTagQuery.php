<?php

namespace app\modules\cms\queries;
use app\modules\cms\models\BaseNewsBaseTag;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cms\models\BaseNewsBaseTag]].
 *
 * @see \app\modules\cms\models\BaseNewsBaseTag
 */
class BaseNewsBaseTagQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseNewsBaseTag[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cms\models\BaseNewsBaseTag|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Mise à jour des associations entre la news $modelId et ses tags
     *
     * @param array $oldRelatedModelsIds
     * @param array $updatedRelatedModelsIds
     * @param       $modelId
     * @return bool
     */
    public static function updateBaseTagsForNews(array $oldRelatedModelsIds, array $updatedRelatedModelsIds, $modelId)
    {
        $out = true;
        $deletedRelatedModelsIds = array_diff($oldRelatedModelsIds, $updatedRelatedModelsIds);
        if ($deletedRelatedModelsIds) {
            $deleted = BaseNewsBaseTag::deleteAll(['base_news_id' => $modelId, 'base_tag_id' => $deletedRelatedModelsIds]);
            $out &= ($deleted > 0);
        }

        $insertedRelatedModelsIds = array_diff($updatedRelatedModelsIds, $oldRelatedModelsIds);
        if ($insertedRelatedModelsIds) {
            $date = date('Y-m-d H:i:s');
            $batch = [];
            foreach ($insertedRelatedModelsIds as $relatedModelId) {
                $batch[] = [$modelId, $relatedModelId, $date, $date];
            }
            $inserted = Yii::$app->db->createCommand()->batchInsert('base_news_base_tag',
                ['base_news_id', 'base_tag_id', 'created_at', 'updated_at'],
                $batch)->execute();
            $out &= ($inserted > 0);
        }

        return $out;
    }
}