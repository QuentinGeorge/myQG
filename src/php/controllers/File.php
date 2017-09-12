<?php
namespace Controllers;

use Models\File as ModelsFile;

class File {
    private $modelsFile = null;

    public function __construct() {
        $this->modelsFile = new ModelsFile();
    }
}
