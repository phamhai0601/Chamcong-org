<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logwork".
 *
 * @property int $id
 * @property int $user_id
 * @property int $time_start
 * @property int $time_end
 * @property int $create_at
 * @property int $updated_at
 */
class Logwork extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logwork';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'time_start', 'time_end', 'create_at', 'updated_at'], 'required'],
            [['id', 'user_id', 'time_start', 'time_end', 'create_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'time_start' => 'Time Start',
            'time_end' => 'Time End',
            'create_at' => 'Create At',
            'updated_at' => 'Updated At',
        ];
    }
}
