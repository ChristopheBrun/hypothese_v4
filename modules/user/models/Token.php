<?php

namespace app\modules\user\models;

use app\modules\user\models\query\TokenQuery;
use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "token".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $code
 * @property integer $type
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property User    $user
 */
class Token extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['code'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(), [
                [
                    'class' => TimestampBehavior::class,
                    'value' => function () {
                        return date('Y-m-d H:i:s');
                    },
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('labels', 'ID'),
            'user_id' => Yii::t('labels', 'User ID'),
            'code' => Yii::t('labels', 'Code'),
            'type' => Yii::t('labels', 'Type'),
            'created_at' => Yii::t('labels', 'Created At'),
            'updated_at' => Yii::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return TokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TokenQuery(get_called_class());
    }

    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////

    /**
     * @param int $userId
     * @param int $type @see TokenType
     * @return null|static
     */
    public static function generateTokenForUser($userId, $type)
    {
        $token = new static(['user_id' => $userId, 'type' => $type, 'code' => uniqid()]);
        if ($token->save()) {
            return $token;
        }

        return null;
    }

    /**
     * Cherche le jeton correspondant au critères
     *
     * @param int    $type
     * @param int    $userId
     * @param string $code
     * @param int    $duration
     * @return Token|array|null
     */
    public static function findToken($type, $userId, $code, $duration = 0)
    {
        $query = static::find()->byUser($userId)->byCode($code)->byType($type);
        if ($duration) {
            $createdAfter = Carbon::now()->subSeconds($duration)->toDateTimeString();
            $query->createdAfter($createdAfter);
        }

        return $query->one();
    }
}
