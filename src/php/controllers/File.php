<?php
namespace Controllers;

use Models\File as ModelsFile;

class File {
    private $modelsFile = null;

    public function __construct() {
        $this->modelsFile = new ModelsFile();
    }

    public function index() {
        $this->modelsFile->fCreateMissingFilesFolders();
        $_SESSION[ 'user' ][ 'groups' ] = $this->modelsFile->fGetAuthorizedGroupsFilesByUserName();
        $_SESSION[ 'fileslist' ] = [];
        foreach ( $_SESSION[ 'user' ][ 'groups' ] as $value ) {
            $_SESSION[ 'fileslist' ][ $value ] = $this->modelsFile->fGetGroupFiles( $value );
        }

        return [ 'view' => 'views/filesindex.php' ];
    }

    public function upload() {
        $_SESSION[ 'uploadfeedback' ] = [];
        $_SESSION[ 'uploadfeedback' ][ $_POST[ 'group' ] ] = $this->modelsFile->fUploadFile( $_POST[ 'group' ] );

        header( 'Location:' . PROJECT_PATH . 'index.php?r=file&a=index' );
        exit;
    }

    public function delete() {
        if ( $_SESSION[ 'user' ][ 'name' ] === ADMIN_NAME && file_exists( $_POST[ 'group' ] ) && file_exists( $_POST[ 'file' ] ) ) {
            $this->modelsFile->fDeleteFile( $_POST[ 'group' ], $_POST[ 'file' ] );
        }

        header( 'Location:' . PROJECT_PATH . 'index.php?r=file&a=index' );
        exit;
    }
}
