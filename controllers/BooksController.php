<?php

namespace app\controllers;

use Yii;
use app\models\Config;
use app\models\Books;
use app\models\BooksSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{

  /*  public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\HttpCache',
               // 'only' => ['index', 'view'],
                'lastModified' => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return $q->from('books')->max('date_update');
                },
            ],
        ];
    }*/
 /*   public function behaviors()
    {
        return [
            'pageCache' => [
                'class' => 'yii\filters\PageCache',
             //   'only' => ['index'],
                'duration' => 0,
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT MAX(date_update) FROM books',
                ],
                'variations' => [
                    \Yii::$app->language,
                ]
            ],
        ];
    }*/
    public function actionTest(){
        //Yii::$app->HttpCache->cacheControlHeader;
        $d1 = [];
        $d2 = Yii::$app->HttpCache;
        $mess = 'cZv';// Yii::$app->HttpCache->cacheControlHeader;
        return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);
    }

//------------------------------------------------------------------------------------
    public function actionIndex(){
           //yii\filters\HttpCache
        if (Yii::$app->user->isGuest)
            return $this->render('welcome');

        $queryModel = BooksSearch::getFilterData();  //----- определяем параметры фильтра
        $searchModel = new BooksSearch();            //----- модель с данными книг и провайдер данных на основе фильтра
        $dataProvider = $searchModel->search();

        if ($queryModel->load(Yii::$app->request->post()) ) {
           /* $d1 = $_REQUEST;
            $d2 = Yii::$app->request;
            $mess =  Books::getFilterStr();
            return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);*/

            if (isset($_REQUEST['mode'])) {
                switch ($_REQUEST['mode']) {
                    case 'filterOn':                    //---- применить фильтр или обновить имеющийся
                        if  ($queryModel->validate()){
                            if (!$queryModel->save()){
                                $d1 = $_REQUEST;
                                $d2 = $queryModel->errors;
                                $mess = '';
                                return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);
                            }
                            $searchModel = new BooksSearch();
                            $dataProvider = $searchModel->search();
                            $queryModel = BooksSearch::getFilterData();
                        }
                        break;
                    case 'filterOff':                    //----- идалить фильтр и показать все записи
                        $queryModel->delete();
                        $searchModel = new BooksSearch();
                        $dataProvider = $searchModel->search();
                        $queryModel = BooksSearch::getFilterData();
                        break;
                }
            }
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'queryModel' => $queryModel,
        ]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------------

  /*  public function actionGetgohome(){
        $page_sort = Books::getUpdateHome();
        $this->redirect($page_sort);
    }*/
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function actionView($id) {     //--- просмотр данных книги в модальном окне

        $model = Books::findOne($id);
        $author = Books::getFullAuthorName($model->author_id);
        return $this->renderPartial('viewModal', ['model' => $model, 'author' => $author]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function actionViewnormal($id) {     //--- просмотр данных книги в обычном окне после редактирования или добавления новой книги
        $model = Books::findOne($id);
        $author = Books::getFullAuthorName($model->author_id);
        return $this->render('view', ['model' => $model, 'author' => $author]);
    }
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function actionCreate(){      //---- создание новой записи
        $model = new Books();
        if ($model->load(Yii::$app->request->post()) ) {
           /* $d1 = $_REQUEST;
            $d2 =  $model;
            $mess = '';
            return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);*/

            if (!$model->mySave($_REQUEST['newPreviewTxt'])){
                $d1 = $_REQUEST;
                $d2 =  $model;
                $mess = $model->errors;
                return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);
            }
            return $this->redirect(['viewnormal', 'id' => $model->id]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function actionUpdate($id) {   //---- редактирование данных книги
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post())) {
            /*$d1 = $_REQUEST;
            $d2 =  $model;
            $mess = '';
            return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);*/

            if (!$model->mySave($_REQUEST['newPreviewTxt'])){
                $d1 = $_REQUEST;
                $d2 =  $model;
                $mess = $model->errors;
                return $this->render('booksDebug', ['d1' => $d1, 'd2' => $d2, 'mess' => $mess]);
            } else {
                return $this->redirect(['viewnormal', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }
//---------------------------------------------------------------------------------------------------------------------------------------------
    public function actionDelete($id) {  //------------ удаление книги
        $bookDelete = $this->findModel($id);
        $pathToPreview = Config::getConfig('pathToPreview');
        if (file_exists($pathToPreview . $bookDelete->preview))  //--- удаление превью
            unlink($pathToPreview . $bookDelete->preview);
        if (file_exists($pathToPreview . 'i' . $bookDelete->preview))  //-- удаление иконки превью
            unlink($pathToPreview . 'i' . $bookDelete->preview);
        $bookDelete->delete();

        return $this->redirect(['index']);
    }
//---------------------------------------------------------------------------------------------------------------------------------------------
    protected function findModel($id){
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
