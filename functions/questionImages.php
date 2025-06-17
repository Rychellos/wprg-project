<?php

class QuestionImagesContent
{
    public static string $directory = 'questionImages/c_';

    /**
     * @param string $identifier
     * @param string $filePath Raw image data (PNG) from file upload or similar
     * @return bool True if saved successfully, false otherwise.
     */
    public static function writeHashed(string $filename, string $filePath): bool
    {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }

        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filePath);
                break;
            default:
                return false;
        }

        if (!is_dir(self::$directory)) {
            mkdir(self::$directory, 0755, true);
        }

        $outputPath = self::$directory . basename($filename);
        $success = imagepng($image, $outputPath);
        imagedestroy($image);

        return $success;
    }

    /**
     * @param string $url
     * @return string|false Returns the file path or false if not found.
     */
    public static function read(string $url): string|false
    {
        $filePath = self::$directory . basename($url);
        return file_exists($filePath) ? $filePath : false;
    }
}

class QuestionImagesAnswer
{
    public static string $directory = 'questionImages/a_';

    /**
     * @param string $identifier
     * @param string $filePath Raw image data (PNG) from file upload or similar
     * @return bool True if saved successfully, false otherwise.
     */
    public static function writeHashed(string $filename, string $filePath): bool
    {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo === false) {
            return false;
        }

        $mime = $imageInfo['mime'];
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filePath);
                break;
            default:
                return false;
        }

        if (!is_dir(self::$directory)) {
            mkdir(self::$directory, 0755, true);
        }

        $outputPath = self::$directory . basename($filename);
        $success = imagepng($image, $outputPath);
        imagedestroy($image);

        return $success;
    }

    /**
     * @param string $url
     * @return string|false Returns the file path or false if not found.
     */
    public static function read(string $url): string|false
    {
        $filePath = self::$directory . basename($url);
        return file_exists($filePath) ? $filePath : false;
    }
}
