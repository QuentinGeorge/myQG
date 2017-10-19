<?php
namespace Models;

use Models\Model as Model;

class User extends Model {
    public function fGetUserDB( $sUser, $sPassword ) {
        $pdo = $this->fConnectDB();
        if ( $pdo ) {
            $sSql = 'SELECT * FROM users WHERE name = :user AND password = :password';
            try {
                $pdoSt = $pdo->prepare( $sSql );
                $pdoSt->execute( [
                    ':user' => $sUser,
                    ':password' => $sPassword
                ] );
                return $pdoSt->fetch();
            } catch ( \PDOException $e ) {
                die( 'Une erreure est survenue lors de la connection' );
            }
        } else {
            die( 'Une erreure est survenue lors de la connection a la DB' );
        }
    }
}
