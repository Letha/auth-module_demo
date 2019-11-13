<?php
    namespace App\Models;
    use App\Core\Model;
    use App\Components\Db;
    use App\Components\FilesLoader;
    use App\Components\AppException;

    /**
     * Work with user's and accont's data.
     * @method public static getCurrentUserData(): ?array
     * @method public static login(array &$userData): ?bool
     * @method public static register(array &$userData, ?array &$filesData = null): ?bool
     * @method private isAccountExist(string $login): bool
     */
    class UsersModel extends Model
    {
        /**
         * @return ?array( // user's data or null if error
         *     'login' => string,
         *     'name' => string,
         *     'surname' => string,
         *     'birthDate' => string, // format: yyyy-mm-dd
         *     'hasPersonalPhoto' => bool
         * )
         */
        public static function getCurrentUserData(): ?array
        {
            session_start(['read_and_close' => true]);
            if (!isset($_SESSION['login'])) {
                return null;
            }

            $dbConnection = Db::getConnection();
            $preparedQuery = 'SELECT Name, Surname, Birth_date FROM Users WHERE Login = ?';
            $bindingParams = array('s', &$_SESSION['login']);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams);

            if (!$stmt->bind_result($userData['name'], $userData['surname'], $userData['birthDate'])) {
                throw new AppException(
                    'UsersModel: error when stmt binds result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            if ($stmt->fetch() !== true) {
                throw new AppException(
                    'UsersModel: error when stmt fetches result (notice: expecting at least one resulting row): (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            $stmt->close();

            $preparedQuery = "SELECT Id FROM Personal_files WHERE Login = ? AND File_role = 'personal_photo'";
            $bindingParams = array('s', &$_SESSION['login']);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams);

            if (!$stmt->bind_result($photoId)) {
                throw new AppException(
                    'UsersModel: error when stmt binds result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            $fetchStatus = $stmt->fetch();
            if ($fetchStatus === false) {
                throw new AppException(
                    'UsersModel: error when stmt fetches result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            } elseif ($fetchStatus === true) {
                $userData['hasPersonalPhoto'] = true;
            }
            $stmt->close();
            $dbConnection->close();
            $userData['login'] = $_SESSION['login'];
            return $userData;
        }
        /**
         * @param array &$userData - authorization data.
         * @return ?bool:
         *    true - when success;
         *    false - when wrong authorization data;
         *    null - when error.
         */
        public static function login(array &$userData): ?bool
        {
            if (
                !isset($userData['login'])
                || !isset($userData['password'])
            ) {
                return null;
            }
            if (preg_match('#^[a-zA-Z0-9]{1,30}$#', $userData['login']) === false) {
                return null;
            }

            $dbConnection = Db::getConnection();
            $preparedQuery = 'SELECT Password FROM Accounts WHERE Login = ?';
            $bindingParams = array('s', &$userData['login']);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams);

            if (!$stmt->bind_result($password)) {
                throw new AppException(
                    'UsersModel: error when stmt binds result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }

            $stmtFetchStatus = $stmt->fetch();
            $stmt->close();
            $dbConnection->close();
            if ($stmtFetchStatus === false) {
                throw new AppException(
                    'UsersModel: error when stmt fetches result (notice: expecting at least one resulting row): (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            } elseif ($stmtFetchStatus === null) {
                return false;
            } else {
                if ($password === $userData['password']) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        /**
         * @param array &$userData - registration data.
         * @param ?array &$filesData = null -
         *     now can load personal photo or pass null if not.
         * @return ?bool:
         *    true - when success;
         *    false - when recieved login already exist (deny);
         *    null - when error.
         */
        public static function register(array &$userData, ?array &$filesData = null): ?bool
        {
            if (
                !isset($userData['login']) || !isset($userData['password'])
                || !isset($userData['name']) || !isset($userData['surname'])
                || !isset($userData['birthDate'])
            ) {
                return null;
            }
            if ($filesData !== null && !isset($filesData['personalPhoto'])) {
                return null;
            }
            if (
                preg_match('#^[a-zA-Z0-9]{1,30}$#', $userData['login']) === false
                || preg_match('#^[а-яА-Яa-zA-Z \-]{1,30}$#', $userData['name']) === false
                || preg_match('#^[а-яА-Яa-zA-Z \-]{1,30}$#', $userData['surname']) === false
                || preg_match(
                    '#^(19|20)[0-9]{2}-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|[12]{1}[0-9]{1}|3[01]{1})$#',
                    $userData['birthDate']
                ) === false
            ) {
                return null;
            }

            if (self::isAccountExist($userData['login'])) {
                return false;
            }

            $dbConnection = Db::getConnection();
            $dbConnection->autocommit(false);
            $dbConnection->begin_transaction();

            $preparedQuery = 'INSERT Accounts (Login, Password) VALUES (?, ?)';
            $bindingParams = array('ss', &$userData['login'], &$userData['password']);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams, true);
            $stmt->close();

            $preparedQuery = 'INSERT Users (Login, Name, Surname, Birth_date) VALUES (?, ?, ?, ?)';
            $bindingParams = array(
                'ssss', &$userData['login'], &$userData['name'], 
                &$userData['surname'], &$userData['birthDate']
            );
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams, true);
            $stmt->close();

            if ($filesData !== null) {
                if (FilesLoader::validateDownloadFile($filesData['personalPhoto'], 'image') === true) {
                    $file = $filesData['personalPhoto'];
                    $fileExtention = explode('.', $file['name']);
                    $fileExtention = strtolower(end($fileExtention));
                    $fileMIMEType = 'image/' . $fileExtention;
                    $fileRole = 'personal_photo';
                    $fileDir = ROOT . 'storage/Personal_files/';

                    $preparedQuery = "
                        INSERT Personal_files (Login, File_role, File_MIME_type, File_path) 
                        VALUES (
                            ?, ?, ?, (
                                SELECT CONCAT(
                                    ?, 
                                    (
                                        SELECT IFNULL(MAX(Same_table.Id) + 1, 1)
                                        FROM Personal_files AS Same_table
                                    ),
                                    '.',
                                    ?
                                )
                            )
                        )
                    ";
                    $bindingParams = array('sssss', &$userData['login'], &$fileRole, &$fileMIMEType, &$fileDir, &$fileExtention);
                    $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams, true);
                    $filePath = $fileDir . $stmt->insert_id . '.' . $fileExtention;
                    $stmt->close();

                    FilesLoader::downloadFile($filesData['personalPhoto']['tmp_name'], $filePath);
                }
            }

            if (!$dbConnection->commit()) {
                throw new AppException(
                    'UsersModel: error when commit: (' 
                    . $dbConnection->errno . ') ' . $dbConnection->error
                );
            }
            if (!$dbConnection->autocommit(true)) {
                throw new AppException(
                    'UsersModel: error when set autocommit: (' 
                    . $dbConnection->errno . ') ' . $dbConnection->error
                );
            }
            $dbConnection->close();
            return true;
        }
        /**
         * @param string $login - login to check.
         * @return bool.
         */
        private static function isAccountExist(string $login): bool
        {
            $dbConnection = Db::getConnection();
            $preparedQuery = 'SELECT Login FROM Accounts WHERE Login = ?';
            $bindingParams = array('s', &$login);
            $stmt = Db::executePreparedQuery($dbConnection, $preparedQuery, $bindingParams);
            if (!$stmt->bind_result($empty)) {
                throw new AppException(
                    'UsersModel: error when stmt binds result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }

            $fetchStatus = $stmt->fetch();
            $stmt->close();
            $dbConnection->close();
            if ($fetchStatus === false) {
                throw new AppException(
                    'UsersModel: error when stmt fetches result: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            } elseif ($fetchStatus === true) {
                return true;
            } elseif ($fetchStatus === null) {
                return false;
            }
        }
    }