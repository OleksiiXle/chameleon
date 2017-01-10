<?php

namespace app\models;

use Yii;

class Filter extends \yii\db\ActiveRecord
{
    public $authorsListSLV = [];

    public function __construct(){
        parent::__construct();
        $this->authorsListSLV = $this->getAuthorSLV();
    }

    public static function tableName(){
        return 'filter';
    }


    public function rules() {
        return [
           // [['author_id', 'name', 'd1', 'd2'], 'required'],
            ['author_id', 'integer'],
            [['name'], 'string', 'max' => 100],
            ['d1', 'validateData1'],
            ['d2', 'validateData2'],

        ];
    }
//-------------------------------------------------------------------------------------------------------
    public function validateData1($attribute)
    {

        if (strlen($this->d1) > 0 && strlen($this->d2) == 0) {
            $this->addError($attribute, 'Не заполнена вторая дата');
        }
    }
//-------------------------------------------------------------------------------------------------------
    public function validateData2($attribute)
    {

        if (strlen($this->d2) > 0 && strlen($this->d1) == 0) {
            $this->addError($attribute, 'Не заполнена первая дата');
        }
        if ($this->d2 < $this->d1) {
            $this->addError($attribute, 'Вторая дата меньше первой');
        }


    }



    public function attributeLabels() {
        return [
            'author_id' => 'Автор',
            'name' => 'Название',
            'd1' => 'Дата выхода книги',
            'd2' => 'до',
        ];
    }

    public static function getAuthorSLV(){
        $strSQL = 'SELECT *
                   FROM authors';
        $query = Yii::$app->db->createCommand($strSQL)->queryAll();
        $arr = [];
        $arr[0] = 'автор';
        for ($i = 1; $i < count($query)+1; $i++) {
            $key = $query[$i-1]['id'];
            $value = $query[$i-1]['firstName'] . ' ' . $query[$i-1]['lastName'];
            $arr[$key] = $value;
        }
        return $arr;
    }

}
