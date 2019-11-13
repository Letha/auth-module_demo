<?php
    namespace App\Components;
    use App\Components\AppException;
    /**
     * General work with upload and dowload files.
     * @method public static validateDownloadFile(array &$fileData, string $fileType): bool.
     * @method public static downloadFile(array $fileData, string $filePath): void.
     */
    class FilesLoader
    {
        /**
         * Validation of file depending of validation type.
         * If image extention was "jpg" than it will be renamed to "jpeg".
         * @param array &$fileData.
         * @param string $fileType - validation type.
         * @return bool.
         */
        public static function validateDownloadFile(array &$fileData, string $fileType): bool
        {
            if ($fileType === 'image') {
                $maxFileSize = 307200;
                if (
                    $fileData['name'] === '' 
                    || $fileData['size'] === 0
                ) {
                    return false;
                }
                if ($fileData['size'] > $maxFileSize) {
                    unlink($fileData['tmp_name']);
                    return false;
                }

                $fileExtention = explode('.', $fileData['name']);
                $fileExtention = strtolower(end($fileExtention));
                $allowedTypes = array('jpg', 'jpeg', 'gif', 'png');
                if (!in_array($fileExtention, $allowedTypes)) {
                    unlink($fileData['tmp_name']);
                    return false;
                }
                if ($fileExtention === 'jpg') {
                    $fileData['name'] = preg_replace('#jpg$#', 'jpeg', $fileData['name']);
                }
                return true;
            }
        }
        /**
         * Move predownloaded file (sended from clien) from temporary directory to storage directory.
         * @param array $fileTempPath - temporary path to file.
         * @param string $filePath - path to storage including file name.
         * @return void.
         */
        public static function downloadFile(string $fileTempPath, string $filePath): void
        {
            if (!move_uploaded_file($fileTempPath, $filePath)) {
                throw new AppException('FilesLoader: download move_uploaded_file error.');
            }
        }
    }