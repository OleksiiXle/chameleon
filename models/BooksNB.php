<?php

namespace app\models;

use Yii;


class Books extends \yii\db\ActiveRecord
{
    public $imageFile;
   // public $date_ = '';
    public $pathToPreview = '';
    public $pathToNewPreview = '';
    public $tmpImage = '';
    public $authorsListSLV = [];
    public $author = '';

    public static function tableName() {
        return 'books';
    }

    public static function getNewPreview($id, $pathToNewPreview){
        $res = '';
        if (file_exists($pathToNewPreview . 'preview_' . $id . '.jpg')) $res =  'preview_' . $id . '.jpg';
        if (file_exists($pathToNewPreview . 'preview_' . $id . '.png')) $res =  'preview_' . $id . '.png';
        if (file_exists($pathToNewPreview . 'preview_' . $id . '.gif')) $res =  'preview_' . $id . '.gif';
        return $res;
    }
//-----------------------------------------------------------------------------------------------------------------
    public function __construct(){
        parent::__construct();
        $this->pathToPreview = Config::getConfig('pathToPreview');
        $this->pathToNewPreview = $this->pathToPreview . 'new/';
        $this->tmpImage =  Config::getConfig('tmpImage');
        $this->authorsListSLV = $this->getAuthorSLV();
    }
//-----------------------------------------------------------------------------------------------------------------
    public function rules(){
        return [
            [['author_id', 'name',  'date'], 'required'],
            [['author_id'], 'integer'],
            [['date_create', 'date_update', 'date'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['imageFile'], 'file',  'extensions' => 'png, jpg', 'maxSize' => 3000*3000],
        ];
    }
//-----------------------------------------------------------------------------------------------------------------
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'name' => 'Название',
            'date_create' => 'Дата создания записи',
            'date_update' => 'Дата обновления записи',
            'imageFile' => 'Превью',
            'date' => 'Дата выхода книги',
        ];
    }
//-----------------------------------------------------------------------------------------------------------------
    public function getAuthor(){
        return $this->hasOne(Authors::className(), ['id' => 'author_id']);
    }
//-----------------------------------------------------------------------------------------------------------------
    public function getAuthorName(){
        $author = $this->author;
        $fullName = $author->firstName . ' ' . $author->lastName;
        return $fullName;
    }
//-----------------------------------------------------------------------------------------------------------------
    public static function getAuthorList(){
        $strSQL = 'SELECT authors.firstName & " " & authors.lastName AS fullName
                   FROM authors';
        $t = Yii::$app->db->createCommand($strSQL)->queryAll();
        return $t;
    }
//-----------------------------------------------------------------------------------------------------------------
    public static function getAuthorSLV(){
        $strSQL = 'SELECT *
                   FROM authors';
        $query = Yii::$app->db->createCommand($strSQL)->queryAll();
        $arr = [];
        for ($i = 0; $i < count($query); $i++) {
            $key = $query[$i]['id'];
            $value = $query[$i]['firstName'] . ' ' . $query[$i]['lastName'];
            $arr[$key] = $value;
        }
        return $arr;
    }
//-----------------------------------------------------------------------------------------------------------------
    public static function find(){
        return new BooksQuery(get_called_class());
    }
//--------------------------------------------
    public static function getFullAuthorName($id){
        $author = Authors::find()->where(['id' => $id])->one();
        return ($author['firstName'] . ' ' . $author['lastName']);
    }
//----------------------------------------------------------------------------------------------------------------------------------
    public function mySave($newPreview){
        if ($this->isNewRecord) {
            $newID = $this->find()->max('id')+1;
            if (strlen($newPreview) > 0) { //если выбрана картинка
                $ext = end(explode(".", basename($newPreview)));
                $this->preview = 'preview_' . $newID . $ext;
                //-------- и записываем новые
                copy($this->pathToNewPreview .  $newPreview, $this->pathToPreview . $this->preview );
                copy($this->pathToNewPreview . 'i'.  $newPreview, $this->pathToPreview . 'i'.  $this->preview );
                //-- и очищаем папку нью
                unlink ($this->pathToNewPreview .  $newPreview);
                unlink ($this->pathToNewPreview . 'i'.  $newPreview);

            } else {
                $this->preview = 'preview_' . $newID . '.jpg';
                copy($this->tmpImage, $this->pathToPreview . $this->preview);
                copy($this->tmpImage, $this->pathToPreview . 'i' . $this->preview);
            }
        } else {
         //   $newID = $this->id;
            if (strlen($newPreview) > 0) { //если изменена картинка
        //    if (1 == 1) { //если изменена картинка
                if (file_exists($this->pathToPreview . $this->preview)) // ----удаляем старые файлы картинок
                    unlink($this->pathToPreview . $this->preview);
                if (file_exists($this->pathToPreview . 'i' . $this->preview))
                    unlink($this->pathToPreview . 'i'.  $this->preview);
                //-------- и записываем новые
                copy($this->pathToNewPreview .  $newPreview, $this->pathToPreview . $newPreview );
                copy($this->pathToNewPreview . 'i'.  $newPreview, $this->pathToPreview . 'i'.  $newPreview );
                //-- и очищаем папку нью
                unlink ($this->pathToNewPreview .  $newPreview);
                unlink ($this->pathToNewPreview . 'i'.  $newPreview);
            }
        }


        if ($this->isNewRecord) {
            $this->date_create = date('Y-m-d H:i:s');
            $this->date_update = $this->date_create;
        } else
            $this->date_update = date('Y-m-d H:i:s');
        return $this->save(false);
    }

