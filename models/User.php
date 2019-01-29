<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property int        $id
 * @property string     $username
 * @property string     $password_hash
 * @property string     $auth_key
 * @property int        $creator_id
 * @property int        $updater_id
 * @property int        $created_at
 * @property int        $updated_at
 *
 * @property Task[]     $createdTasks
 * @property Task[]     $updatedTasks
 * @property Task[]     $accessedTasks
 * @property TaskUser[] $taskUsers
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const RELATION_CREATED_TASKS = 'createdTasks';
    const RELATION_UPDATED_TASKS = 'updatedTasks';
    const RELATION_TASK_USERS = 'taskUsers';
    const RELATION_ACCESSED_TASKS = 'accessedTasks';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    public function behaviors() {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'creator_id',
                'updatedByAttribute' => 'updater_id',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['username', 'password_hash'], 'required'],
            [['creator_id', 'updater_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'creator_id' => 'Creator ID',
            'updater_id' => 'Updater ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     *
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     *
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     *
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Возвращает задачи созданные текущим пользователем
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedTasks() {
        return $this->hasMany(Task::className(), ['creator_id' => 'id']);
    }

    /**
     * Возвращает задачи измененные текущим пользователем
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedTasks() {
        return $this->hasMany(Task::className(), ['updater_id' => 'id']);
    }

    /**
     * Возвращает номера задач доступные пользователю
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers() {
        return $this->hasMany(TaskUser::className(), ['user_id' => 'id']);
    }

    /**
     * Возвращает задачи доступные пользователю
     * 5) Добавить в User метод релейшена getAccessedTasks, связывающий User и Task через релейшен taskUsers.
     * @return \yii\db\ActiveQuery
     */
    public function getAccessedTasks() {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])
            ->via($this::RELATION_TASK_USERS);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\UserQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\UserQuery(get_called_class());
    }
}
