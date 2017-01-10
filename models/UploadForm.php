<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;


class UploadForm extends Model
{
/**
* @var UploadedFile
*/
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file',  'extensions' => 'png, jpg', 'maxSize' => 3000*3000],
            //5120*5120  'skipOnEmpty' => false,
        ];
    }
//------------------------------------------------------------------------------------------------------------------
//------------------------------- создание иконки изображения
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

//-------------------------------
//загрузка файла изображения
// $dstPath - путь к файлу - приемнику
// $dstName - имя файла приемника БЕЗ РАСШИРЕНИЯ
// $icon = '' -литера иконки, если пусто  - иконки не будет, $maxX - кооф. сжатия в иконку
// возвращает имя с путем к загруженному файлу или к файлу - иконке, или пустую строку
    public function upload($dstPath, $dstName, $icon = '', $maxX)
    {
        if ($this->validate()) {
            $fName =  $dstName . '.' . $this->imageFile->extension;
            $fType = $this->imageFile->extension;
            $fNameFull = $dstPath . $dstName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($fNameFull);
            if (strlen($icon) > 0){
                $fNameFull = $this->getIcon($fNameFull ,$dstPath, $fType ,$icon . $fName, $maxX);
            }
            return $fNameFull;
        } else {
            return '';
        }
    }
    public static function uploadFile($fModel, $dstPath, $dstName, $icon = '', $maxX)
    {

            $fName =  $dstName . '.' . $fModel->imageFile->extension;
            $fType = $fModel->imageFile->extension;
            $fNameFull = $dstPath . $dstName . '.' . $fModel->imageFile->extension;
           // return $fNameFull;
            $fModel->imageFile->saveAs($fNameFull);
            if (strlen($icon) > 0){
                $fNameFull = self::getIcon($fNameFull ,$dstPath, $fType ,$icon . $fName, $maxX);
            }
            return $fNameFull;

    }
    //-------------------------------
//загрузка файла изображения
// $dstPath - путь к файлу - приемнику
// $dstName - имя файла приемника БЕЗ РАСШИРЕНИЯ
// $icon = '' -литера иконки, если пусто  - иконки не будет, $maxX - кооф. сжатия в иконку
// $maxXXX - кооф. сжатия основного файла
// $tmpFile - временный файл с полным путем но без расширения
// возвращает имя с путем к загруженному файлу или к файлу - иконке, или пустую строку

    public static function uploadFileXXX($fModel, $dstPath, $dstName, $icon = '', $maxX, $maxXXX, $tmpFile)
    {
            $tmpFile_ = $tmpFile . '.' . $fModel->imageFile->extension;
            $fName =  $dstName . '.' . $fModel->imageFile->extension;
            $fType = $fModel->imageFile->extension;
            $fNameFull = $dstPath . $dstName . '.' . $fModel->imageFile->extension;
           // return $fNameFull;
            $fModel->imageFile->saveAs($tmpFile);
            if (strlen($icon) > 0){
                $fNameFull = self::getIcon($tmpFile ,$dstPath, $fType ,$fName, $maxXXX);
                $fNameFull = self::getIcon($tmpFile ,$dstPath, $fType ,$icon . $fName, $maxX);
                unlink($tmpFile);
            }
            return $fNameFull;

    }

    public function attributeLabels()
    {
        return [
            'imageFile' => '',
        ];
    }


}