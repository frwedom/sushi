<?php

namespace App;

class Upload
{

    private $files;
    private $uploadOk = 1;
    private $image_info = array();

    public $imageMaxSize = '3'; //MB
    public $imageCompress = true;
    public $imageFormats = ['PNG', 'JPG', 'JPEG', 'GIF'];

    public $videoMaxSize = '35'; //MB
    public $videoFormats = ['MP4', 'AVI', 'MPEG', 'WEBM'];

    public function __construct()
    {
        $this->files = $_FILES;
    }

    public function images($params)
    {

        $path_name = $params['path_name'];
        $max_files = $params['max_files'];
        $resizeType = $params['resizeType'];
        $resizeWidth = $params['resizeWidth'];
        $filterImage = $params['filter_image'];
        $crop = $params['crop'];
        $crop_name = $params['crop_name'];
//        $watermark = $params['watermark'];
        $watermark = 1;
        $files_count = $params['files_count'];
        $compress = $this->imageCompress;
        $max_sizeKB = intval($this->imageMaxSize . '000000');
        $formats = $this->imageFormats;

        //Куда будет загружена картинка
        $target_dir = "{$_SERVER["DOCUMENT_ROOT"]}/uploads/images/{$path_name}/";

        //Если папки нету, то создаем
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }

        $pathResizes = [];

        if (!empty($resizeWidth)) {

            foreach ($resizeWidth as $k => $v) {
                $pathResizes[$k] = $target_dir . $v . '/';
                $path = $pathResizes[$k];

                if (!is_dir($path)) {
                    mkdir($path);
                }
            }

        }

        $files_count = (int)$files_count;
        $files_count++;

        foreach ($this->files as $key => $value) {

            $this->uploadOk = 1;

            //Сама картинка
            $target_file = $target_dir . basename($this->files[$key]["name"]);

            $source_name = $this->files[$key]["name"];

            //Формат файла
            $imageFileType = strtoupper(pathinfo($target_file, PATHINFO_EXTENSION));

            //Имя файла
            $randName = \App::$functions->random_name($target_file);

            if (empty($crop_name)) {
                $path = $target_dir . $randName;
            } else {
                $v = '?v=' . '1';
                $path = $target_dir . '500/' . $crop_name;
            }


            $error_text = '';
            $error_code = '';

            if (!$compress) {
                // Проверка размера картинки в MB
                if ($this->files[$key]["size"] > $max_sizeKB) {
                    $this->uploadOk = 0;
                    $error_code = 1;
                }
            }

            // Проверка на количество картинок
            if ($max_files && $files_count && $files_count > (int)$max_files) {
                $this->uploadOk = 0;
                $error_code = 4;
            }

            // Проверка расширении картинки
            if (!in_array($imageFileType, $formats)) {

                $this->uploadOk = 0;
                $error_code = 2;

            }

            //Загружаем
            if ($this->uploadOk) {



                if (move_uploaded_file($this->files[$key]["tmp_name"], $path)) {
                    $error_code = 0;

                    $filterType = $max_files - $params['files_count'];

                    if ($filterImage) {
                        $this->filterImage($path, $path, $filterType, 100);

                        $filter_type = $filterType;
                    }

                } else {
                    $this->uploadOk = 0;
                    $error_code = 3;
                }

                //Сжатие
                if ($compress) {

                    $this->compressImage($path, $path, 85);
                    $filesize = filesize($path);

                    if ($filesize > $max_sizeKB) {
                        $this->uploadOk = 0;

                        $error_code = 1;
                        unlink($path);
                    }

                    if ($this->uploadOk) {
                        $files_count++;
                    }

                    $error_text .= " <br>(сжатый)";
                }

                $info = getimagesize($path);
                if ($info['mime'] == 'image/jpeg') {
                    $image = imagecreatefromjpeg($path);
                } elseif ($info['mime'] == 'image/gif') {
                    $image = imagecreatefromgif($path);
                } elseif ($info['mime'] == 'image/png') {
                    $image = imagecreatefrompng($path);
                }

                $iwidth = imagesx($image);

                imagedestroy($image);

                if ($this->uploadOk) {

                    $ydx_error = '';
                    $ydx_error_code = '';
                    $publish = '';

                }

                if ($watermark) {

                    $this->waterMark($path, $path, 100);

                }

                if (!$crop) {
                    if ($iwidth > 1040) {
                        $this->resizeImage($path, $path, 1040, 500, 100, 2);
                    }

                    if (!empty($pathResizes)) {

                        foreach ($pathResizes as $k => $v) {

                            $resizePath = $v . $randName;

                            $this->resizeImage($resizePath, $path, $resizeWidth[$k], 300, 100, $resizeType);
                        }

                    }
                }

            }


            $this->image_info[] = array(
                "files_count" => $files_count,
                "source_name" => $source_name,
                "name" => $randName,
                "status" => $this->uploadOk,
                "status_text" => $error_text,
                "error_code" => $error_code,
                "path_name" => $path_name,
                "compress" => $compress,
                "ydx_upload" => $params['yandexUpload'],
                "ydx_error" => $ydx_error,
                "ydx_error_code" => $ydx_error_code,
                "publish" => $publish,
                "filter_type" => $filter_type,
            );
        }



