<?php

namespace App\Shell;

use Cake\ORM\TableRegistry;
use Cake\Console\Shell;
use Cake\Filesystem\Folder;

/**
 * Simple console wrapper around Psy\Shell.
 */
class Nobu2Shell extends Shell
{

    public function main()
    {
        for ($i = 1870;$i <= 2140;$i++) {
            $basePath = ROOT . '/file/check2/' . $i . 'L.png';
            // M
            $baseSize = [
                'height' => 120,
                'width' => 96,
                'type' => 'scoop',
            ];
            $newPath = ROOT . '/file/check2/' . $i . 'M.png';
            $this->imageResize($basePath, $baseSize, $newPath);
            // S
            $baseSize = [
                'height' => 70,
                'width' => 56,
                'type' => 'scoop',
            ];
            $newPath = ROOT . '/file/check2/' . $i . 'S.png';
            $this->imageResize($basePath, $baseSize, $newPath);
        }
    }


    private $tp;

    /**
     * imageResize
     * 画像のリサイズ処理(外からでもたたけるようにpublicにする
     * @author hagiwara
     * @param string $imagePath
     * @param array $baseSize
     * @return bool
     */
    public function imageResize(string $imagePath, array $baseSize, string $newPath): bool
    {
        $imageInfo = $this->getImageInfo($imagePath);
        $image = $imageInfo['image'];
        $imagetype = $imageInfo['imagetype'];
        if (!$image) {
            // 画像の読み込み失敗
            return false;
        }
        // // 画像の縦横サイズを取得
        $imageSizeInfo = $this->imageSizeInfo($image, $baseSize);

        return $this->imageResizeMake($image, $imagetype, $imagePath, $baseSize, $imageSizeInfo, $newPath);
    }

    /**
     * getImageInfo
     * 画像情報の取得
     * @author hagiwara
     * @param string $imagePath
     * @return bool|array
     */
    private function getImageInfo(string $imagePath)
    {
        if (file_exists($imagePath) === false) {
            return false;
        }

        $imagetype = exif_imagetype($imagePath);
        if ($imagetype === false) {
            return false;
        }

        // 画像読み込み
        switch ($imagetype) {
            case IMAGETYPE_GIF:
                $image = ImageCreateFromGIF($imagePath);
                break;
            case IMAGETYPE_JPEG:
                $image = ImageCreateFromJPEG($imagePath);
                break;
            case IMAGETYPE_PNG:
                $image = ImageCreateFromPNG($imagePath);
                break;
            default:
                $image = false;
        }
        return [
            'image' => $image,
            'imagetype' => $imagetype,
        ];
    }

    /**
     * imageSizeInfo
     * 画像リサイズ情報の取得
     * @author hagiwara
     * @param resource $image
     * @param array $baseSize
     * @return array
     */
    private function imageSizeInfo($image, array $baseSize): array
    {
        // 画像の縦横サイズを取得
        $sizeX = ImageSX($image);
        $sizeY = ImageSY($image);
        // リサイズ後のサイズ
        if (!array_key_exists('height', $baseSize)) {
            $baseSize['height'] = 0;
        }
        if (!array_key_exists('width', $baseSize)) {
            $baseSize['width'] = 0;
        }
        // リサイズ種別
        if (!array_key_exists('type', $baseSize)) {
            $baseSize['type'] = 'normal';
        }

        if ($baseSize['type'] == 'normal_s' || $baseSize['type'] == 'scoop') {
            // 短い方基準もしくは、くりぬき
            if (empty($baseSize['width']) || !empty($baseSize['height']) && $sizeX * $baseSize['height'] < $sizeY * $baseSize['width']) {
                // 縦基準
                $mag = $baseSize['width'] / $sizeX;
                $reSizeX = $baseSize['width'];
                $reSizeY = $sizeY * $mag;
            } else {
                // 横基準
                $mag = $baseSize['height'] / $sizeY;
                $reSizeY = $baseSize['height'];
                $reSizeX = $sizeX * $mag;
            }
        } else {
            // 長い方基準
            if (empty($baseSize['width']) || !empty($baseSize['height']) && $sizeX * $baseSize['height'] < $sizeY * $baseSize['width']) {
                // 縦基準
                $mag = $baseSize['height'] / $sizeY;
                $reSizeY = $baseSize['height'];
                $reSizeX = $sizeX * $mag;
            } else {
                // 横基準
                $mag = $baseSize['width'] / $sizeX;
                $reSizeX = $baseSize['width'];
                $reSizeY = $sizeY * $mag;
            }
        }
        return [
            'sizeX' => $sizeX,
            'sizeY' => $sizeY,
            'reSizeX' => $reSizeX,
            'reSizeY' => $reSizeY,
            'type' => $baseSize['type'],
        ];
    }

