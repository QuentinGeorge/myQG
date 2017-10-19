<?php
namespace Models;

class Model {
    public function fConnectDB() {
        $dbConfig = @parse_ini_file( DB_INI_FILE );
        $dsn = sprintf(
            'mysql:dbname=%s;host=%s',
            $dbConfig[ 'dbname' ],
            $dbConfig[ 'host' ]
        );
        try {

            return new \PDO(
                $dsn,
                $dbConfig[ 'username' ],
                $dbConfig[ 'password' ],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch ( \PDOException $e ) {

            return false;
        }
    }
}
