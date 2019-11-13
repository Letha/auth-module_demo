<?php
    namespace App\Models;
    use App\Core\Model;
    use App\Components\Db;
    use App\Components\AppException;
    
    /**
     * Manage uploads and downloads.
     * @method public static getPersonalPhoto(): array
     */
    class LoadFilesModel extends Model
    {
        /**
         * Get user's personal photo (now only one photo per one user is accepteble).
         * @return array(
         *     'content' => string, // file content
         *     'mimeType' => string // file MIME type
         * )
         */
        public static function getPersonalPhoto(): array
        {
            session_start(['read_and_close' => true]);
            if (!isset($_SESSION['login'])) {
                throw new AppException('LoadFilesModel: unauthorized try to get personal photo.');
            }

            $dbConnection = Db::getConnection();
            $preparedQuery = "
                SELECT File_path, File_MIME_type
                FROM Personal_files
                WHERE Login = ? AND File_role = ? 
            ";
            $fileRole = 'personal_photo';
            $bindingParams = array('ss', &$_SESSION['login'], &$fileRole);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams);
            
            if (!$stmt->bind_result($filePath, $fileData['mimeType'])) {
                throw new AppException(
                    'LoadFilesModel: error when stmt binds result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            if ($stmt->fetch() !== true) {
                throw new AppException(
                    'LoadFilesModel: error when stmt fetches result (notice: expecting at least one resulting row): ('
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            $stmt->close();
            $dbConnection->close();

            $fileData['content'] = file_get_contents($filePath);
            if ($fileData['content'] === false) {
                throw new AppException('LoadFilesModel: file_get_contents() error.');
            }
            return $fileData;
        }
    }