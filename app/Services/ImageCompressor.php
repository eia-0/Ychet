<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageCompressor
{
    /**
     * Сжать изображение и сохранить в указанную директорию.
     * Возвращает путь относительно диска 'public'.
     */
    public function compressAndStore(UploadedFile $file, string $directory = 'photos', int $maxMegabytes = 5): string
    {
        $maxBytes = $maxMegabytes * 1024 * 1024;

        // Если файл уже меньше лимита, просто сохраняем без изменений
        if ($file->getSize() <= $maxBytes) {
            return $file->store($directory, 'public');
        }

        $sourcePath = $file->getRealPath();
        $mime = $file->getMimeType();

        // Создаём изображение в зависимости от типа
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                // Если формат не поддерживается, просто сохраняем оригинал
                return $file->store($directory, 'public');
        }

        if (!$sourceImage) {
            return $file->store($directory, 'public');
        }

        // Получаем размеры оригинала
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Создаём временный файл
        $tempPath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $quality = 90;

        // Сохраняем как JPEG с понижением качества
        imagejpeg($sourceImage, $tempPath, $quality);
        imagedestroy($sourceImage);

        $size = filesize($tempPath);

        // Уменьшаем качество, пока не достигнем нужного размера
        while ($size > $maxBytes && $quality > 10) {
            $quality -= 5;
            $sourceImage = imagecreatefromstring(file_get_contents($file->getRealPath()));
            imagejpeg($sourceImage, $tempPath, $quality);
            imagedestroy($sourceImage);
            $size = filesize($tempPath);
        }

        // Если даже с минимальным качеством файл велик, уменьшаем размер
        if ($size > $maxBytes) {
            $sourceImage = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $newWidth = (int) ($originalWidth * 0.7);
            $newHeight = (int) ($originalHeight * 0.7);
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            imagejpeg($resizedImage, $tempPath, 85);
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
        }

        // Сохраняем результат в storage
        $filename = uniqid() . '.jpg';
        $path = $directory . '/' . $filename;
        \Storage::disk('public')->put($path, file_get_contents($tempPath));
        unlink($tempPath);

        return $path;
    }
}