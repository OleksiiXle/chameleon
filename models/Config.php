<?php

namespace app\models;


class Config extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'config';
    }

    public static function getConfig($attribute_){
        $v = self::find()->where(['attribute' => $attribute_])->one();
        return $v->value;
    }


}