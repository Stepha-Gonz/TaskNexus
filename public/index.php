<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\TareaController;
use Controllers\DashboardController;
$router = new Router();


//*LOGIN
//,index

$router->get('/',[LoginController::class,'login']);
$router->post('/',[LoginController::class,'login']);
$router->get('/logout',[LoginController::class,'logout']);

//,Crear Cuenta

$router->get('/crear',[LoginController::class,'crear']);
$router->post('/crear',[LoginController::class,'crear']);

//,Confirmar cuenta

$router->get('/mensaje',[LoginController::class,'mensaje']);
$router->get('/confirmar',[LoginController::class,'confirmar']);


//,olvide mi password

$router->get('/olvide',[LoginController::class,'olvide']);
$router->post('/olvide',[LoginController::class,'olvide']);


//,nuevo password
$router->get('/restablecer',[LoginController::class,'restablecer']);
$router->post('/restablecer',[LoginController::class,'restablecer']);



//*DASHBOARD

$router->get('/dashboard', [DashboardController::class,'index']);
$router->get('/crear-proyecto', [DashboardController::class,'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class,'crear_proyecto']);
$router->get('/perfil', [DashboardController::class,'perfil']);
$router->post('/perfil', [DashboardController::class,'perfil']);
$router->get('/cambiar-password', [DashboardController::class,'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class,'cambiar_password']);


//,PROYECTOS

$router->get('/proyecto', [DashboardController::class,'proyecto']);



//* API PARA LAS TAREAS

$router->get('/api/tareas',[TareaController::class,'index']);
$router->post('/api/tarea',[TareaController::class,'crear']);
$router->post('/api/tarea/actualizar',[TareaController::class,'actualizar']);
$router->post('/api/tarea/eliminar',[TareaController::class,'eliminar']);


//*COMPROBACION
$router->comprobarRutas();
