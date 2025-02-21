<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "quran_surah".
 *
 * @property int $id
 * @property string $arabic
 * @property string $latin
 */
class QuranSurah extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'quran_surah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['arabic', 'latin'], 'required'],
            [['arabic', 'latin'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'arabic' => Yii::t('app', 'Arabic'),
            'latin' => Yii::t('app', 'Latin'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\QuranSurahQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\QuranSurahQuery(get_called_class());
    }

}
