<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $day
 * @property int|null $week
 * @property int|null $month
 * @property int|null $all_time
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Rating extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['all_time'], 'default', 'value' => 0],
            [['user_id'], 'required'],
            [['user_id', 'day', 'week', 'month', 'all_time'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'day' => Yii::t('app', 'Day'),
            'week' => Yii::t('app', 'Week'),
            'month' => Yii::t('app', 'Month'),
            'all_time' => Yii::t('app', 'All Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RatingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RatingQuery(get_called_class());
    }

}
