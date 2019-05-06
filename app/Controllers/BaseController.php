<?php
namespace App\Controllers;
use  \Twig_Loader_Filesystem;
use Zend\Diactoros\Response\HtmlResponse;


Class BaseController {
    protected $templateEngine;
    //protected dentro de las clases y las  clases hijas
    
    public function __construct() {
        $loader = new \Twig_Loader_Filesystem('../views');
        // la diagonal expecifica que no la busque dentro del namespace
        // si no dentro del contexto 
        //directorio de donde vamos a estar importanto las vistas
        $this->templateEngine = new \Twig_Environment($loader, array(
            'debug' => true, 
            'cache' => false,
        ));
        //inicializamos nuestro tempalteEngine

    }

    public function renderHTML($fileName, $data = []) {
        //Em el caso que no haya nada que pasar por parametros se puede
        // agregar =[] para que agrege un false - null
        return new HtmlResponse($this->templateEngine->render($fileName, $data));
           //regresa un new htmlResponse con el valor
   
    }
}   