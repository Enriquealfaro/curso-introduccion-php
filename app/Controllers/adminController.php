<?php

namespace App\Controllers;

use App\Models\{Job,Project, User};

class AdminController extends BaseController{
    public function getIndex(){
        return $this->renderHTML('admin.twig');
    }
}