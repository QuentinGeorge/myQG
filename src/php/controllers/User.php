<?php
namespace Controllers;

use Models\User as ModelsUser;

class User {
    private $modelsUser = null;

    public function __construct() {
        $this->modelsUser = new ModelsUser();
    }

    public function getLogin() {
        $_SESSION[ 'logError' ] = '';

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
            $_SESSION[ 'logError' ] = 'Nom d´utilisateur ou mot de passe incorrect';
            return [ 'view' => 'views/userlogin.php' ];
        }
    }

    public function getLogout() {
        session_destroy();

        header( 'Location:' . PROJECT_PATH . 'index.php' );
        exit;
    }
}
