<?php

namespace Controllers;

use MVC\Router;
use Model\Usuario;
use Model\Proyecto;


class DashboardController{
    public static function index(Router $router){

        session_start();
        
        isAuth();

        $id=$_SESSION['id'];
        $proyectos= Proyecto::belongsTo('propietarioId',$id);
        
        $router->render('dashboard/index',[
            'titulo'=>'Proyectos',
            'proyectos'=>$proyectos

        ]);

    }
    public static function crear_proyecto(Router $router){

        session_start();
        isAuth();
        $alertas=[];
        

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $proyecto=new Proyecto($_POST);
            
            $alertas=$proyecto->validarProyecto();


            if(empty($alertas)){
                //generar url 
                $hash=md5(uniqid());
                $proyecto->url=$hash;
                $proyecto->propietarioId=$_SESSION['id'];
                $proyecto->guardar();
                header('Location:/proyecto?id=' . $proyecto->url);
                
            }
        }
        $router->render('dashboard/crear-proyecto',[
            'titulo'=>'Crear Proyecto',
            'alertas'=>$alertas

        ]);

    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();

        $token=$_GET['id'];
        if(!$token){
            header('Location:/dashboard');
        }else{
            $proyecto=Proyecto::where('url',$token);
            if($proyecto->propietarioId !== $_SESSION['id']){
                header('Location:/dashboard');
            }
        }
        $router->render('dashboard/proyecto',[
            'titulo'=>$proyecto->proyecto
        ]);
    }
    public static function perfil(Router $router){

        session_start();
        isAuth();

        $alertas=[];
        
        $usuario=Usuario::find($_SESSION['id']);
        

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validar_perfil();

            if(empty($alertas)){

                $existeUsuario=Usuario::where('email',$usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id ){


                    Usuario::setAlerta('error','Email no disponible, cuenta ya registrada');
                }else{
                    $usuario->guardar();
                    Usuario::setAlerta('exito','Guardado Correctamente');
                    $_SESSION['nombre']= $usuario->nombre;
                }
                
                $alertas=$usuario->getAlertas();
                
            }



            //debuguear($alertas);
        }
        $router->render('dashboard/perfil',[
            'titulo'=>'Perfil',
            'usuario'=>$usuario,
            'alertas'=>$alertas

        ]);

    }

    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas=[];

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario= Usuario::find($_SESSION['id']);
            $usuario->sincronizar($_POST);
            $alertas=$usuario->nuevo_password();

            if(empty($alertas)){
                $resultado= $usuario->comprobar_password();

                if($resultado){
                    $alertas=$usuario->comprobar_confirmacion();
                    $alertas=Usuario::getAlertas();

                    if(empty($alertas)){
                        
                        $usuario->password=$usuario->password_nuevo;
                        unset($usuario->password_actual);
                        unset($usuario->password_confirmado);
                        unset($usuario->password_nuevo);

                        $usuario->hashPassword();
                        $resultado2=$usuario->guardar();

                        if($resultado2){
                            Usuario::setAlerta('exito', 'Contraseña Actualizada');
                            $alertas=Usuario::getAlertas();
                        }
                        
                    }
                    

                }else{
                    Usuario::setAlerta('error', 'Contraseña Actual incorrecta');
                    
                }
                
                
            }
            
        }

        $alertas=Usuario::getAlertas();
        $router->render('dashboard/cambiar-password',[
            'titulo'=> 'Cambiar Contraseña',
            'alertas'=>$alertas
        ]);
    }

   
}