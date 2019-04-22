<?php
namespace App\Controllers;
use  \Twig_Loader_Filesystem;
use Zend\Diactoros\Response\HtmlResponse;


Class BaseController {
    protected $templateEngine;
    //protected dentro de las clases y las  clases hijas
    
    public function __construct() {
        $loader = new \Twig_Loader_Filesystem('../views');
        $this->templateEngine = new \Twig_Environment($loader, array(
            'debug' => true, 
            'cache' => false,
        ));

    }

    public function renderHTML($fileName, $data = []) {
        //Em el caso que no alla nada que pasar por parametros se puede
        // agregar =[] para que agrege un false - null
        return new HtmlResponse($this->templateEngine->render($fileName, $data));
    }
}   