<?php

namespace app\modules\user\models;

use app\modules\user\models\query\TokenQuery;
use DateInterval;
use DateTimeImmutable;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "token".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Token extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'token';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'code'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['code'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function behaviors(): array
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

    public function attributeLabels(): array
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

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function find(): TokenQuery
    {
        return new TokenQuery(get_called_class());
    }

    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////

    public static function generateTokenForUser(int $userId, int $type): ?static
    {
        $token = new static(['user_id' => $userId, 'type' => $type, 'code' => uniqid()]);
        if ($token->save()) {
            return $token;
        }

        return null;
    }

    /**
     * Cherche le jeton correspondant aux critères
     * @param int $type
     * @param int $userId
     * @param string $code
     * @param int $duration
     * @return Token|null
     */
    public static function findToken(int $type, int $userId, string $code, int $duration = 0): ?Token
    {
        $query = static::find()->byUser($userId)->byCode($code)->byType($type);
        if ($duration) {
            $interval = new DateInterval('PT1S');
            $createdAfter = (new DateTimeImmutable('now'))->sub($interval)->format('Y-m-d H:i:s');
            $query->createdAfter($createdAfter);
        }

        return $query->one();
    }
}
