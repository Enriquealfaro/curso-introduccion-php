<?php

ini_set('display_errors', 1);
ini_set('display_starup_error',1);
error_reporting(E_ALL);
//Esto se encarga de inicializar variabels y mostrar posibles errores

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
//director inicial
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$container = new DI\Container();
$capsule = new Capsule;


$capsule->addConnection([
    'driver'    => getenv('DB_DRIVER'),
    'host'      => getenv('DB_HOST'),/*'localhost',*/
    'database'  => getenv('DB_NAME'),/*'cursophp',*/
    'username'  => getenv('DB_USER'),/*'root',*/
    'password'  => getenv('DB_PASS'),/*'',*/
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

/*-------------------- Index ruta  */
$map->get('index','/',[
    'controller'=> 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);

/*---------------------addJobs     */
$map->get('addJob','/job/add', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
$map->post('saveJobs','/job/add', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
/*---------------------IndexJobs */
$map->get('indexJob','/jobs', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'indexAction'
]);
/*---------------------deleteJobs */
$map->get('deleteJobs','/jobs/delete', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'deleteAction'
]);
/*---------------------forceDeleteJobs */
$map->get('forceDeleteJobs','/jobs/forceDelete', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'forceDeleteAction'
]);
/*---------------------restoreJobs */
$map->get('restoreJobs','/jobs/restore', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'restoreAction'
]);
/*---------------------addProjects */

$map->get('addProjects','/project/add', [
    'controller'=> 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction'
]);
$map->post('saveProjects','/project/add', [
    'controller'=> 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction'
]);
/*---------------------addUssers */
$map->get('addUsers','/user/add', [
    'controller'=> 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);
$map->post('saveUser','/user/add', [
    'controller'=> 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);
/*--------------------loginForm */

$map->get('loginForm','/login', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);
/*--------------------logout  */
$map->get('logout','/logout', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);
/*---------------------admin */
$map->get('admin','/admin', [
    'controller'=> 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true
]);
/*---------------------auth */

$map->post('auth','/auth', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'No route';
}else{
    $handlerData = $route->handler;
    //va a instanciar la clase con el contenido de esta
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;
    
    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId){
         echo 'Protected route';
         die;
    }

    $controller =  $container->get($controllerName);
    //$controller = new $controllerName;
    $response = $controller->$actionName($request);

    foreach($response->getHeaders() as $name => $values)
    //para cada header que tengamos su valor como el nombre
    // y el interior el valor de cad nombre
    {
        foreach($values as $value){
            header(sprintf('%s: %s', $name, $value), false);
            //imprimir cosas dentro de una cadena sprintg
            //%s insertar o concatenar valores dentro de la cadena
        }
    }
    http_response_code($response->getStatusCode());
    //nos permite establecer el codigo de respueta
    echo $response->getBody();

}
