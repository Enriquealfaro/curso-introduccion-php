<?php

ini_set('display_errors', 1);
ini_set('display_starup_error',1);
error_reporting(E_ALL);
//Esto se encarga de inicializar variabels y mostrar posibles errores

require_once '../vendor/autoload.php';

session_start();

//$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
//director inicial
//$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

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
$map->get('index','/',[
    'controller'=> 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);
$map->get('addJobs','/job/add', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
$map->get('addProjects','/project/add', [
    'controller'=> 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction'
]);
$map->get('addUsers','/user/add', [
    'controller'=> 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);

$map->get('loginForm','/login', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'getLogin'
]);
$map->get('logout','/logout', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'getLogout'
]);
$map->get('admin','/admin', [
    'controller'=> 'App\Controllers\AdminController',
    'action' => 'getIndex',
    'auth' => true
]);


$map->post('saveJobs','/job/add', [
    'controller'=> 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);

$map->post('saveProjects','/project/add', [
    'controller'=> 'App\Controllers\ProjectsController',
    'action' => 'getAddProjectAction'
]);
$map->post('saveUser','/user/add', [
    'controller'=> 'App\Controllers\UsersController',
    'action' => 'getAddUserAction'
]);
$map->post('auth','/auth', [
    'controller'=> 'App\Controllers\AuthController',
    'action' => 'postLogin'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

function printJob($jobs){
    // if($jobs->visible == false){
    //   return;  
    // }
    echo '<li class="work-position">';
    echo '<h5>' . $jobs->title. '</h5>';
    echo '<p>'  . $jobs->description . '</p>';
    echo '<p>'  . $jobs->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
  }
  
    function printProject($projects){
      // if($jobs->visible == false){
      //   return;
      // }
      echo'<h5>' . $projects->title . '</h5>';
      echo'<div class="row">';
      echo'<div class="col-3">';
      echo'<img id="profile-picture" src="https://ui-avatars.com/api/?name=John+Doe&size=255" alt="">';
      echo'</div>';
      echo'<div class="col">';
      echo'<p>' . $projects->description . '</p>';
      echo'<strong>Technologies used:</strong>';
      echo'<span class="badge badge-secondary">PHP</span>';
      echo'<span class="badge badge-secondary">HTML</span>';
      echo'<span class="badge badge-secondary">CSS</span>';
      echo'</div>';
      echo'</div>';
    }

if(!$route){
    echo 'No route';
}else{
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];
    $needsAuth = $handlerData['auth'] ?? false;
    
    $sessionUserId = $_SESSION['userId'] ?? null;
    if($needsAuth && !$sessionUserId){
         echo 'Protected route';
         die;
    }

    $controller = new $controllerName;
    $response = $controller->$actionName($request);

    foreach($response->getHeaders() as $name => $values)
    {
        foreach($values as $value){
            header(sprintf('%s: %s', $name, $value), false);
            //imprimir cosas dentro de una cadena sprintg
            //%s insertar o concatenar valores dentro de la cadena
        }
    }
    http_response_code($response->getStatusCode());
    echo $response->getBody();

}
