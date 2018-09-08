<?php

namespace app\modules\cms\models;

use app\modules\cms\HCms;
use app\modules\cms\queries\BaseNewsBaseTagQuery;
use app\modules\cms\queries\BaseNewsQuery;
use app\modules\hlib\HLib;
use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "base_news".
 *
 * @property integer           $id
 * @property string            $event_date
 * @property integer           $enabled
 * @property string            $created_at
 * @property string            $updated_at
 *
 * @property BaseNewsBaseTag[] $baseNewsBaseTags
 * @property BaseTag[]         $baseTags
 * @property WebNews[]         $webNews
 */
class BaseNews extends ActiveRecord
{
    /** @var  array */
    private $updatedBaseTagsIds;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%base_news}}';
    }

    /**
     * Initialisation avec les valeurs par défaut
     */
    public function init()
    {
        parent::init();

        // valeurs par défaut
        $this->enabled = false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['event_date', 'filter', 'filter' => function ($value) {
                // en entrée : dd-MM-yyyy ; en sortie : format compatible SQL
                return (new Carbon($value))->toDateString();
            }],
            //
            ['enabled', 'boolean'], // not null default 0
            ['event_date', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_date' => HCms::t('labels', 'Event Date'),
            'enabled' => HLib::t('labels', 'Enabled'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
            'baseTags' => HCms::t('labels', 'Base tags'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseNewsBaseTags()
    {
        return $this->hasMany(BaseNewsBaseTag::class, ['base_news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaseTags()
    {
        return $this->hasMany(BaseTag::class, ['id' => 'base_tag_id'])->viaTable('base_news_base_tag', ['base_news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWebNews()
    {
        return $this->hasMany(WebNews::class, ['base_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BaseNewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BaseNewsQuery();
    }

    /**
     * Renvoie la liste des identifiants des tags/racines associés à $this
     *
     * @return array
     */
    public function baseTagsIds()
    {
        return ArrayHelper::getColumn($this->baseTags, 'id');
    }

    /**
     * Renvoie la liste des identifiants des tags/racines associés à $this
     *
     * @return array
     */
    public function baseTagsCodes()
    {
        return ArrayHelper::getColumn($this->baseTags, 'code');
    }

    /**
     * Assignation en masse des attributs avec prise en compte de la liste des ids des tags
     * todo_cbn cf. issue #20
     *
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        $this->updatedBaseTagsIds = ArrayHelper::getValue($data, 'BaseNews.baseTags', []);
        if ($this->updatedBaseTagsIds === "") {
            $this->updatedBaseTagsIds = [];
        }

        return parent::load($data, $formName);
    }

    /**
     * Mise à jour de l'objet.
     * Si $saveRelated vaut 'true', la table d'association avec les tags est mise à jour en même temps.
     *
     * @param bool|true $runValidation
     * @param null $attributeNames
     * @param bool|false $saveRelated
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save($runValidation = true, $attributeNames = null, $saveRelated = false)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!parent::save($runValidation, $attributeNames)) {
                throw new Exception('Erreur sur parent::save()');
            }

            if ($saveRelated && !BaseNewsBaseTagQuery::updateBaseTagsForNews($this->baseTagsIds(), $this->updatedBaseTagsIds, $this->id)) {
                throw new Exception("Erreur lors de la mise à jour de la table d'association");
            }
        } catch (Exception $x) {
            Yii::error($x->getMessage(), __METHOD__);
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * Contrôle la liste des tags déclarés dans $request[nom_formulaire][relation] et crée s'il le faut de nouveaux objets dans la base.
     * Renvoie le tableau après avoir remplacé les libellés des nouveaux objets par leur identifiant.
     *
     * @param array $request Données de formulaire
     * @return array|bool
     */
    public function updateBaseTagsFromRequest(array &$request)
    {
        $currentRelatedModels = ArrayHelper::getColumn(BaseTag::find()->all(), 'id');

        $updatedRelatedModels = ArrayHelper::getValue($request, 'BaseNews.baseTags', []);
        if ($updatedRelatedModels === "") {
            $updatedRelatedModels = [];
        }

        foreach ($updatedRelatedModels as $i => $value) {
            if (!in_array($value, $currentRelatedModels)) {
                // $value n'est pas l'identifiant d'un tag déjà connu en base mais le libellé d'un nouvel objet
                $newRelatedModel = new BaseTag(['code' => $value]);
                if (!$newRelatedModel->save()) {
                    return false;
                }

                $updatedRelatedModels[$i] = $newRelatedModel->id;
            }
        }

        $request['BaseNews']['baseTags'] = $updatedRelatedModels;
        return true;
    }

    /**
     * @param bool $onlyActive
     * @return BaseNews
     */
    public function getPrevious($onlyActive = true)
    {
        return $this->find()->getPrevious($this->id, $this->event_date, $onlyActive)->one();
    }

    /**
     * @param bool $onlyActive
     * @return BaseNews
     */
    public function getNext($onlyActive = true)
    {
        return $this->find()->getNext($this->id, $this->event_date, $onlyActive)->one();
    }

}
