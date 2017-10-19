<?php
namespace Controllers;

use Models\File as ModelsFile;

class File {
    private $modelsFile = null;

    public function __construct() {
        $this->modelsFile = new ModelsFile();
    }

    public function index() {
        $_SESSION[ 'user' ][ 'groups' ] = $this->modelsFile->fGetAuthorizedGroupsFilesByUserName();

        return [ 'view' => 'views/filesindex.php' ];
    }

    public function upload() {
        $_SESSION[ 'uploadfeedback' ] = [];
        $_SESSION[ 'uploadfeedback' ][ $_POST[ 'group' ] ] = $this->modelsFile->fUploadFile( $_POST[ 'group' ] );

        return [ 'view' => 'views/filesindex.php' ];
    }
}
