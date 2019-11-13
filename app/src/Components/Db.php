<?php
    namespace App\Components;
    use App\Components\AppException;
    /**
     * Work with database.
     * @method public static getConnection(): \mysqli.
     * @method public static executePreparedQuery(
     *     \mysqli $dbConnection, string $preparedQuery, 
     *     array $bindingParams, bool $isInTransaction = false
     * ): \mysqli_stmt.
     */
    class Db
    {
        public static function getConnection(): \mysqli
        {
            $includeConnectParamsState = include ROOT . 'src/config/db_params.php';
            if ($includeConnectParamsState === false) {
                throw new AppException('Db did not include connecntion parameters.');
            }
            $connectionParams = $includeConnectParamsState;

            $dbConnection = new \mysqli(
                $connectionParams['host'],
                $connectionParams['user'],
                $connectionParams['password'],
                $connectionParams['db']
            );
            if ($dbConnection->connect_error) {
                throw new AppException('Db ended database connection with error: ' . $mysqlConnect->connect_error . '.'); 
            }

            if (!$dbConnection->set_charset('utf8')) {
                throw new AppException('Db did not set database connetion charset.');
            }
            return $dbConnection;
        }
        /**
         * Creates, executes and returns mysql stmt.
         * @param \mysqli $dbConnection.
         * @param string $preparedQuery.
         * @param array $bindingParams = [
         *     '0' => string, // first arg to mysqli_stmt->bind_param
         *     ... => mixed // rest args to mysqli_stmt->bind_param
         * ].
         * @param bool $isInTransaction = false -
         *     if query is a part of transaction.
         * @return \mysqli_stmt.
         */
        public static function executePreparedQuery(
            \mysqli $dbConnection, string $preparedQuery, 
            array $bindingParams, bool $isInTransaction = false
        ): \mysqli_stmt {
            $stmt = $dbConnection->prepare($preparedQuery);
            if (!$stmt) { var_dump($dbConnection->error);
                throw new AppException(
                    'Db: error of preparing database query: (' 
                    . $dbConnection->errno . ') ' . $dbConnection->error
                );
            }
            $stmtBindingResult = call_user_func_array(array($stmt, 'bind_param'), $bindingParams);
            if (!$stmtBindingResult) {
                throw new AppException(
                    'Db: error of bind_param to database query: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            if (!$stmt->execute()) {
                if ($isInTransaction) {
                    $dbConnection->rollback();
                    $dbConnection->autocommit(true);
                }
                throw new AppException(
                    'Db: error of execute query: (' 
                    . $stmt->errno . ') ' . $stmt->error
                );
            }
            return $stmt;
        }
    }