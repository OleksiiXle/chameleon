<?php

namespace app\models;

use app\models\Books;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BooksSearch extends Books
{
   /* public $author_id_ = '';
    public $name_ = '';
    public $d1_ = '';
    public $d2_ = '';*/
    public $authorsListSLV = [];

    public function __construct(){
        parent::__construct();
        $this->authorsListSLV = $this->getAuthorSLV();
    }
//-----------------------------------------------------------------------------------------------------------------
    public function rules(){
        return [
            [['id', 'author_id'], 'integer'],
            [['name', 'date_create', 'date_update', 'preview', 'date'], 'safe'],
        ];
    }
//-----------------------------------------------------------------------------------------------------------------
  /*  public function attributeLabels()
    {
        return [
            'author_id_' => 'Автор',
            'name_' => 'Название книги',
            'd1_' => 'Дата выхода книги:',
            'd2_' => ' до',
        ];
    }*/


    public function scenarios(){
        return Model::scenarios();
    }
//-----------------------------------------------------------------------------------------------------------------
    public function search(){
        //----- если таблица Filter пустая, в $query все записи из Books, если нет - согласно фильтру
        if (count(Filter::find()->all()) == 0)
            $query =  Books::find()->with();
        else{
            $filterStr = Books::getFilterStr();
            $query =  Books::find()->where($filterStr)->with();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
/*        if (!$this->validate()) {
            return $dataProvider;
        }*/
        return $dataProvider;
    }
//-----------------------------------------------------------------------------------------------------------------
    public static function getFilterData(){
        $filter_ = Filter::find()->all();
        if (count($filter_) == 0)
            $filter_ = new Filter();
        else
            $filter_ = Filter::find()->one();
        return $filter_;

    }
}
