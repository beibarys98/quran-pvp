<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%quran_id}}".
 *
 * @property int $id
 * @property int|null $suraId
 * @property int|null $verseID
 * @property string|null $ayahText
 * @property string|null $readText
 */
class QuranId extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%quran_id}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['suraId', 'verseID', 'ayahText', 'readText'], 'default', 'value' => null],
            [['id'], 'required'],
            [['id', 'suraId', 'verseID'], 'integer'],
            [['ayahText', 'readText'], 'string'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'suraId' => Yii::t('app', 'Sura ID'),
            'verseID' => Yii::t('app', 'Verse ID'),
            'ayahText' => Yii::t('app', 'Ayah Text'),
            'readText' => Yii::t('app', 'Read Text'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\QuranIdQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuranIdQuery(get_called_class());
    }

}
