<?php

namespace app\modules\user\models;

use app\modules\hlib\helpers\h;
use app\modules\user\lib\enums\AuthItemType;
use app\modules\user\lib\enums\TokenType;
use app\modules\user\models\query\AuthAssignmentQuery;
use app\modules\user\models\query\UserQuery;
use Yii;
use app\modules\user\UserModule;
use app\modules\hlib\HLib;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\filters\auth\HttpBasicAuth;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $password_updated_at
 * @property integer $password_usage
 * @property string $auth_key
 * @property string $confirmed_at
 * @property string $blocked_at
 * @property integer $registered_from
 * @property integer $logged_in_from
 * @property string $logged_in_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Profile[] $profiles
 * @property Profile $profile
 * @property AuthAssignment[] $authorizations
 * @property AuthItem[] $roles
 * @property AuthItem[] $permissions
 */
class User extends ActiveRecord implements IdentityInterface
{
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_PASSWORD = 'password';

    public string $email_confirm;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @return array
     */
    public function scenarios(): array
    {
        $scenarios = parent::scenarios();
        $scenarios[static::SCENARIO_REGISTER] = ['email'];
        $scenarios[static::SCENARIO_PASSWORD] = ['password_hash', 'password_updated_at', 'password_usage'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            ['email_confirm', 'compare', 'compareAttribute' => 'email'],
            //
            [['registered_from', 'confirmed_at', 'blocked_at', 'logged_in_at', 'logged_in_from',
                'auth_key', 'password_hash', 'password_updated_at', 'password_usage', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        UserModule::registerTranslations();
        return [
            'id' => Yii::t('labels', 'ID'),
            'email' => UserModule::t('labels', 'Email'),
            'email_confirm' => UserModule::t('labels', 'Email (confirm)'),
            'password_hash' => UserModule::t('labels', 'Password Hash'),
            'password_updated_at' => UserModule::t('labels', 'Password Updated At'),
            'password_usage' => UserModule::t('labels', 'Password Usage'),
            'auth_key' => UserModule::t('labels', 'Auth Key'),
            'confirmed_at' => UserModule::t('labels', 'Confirmed At'),
            'blocked_at' => UserModule::t('labels', 'Blocked At'),
            'registered_from' => UserModule::t('labels', 'Registered From'),
            'logged_in_from' => UserModule::t('labels', 'Logged In From'),
            'logged_in_at' => UserModule::t('labels', 'Logged In At'),
            'created_at' => HLib::t('labels', 'Created At'),
            'updated_at' => HLib::t('labels', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getProfiles(): ActiveQuery
    {
        return $this->hasMany(Profile::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthorizations(): ActiveQuery
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getRoles(): ActiveQuery
    {
        return $this->hasMany(AuthItem::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id'])
            ->where(['auth_item.type' => AuthItemType::ROLE]);
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(AuthItem::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id'])
            ->where(['auth_item.type' => AuthItemType::PERMISSION]);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     * @throws InvalidConfigException
     */
    public static function find(): UserQuery
    {
        return Yii::createObject(UserQuery::class, [get_called_class()]);
    }

    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id): IdentityInterface
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        switch ($type) {
            case h::getClass(HttpBasicAuth::class) :
                return User::findOne(['auth_key' => $token]);
            default :
                $token = Token::find()->byType(TokenType::ACCESS)->byCode($token)->one();
                return $token ? static::findIdentity($token->user_id) : null;
        }
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     * @noinspection PhpMissingParamTypeInspection
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key == $authKey;
    }

//    /**
//     * Remet à jour les flags sur l'utilisation du mot de passe : date de mise à jour, nombre d'utilisations
//     *
//     * @return mixed
//     */
//    public function resetPasswordInformations()
//    {
//        return static::find()->resetPasswordInformations($this->id, date('Y:m:d H:i:s'));
//    }

    /**
     * @return string
     */
    public function formatName(): string
    {
        return $this->profiles ? $this->profiles[0]->formatName() : '';
    }

    /**
     * @param bool $mailtoLink
     * @return string
     */
    public function formatNameAndMail($mailtoLink = true): string
    {
        $name = $this->formatName();
        return $mailtoLink ?
            sprintf('%s (<a href="mailto:%s">%s</a>)', $name, $this->email, $this->email)
            : sprintf('%s (%s)', $name, $this->email);
    }

    /**
     * Renvoie le premier profil associé à cet utilisateur
     * Il s'agit d'une commodité permettant de gérer un profil par défaut, utile pour toutes les applications ne proposant qu'un seul
     * profil par utilisateur
     *
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->profiles ? $this->profiles[0] : null;
    }

    /**
     *
     * @throws Exception
     */
    public function afterDelete()
    {
        parent::afterDelete();

        // On supprime les entrées associées dans la table auth_assignment
        AuthAssignmentQuery::deleteByUser($this->id);
    }

}