//------------------------------------------------------------------------------------------------------------------
//------------------------------- масштабирование изображения
    //$fileSrc - файл источника с путем, $dst_path - путь, куда записать, $fileType - тип файла
    //$fileDst - имя файла без пути, в который записывать (без i), $max_x - кооф сжатия файла
    //возвращает имя файла иконки без пути или пустую строку
    //$max_x=500 - норм для иконки, 2500 - 3000 - вайлом для изображения будет до 1 мб
//------------------------------------------------------------------------------------------------------------------
    private function getIcon($fileSrc, $dst_path, $fileType, $fileDst, $max_x ){
        if ( $fileType == 'jpg')
            $source = imagecreatefromjpeg($fileSrc);
        elseif ( $fileType == 'png')
            $source = imagecreatefrompng($fileSrc);
        elseif ( $fileType == 'gif')
            $source = imagecreatefromgif($fileSrc);
        // Определяем ширину и высоту изображения
        $w_src = imagesx($source);
        $h_src = imagesy($source);
        // $fileDstI = 'i' . $fileDst;
        if ($w_src > $max_x) {
            // Вычисление пропорций
            $ratio = $w_src/$max_x;
            $w_dest = round($w_src/$ratio);
            $h_dest = round($h_src/$ratio);
            // Создаём пустую картинку
            $dest = imagecreatetruecolor($w_dest, $h_dest);
            // Копируем старое изображение в новое с изменением параметров
            imagecopyresampled($dest, $source, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
            // Вывод картинки и очистка памяти
            switch ($fileType){
                case 'gif':
                    imagegif($dest, $dst_path . $fileDst);
                    break;
                case 'png':
                    imagepng($dest, $dst_path . $fileDst);
                    break;
                case 'jpg':
                    imagejpeg($dest, $dst_path . $fileDst);
                    break;
            }
            imagedestroy($dest);
        }
        else {
            // Вывод картинки и очистка памяти
            switch ($fileType){
                case 'gif':
                    imagegif($source, $dst_path .$fileDst);
                    break;
                case 'png':
                    imagepng($source, $dst_path . $fileDst);
                    break;
                case 'jpg':
                    imagejpeg($source, $dst_path . $fileDst);
                    break;
            }
        }
        imagedestroy($source);
        return $dst_path . $fileDst;
    }

    /**
     * Человекопонятная дата.
     * @param string $date Дата формата YYY-MM-DD [HH-MM-SS]
     * @param boolen $showTime Вывод времени
     * @param boolen $showSeconds Вывод секунд
     * @param string $format Вормат вывода даты:
     * - 1 dd {fullMonthName} yyyy [hh:mm[:ss]]
     * - 2 dd {shortMonthName} yyyy [hh:mm[:ss]]
     * - 3 dd.mm.yyyy [hh:mm[:ss]] ($alwaysFull - принудительно true)
     * @param bool $alwaysFull Всегда полная дата (без сокращений ближайших дней)
     * @return string or boolen
     */
    public static function formatDateHuman($date, $showTime = false, $showSeconds = false, $format = 2, $alwaysFull = false)
    {
        // Провяем корректность переданой даты.
        if(preg_match("/^\d\d\d\d-\d\d-\d\d( \d\d:\d\d:\d\d){0,1}$/", $date) === 0)
            return false;

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        if($format === 2)
            $alwaysFull = true;

        // Формируем названия месяцев.
        switch($format) {
            case 1:
                $monthList = array(
                    '01'=>'Января',
                    '02'=>'Февраля',
                    '03'=>'Марта',
                    '04'=>'Апреля',
                    '05'=>'Мая',
                    '06'=>'Июня',
                    '07'=>'Июля',
                    '08'=>'Августа',
                    '09'=>'Сентября',
                    '10'=>'Октября',
                    '11'=>'Ноября',
                    '12'=>'Декабря',
                );
                break;

            case 2:
                $monthList = array(
                    '01'=>'Янв',
                    '02'=>'Фев',
                    '03'=>'Мар',
                    '04'=>'Апр',
                    '05'=>'Май',
                    '06'=>'Июн',
                    '07'=>'Июл',
                    '08'=>'Авг',
                    '09'=>'Сен',
                    '10'=>'Окт',
                    '11'=>'Ноя',
                    '12'=>'Дек',
                );
                break;
            case 3:
                $monthList = array(
                    '01'=>'01',
                    '02'=>'02',
                    '03'=>'03',
                    '04'=>'04',
                    '05'=>'05',
                    '06'=>'06',
                    '07'=>'07',
                    '08'=>'08',
                    '09'=>'09',
                    '10'=>'10',
                    '11'=>'11',
                    '12'=>'12',
                );
                break;
        }

        // Если разрешено, формируем короткий вариант ближайшей даты.
        if($alwaysFull == false) {
            $now = date("Y-m-d H:i:s");
            $nowYear = substr($now, 0, 4);
            $nowMonth = substr($now, 5, 2);
            $nowDay = substr($now, 8, 2);

            // Если сегодня...
            if($nowYear.$nowMonth.$nowDay == $year.$month.$day)
                $return = 'Сегодня';
            // Если вчера...
            else if($nowYear.$nowMonth.$nowDay == $year.$month.($day-1))
                $return = 'Вчера';
            // Если позавчера...
            else if($nowYear.$nowMonth.$nowDay == $year.$month.($day-2))
                $return = 'Позавчера';
        }

        // Если дата не была сформированна, формируем полную дату.
        if(!$return) {
            switch($format) {
                case 1:
                    $return = $day . ' ' . $monthList[$month] . ' ' . $year;
                    break;
                case 2:
                    $return = $day . ' ' . $monthList[$month] . ' ' . $year;
                    break;
                case 3:
                    $return = $day . '.' . $monthList[$month] . '.' . $year;
                    break;
            }
        }

        // Добавляем время.
        if($showTime === true) {
            $hour = substr($date, 11, 2);
            $minit = substr($date, 14, 2);
            $return .= ' в '.$hour.':'.$minit;

            if($showSeconds === true) {
                $second = substr($date, 17, 2);
                $return .= ':'.$second;
            }
        }

        // Возвращаем результат
        return $return;
    }
//----------------------------------------------------------------------------------------------------------
    public static function setUpdateHome(){
        $homeUpdate = [];
        $homeUpdate ['hPage'] = Yii::$app->request->get('page');
        $homeUpdate ['hSort'] = Yii::$app->request->get('sort');
        $session = Yii::$app->session;
        $session->set('homeUpdate', $homeUpdate);
    }
//----------------------------------------------------------------------------------------------------------
    public static function getUpdateHome(){
        $session = Yii::$app->session;
        if (isset($session['homeUpdate'])){
            $homeUpdate  = [];
            $homeUpdate [0] = 'index';
            $hPage = $session['homeUpdate']['hPage'];
            $hSort = $session['homeUpdate']['hSort'];
            if (strlen($hPage) > 0) $homeUpdate ['page'] = $hPage;
            if (strlen($hSort) > 0) $homeUpdate ['sort'] = $hSort;
            unset($session['homeUpdate']);
        } else {
            $homeUpdate = ['index'];
        }
        return $homeUpdate;
    }
//----------------------------------------------------------------------------------------------------------
    public static function getFilterStr(){
        $strWHERE='';
        $filter = Filter::find()->one();
        if (isset($filter)){
            $strWHERE='';
            if (($filter->author_id) > 0) {
                $strWHERE = 'author_id=' . $filter->author_id;
            }
            if (strlen($filter->name) > 0) {
                (strlen($strWHERE) > 0) ? $strWHERE = $strWHERE . ' AND name="' . $filter->name .'"' :
                    $strWHERE = 'name="' . $filter->name . '"';
            }
            if (strlen($filter->d1) > 0) {
                (strlen($strWHERE) > 0) ? $strWHERE = $strWHERE . ' AND (date >="' . $filter->d1  . '" AND date <="' . $filter->d2 .'")' :
                    $strWHERE = 'date >="' . $filter->d1  . '" AND date <="' . $filter->d2 .'"';
            }
        }
        return $strWHERE;
    }

}
