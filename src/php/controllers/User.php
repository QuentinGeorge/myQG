<?php
namespace Controllers;

use Models\User as ModelsUser;

class User {
    private $modelsUser = null;

    public function __construct() {
        $this->modelsUser = new ModelsUser();
    }

    public function getLogin() {

        return ['view' => 'views/userlogin.php'];
    }
}
