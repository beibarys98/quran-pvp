<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%battle}}".
 *
 * @property int $id
 * @property int $playerOne
 * @property int $playerTwo
 * @property int $suraId
 * @property int $turn
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $playerOne0
 * @property User $playerTwo0
 */
class Battle extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%battle}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['turn'], 'default', 'value' => 1],
            [['playerOne', 'playerTwo', 'suraId'], 'required'],
            [['playerOne', 'playerTwo', 'suraId', 'turn'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['playerOne'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['playerOne' => 'id']],
            [['playerTwo'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['playerTwo' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'playerOne' => Yii::t('app', 'Player One'),
            'playerTwo' => Yii::t('app', 'Player Two'),
            'suraId' => Yii::t('app', 'Sura ID'),
            'turn' => Yii::t('app', 'Turn'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PlayerOne0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getPlayerOne0()
    {
        return $this->hasOne(User::class, ['id' => 'playerOne']);
    }

    /**
     * Gets query for [[PlayerTwo0]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getPlayerTwo0()
    {
        return $this->hasOne(User::class, ['id' => 'playerTwo']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BattleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BattleQuery(get_called_class());
    }

}