        return $this->image_info;
    }

    public function filterImage($dest, $src, $type, $quality) {

        $info = getimagesize($src);
        $image = '';
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
        }

        switch ($type) {
            case 0:
                imagefilter($image, IMG_FILTER_CONTRAST, -8);  // 1
                break;
            case 1:
                imagefilter($image, IMG_FILTER_COLORIZE, 0, 255, 153, 115); // 2
                break;
            case 2:
                imagefilter($image, IMG_FILTER_COLORIZE, 255, 80, 80, 115); // 3
                break;
            case 3:
                imagefilter($image, IMG_FILTER_BRIGHTNESS, 25); // 4
                break;
            case 4:
                imagefilter($image, IMG_FILTER_COLORIZE, 0, 153, 255, 115); // 5
                break;
        }

        imagejpeg($image, $dest, $quality);
        imagedestroy($image);
    }

    public function resizeImage($dest, $src, $width, $height, $quality, $resizeType)
    {

        $scale = 50;

        $info = getimagesize($src);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
        }

        $iwidth = imagesx($image);
        $iheight = imagesy($image);

        if ($resizeType == 1) {
            $ratio = $height / $iheight;
            $width = $iwidth * $ratio;
        } elseif ($resizeType == 2) {
            $ratio = $width / $iwidth;
            $height = $iheight * $ratio;
        } elseif ($resizeType == 3) {
            $width = $iwidth * $scale / 100;
            $height = $iheight * $scale / 100;
        }

        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $width, $height, $iwidth, $iheight);

        imagejpeg($new_image, $dest, $quality);
        imagedestroy($new_image);

    }

    public function compressImage($src, $dest, $quality)
    {
        $image = '';
        $uploadOk = 0;

        $info = getimagesize($src);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
            $uploadOk = 1;
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
            $uploadOk = 1;
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
            $uploadOk = 1;
        }

        if ($uploadOk) {
            imagejpeg($image, $dest, $quality);
        }

        imagedestroy($image);

    }

    public function waterMark($src, $dest, $quality)
    {
        $image = '';
        $uploadOk = 0;

        $info = getimagesize($src);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($src);
            $uploadOk = 1;
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($src);
            $uploadOk = 1;
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($src);
            $uploadOk = 1;
        }

        if ($uploadOk) {

            $stamp = imagecreatefrompng("{$_SERVER["DOCUMENT_ROOT"]}/uploads/other/logo.png");

            $sx = imagesx($stamp);
            $sy = imagesy($stamp);


            $w = imagesx($image) / 8;
            $koe = $sx / $w;
            $h = ceil($sy / $koe);
            $sim = imagecreatetruecolor($w, $h);
            $transparent = imagecolorallocatealpha($sim, 0, 0, 0, 127);
            imagefill($sim, 0, 0, $transparent);
            imagesavealpha($sim, true);
            imagecopyresampled($sim, $stamp, 0, 0, 0, 0, $w, $h, $sx, $sy);
            imagecopy($image, $sim, imagesx($image) - $w - 20, imagesy($image) - $h - 20, 0, 0, imagesx($sim), imagesy($sim));


            imagejpeg($image, $dest, $quality);
            imagedestroy($image);

        }

    }

    public function video($params)
    {

        $path_name = $params['path_name'];
        $max_files = $params['max_files'];
        $files_count = $params['files_count'] + count($this->files);
        $max_sizeKB = intval($this->videoMaxSize . '000000');
        $formats = $this->videoFormats;

        //Куда будет загружена картинка
        $target_dir = "{$_SERVER["DOCUMENT_ROOT"]}/uploads/video/{$path_name}/";

        //Если папки нету, то создаем
        if (!is_dir($target_dir)) {
            mkdir($target_dir);
        }

        $files_count = (int)$files_count;

        foreach ($this->files as $key => $value) {

            $this->uploadOk = 1;

            //Сама картинка
            $target_file = $target_dir . basename($this->files[$key]["name"]);

            $source_name = $this->files[$key]["name"];

            //Формат файла
            $imageFileType = strtoupper(pathinfo($target_file, PATHINFO_EXTENSION));

            //Имя файла
            $randName = \App::$functions->random_name($target_file);
            $path = $target_dir . $randName;

            $error_text = '';
            $error_code = '';

            // Проверка размера картинки в MB
            if ($this->files[$key]["size"] > $max_sizeKB) {
                $this->uploadOk = 0;
                $error_code = 1;
            }

            // Проверка на количество картинок
            if ($files_count > $max_files) {
                $this->uploadOk = 0;
                $error_code = 4;
            }

            // Проверка расширении картинки
            if (!in_array($imageFileType, $formats)) {

                $this->uploadOk = 0;
                $error_code = 2;
            }

            //Загружаем
            if ($this->uploadOk) {

                if (move_uploaded_file($this->files[$key]["tmp_name"], $path)) {
                    $error_code = 0;
                } else {
                    $this->uploadOk = 0;
                    $error_code = 3;
                }

                if ($this->uploadOk) {
                    $files_count++;
                }

            }


            $this->image_info[] = array(
                "source_name" => $source_name,
                "name" => $randName,
                "status" => $this->uploadOk,
                "status_text" => $error_text,
                "error_code" => $error_code,
                "path_name" => $path_name,
                "files_count" => $files_count,
                "max_files" => $max_files,
            );

        }

        return $this->image_info;
    }


}

