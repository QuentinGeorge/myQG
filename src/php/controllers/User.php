<?php
namespace Controllers;

use Models\User as ModelsUser;

class User {
    private $modelsUser = null;

    public function __construct() {
        $this->modelsUser = new ModelsUser();
    }

    public function getLogin() {

        return [ 'view' => 'views/userlogin.php' ];
    }

    public function postLogin() {
        $sUser = strtolower( strval( $_POST[ 'user' ] ) );
        $sPassword = sha1( strval( $_POST[ 'password' ] ) );

        $_SESSION[ 'user' ] = $this->modelsUser->fGetUserDB( $sUser, $sPassword );
        if ( $_SESSION[ 'user' ] !== false ) {
            header( 'Location:' . PROJECT_PATH . 'index.php?r=file&a=index' );
            exit;
        } else {
            return [ 'view' => 'views/userlogin.php' ];
        }
    }

    public function getLogout() {
        session_destroy();

        header( 'Location:' . PROJECT_PATH . 'index.php' );
        exit;
    }
}