    /**
     * imageResizeMake
     * 画像リサイズ情報の取得
     * @author hagiwara
     * @param resource $image
     * @param int $imagetype
     * @param string $imagePath
     * @param array $baseSize
     * @param array $imageSizeInfo
     */
    private function imageResizeMake($image, int $imagetype, string $imagePath, array $baseSize, array $imageSizeInfo, $newPath): bool
    {
        // サイズ変更後の画像データを生成
        $campusX = $imageSizeInfo['reSizeX'];
        $campusY = $imageSizeInfo['reSizeY'];
        // くりぬきの場合(幅と高さが両方必要)
        if ($imageSizeInfo['type'] == 'scoop' && !empty($baseSize['width']) && !empty($baseSize['height'])) {
            $campusX = $baseSize['width'];
            $campusY = $baseSize['height'];
        }
        $outImage = ImageCreateTrueColor($campusX, $campusY);
        if (!$outImage) {
            // リサイズ後の画像作成失敗
            return false;
        }

        switch ($imagetype) {
            case IMAGETYPE_GIF:
                //透過GIF対策
                $alpha = imagecolortransparent($image);  // 元画像から透過色を取得する
                imagefill($outImage, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
                imagecolortransparent($outImage, $alpha); // 塗りつぶした色を透過色として指定する
                //!透過GIF対策
                break;
            case IMAGETYPE_PNG:
                //透過PNG対策
                //ブレンドモードを無効にする
                imagealphablending($outImage, false);
                //完全なアルファチャネル情報を保存するフラグをonにする
                imagesavealpha($outImage, true);
                //!透過PNG対策
                break;
            default:
                break;
        }

        $diffX = 0;
        $diffY = 0;
        // くりぬきの場合(幅と高さが両方必要)
        if ($imageSizeInfo['type'] == 'scoop' && !empty($baseSize['width']) && !empty($baseSize['height'])) {
            $diffX = ($imageSizeInfo['sizeX'] - ($baseSize['width'] * $imageSizeInfo['sizeX'] / $imageSizeInfo['reSizeX'])) / 2;
            $diffY = ($imageSizeInfo['sizeY'] - ($baseSize['height'] * $imageSizeInfo['sizeY'] / $imageSizeInfo['reSizeY'])) / 2;
        }
        $ret = imagecopyresampled($outImage, $image, 0, 0, $diffX, $diffY, $imageSizeInfo['reSizeX'], $imageSizeInfo['reSizeY'], $imageSizeInfo['sizeX'], $imageSizeInfo['sizeY']);
        if ($ret === false) {
            // リサイズ失敗
            return false;
        }

        ImageDestroy($image);

        // 画像保存

        switch ($imagetype) {
            case IMAGETYPE_GIF:
                ImageGIF($outImage, $newPath);
                break;
            case IMAGETYPE_JPEG:
                ImageJPEG($outImage, $newPath, 100);
                break;
            case IMAGETYPE_PNG:
                ImagePNG($outImage, $newPath);
                break;
            default:
                return false;
        }

        ImageDestroy($outImage);

        return true;
    }

    /**
     * getPathInfo
     * 通常のpathinfoに加えてContentsFile独自のpathも一緒に設定する
     * @author hagiwara
     * @param string $imagePath
     * @param array $resize
     * @return array
     */
    public function getPathInfo(string $imagePath, array $resize = []): array
    {
        $pathinfo = pathinfo($imagePath);
        $pathinfo['resize_dir'] = $pathinfo['dirname'] . '/contents_file_resize_' . $pathinfo['filename'];
        //一旦ベースのパスを通しておく
        $pathinfo['resize_filepath'] = $imagePath;
        if (!empty($resize)) {
            if (!isset($resize['width'])) {
                $resize['width'] = 0;
            }
            if (!isset($resize['height'])) {
                $resize['height'] = 0;
            }
            if (!isset($resize['type'])) {
                $resize['type'] = 'normal';
            }
            $pathinfo['resize_filepath'] = $pathinfo['resize_dir'] . '/' . $resize['width'] . '_' . $resize['height'] . '_' . $resize['type'];
            if (Configure::read('ContentsFile.Setting.ext') === true) {
                $ext = (new \SplFileInfo($imagePath))->getExtension();
                $pathinfo['resize_filepath'] .= '.' . $ext;
            }
        }
        return $pathinfo;
    }



}
