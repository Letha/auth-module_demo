<?php
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Models\LoadFilesModel;

    /**
     * Work with upload and download of files.
     * @method public actionUploadPersonalPhoto(): void.
     */
    class LoadFilesController extends Controller
    {
        public function actionUploadPersonalPhoto(): void
        {
            $fileData = LoadFilesModel::getPersonalPhoto();
            header("Content-type: {$fileData['mimeType']}");
            echo $fileData['content'];
        }
    }