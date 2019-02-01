<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Task]].
 *
 * @see \app\models\Task
 */
class TaskQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * return all owner's tasks
     *
     * @param $id integer id owner
     *
     * @return \app\models\Task|array
     */
    public function byCreator($id) {
        return $this->andWhere(['creator_id' => $id]);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Task[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Task|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
}
