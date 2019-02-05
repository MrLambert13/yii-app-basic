<?php

namespace app\models;

/**
 * This is the model class for table "task_user".
 * @property int        $id
 * @property int        $task_id
 * @property int        $user_id
 * @property Task       $task
 * @property User       $user
 * @property TaskUser[] $taskUsers
 * @property User[]     $sharedUsers
 */
class TaskUser extends \yii\db\ActiveRecord
{
    const RELATION_TASK = 'task';
    const RELATION_USER = 'user';
    const RELATION_TASK_USERS = 'taskUsers';
    const RELATION_SHARED_USERS = 'sharedUsers';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'task_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['task_id', 'user_id'], 'required'],
            [['task_id', 'user_id'], 'integer'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Возвращает информацию о задаче
     * @return \yii\db\ActiveQuery
     */
    public function getTask() {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * Возвращает информацию о задаче
     * @return \yii\db\ActiveQuery
     */
    public function getTaskUsers() {
        return $this->hasMany(TaskUser::className(), ['task_id' => 'id']);
    }

    /**
     * Возвращает информацию о пользователе который имеет досуп к текущей задаче
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\TaskUserQuery the active query used by this AR class.
     */
    public static function find() {
        return new \app\models\query\TaskUserQuery(get_called_class());
    }
}
