<?php


namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController{

    public static function login(Router $router){
    
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);
            
            $alertas=$usuario->validarLogin();

            if(empty($alertas)){
                $usuario=Usuario::where('email',$usuario->email);
                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error','Usuario no registrado o confirmado');
                }else{
                    if(password_verify($_POST['password'],$usuario->password)){
                        session_start();
                        $_SESSION['id']=$usuario->id;
                        $_SESSION['nombre']=$usuario->nombre;
                        $_SESSION['email']=$usuario->email;
                        $_SESSION['login']=true;

                        header('Location: /dashboard');
                    }else{
                        Usuario::setAlerta('error','Contraseña Incorrecta');
                    }
                }
            }
        $alertas=Usuario::getAlertas();
        }
        $router->render('auth/login',[
            'titulo'=>'Iniciar Sesión',
            
            'alertas'=>$alertas
        ]); 
    }
    public static function logout(){
        session_start();
        $_SESSION=[];
        header('Location:/');

    }
    public static function crear(Router $router){

        $usuario= new Usuario;
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $usuario->sincronizar($_POST);
            
            $alertas= $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario=Usuario::where('email', $usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas=Usuario::getAlertas();
                }else {
                    //hashear password
                    $usuario->hashPassword();
                    //eliminar password2

                    unset($usuario->password2);

                    //token

                    $usuario->crearToken();

                    
                    $resultado=$usuario->guardar();

                    $email=new Email($usuario->email, $usuario->nombre, $usuario->token);
                    
                    $email->enviarConfirmacion();

                    if($resultado){
                        header('Location: /mensaje');
                    }

                }
            
            }
            
        }

         $router->render('auth/crear',[
            'titulo'=>'Crear Cuenta',
            'usuario'=>$usuario,
            'alertas'=>$alertas
        ]);
    }
    public static function olvide(Router $router){
        
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario=new Usuario($_POST);
            $alertas=$usuario->validarEmail();

            if(empty($alertas)){
                $usuario=Usuario::where('email',$usuario->email);
                if($usuario && $usuario->confirmado==1){
                    //generar nuevo token

                    $usuario->crearToken();

                    //eliminar password 2

                    unset($usuario->password2);

                    //guardar

                    $usuario->guardar();

                    $email=new Email($usuario->email, $usuario->nombre,$usuario->token);
                    $email->enviarInstrucciones();
                    
                    //imprimir alerta

                    Usuario::setAlerta('exito', 'Se enviaron las instrucciones a tu email');

                    
                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                    
                }
                
            }
        }
        $alertas=Usuario::getAlertas();

        $router->render('auth/olvide',[
            'titulo'=>'Recuperar contraseña',
            'alertas'=>$alertas
        ]);
    }
    public static function restablecer(Router $router){

        $token=s($_GET['token']);
        $alertas=[];
        $mostrar=true;
        if(!$token){
            header('Location: /');
        }
        
        $usuario=Usuario::where('token',$token);
        

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no válido');
            $mostrar=false;
        }


        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            
            $usuario->sincronizar($_POST);

            $alertas=$usuario->validarPassword();

            if(empty($alertas)){
                
                $usuario->hashPassword();
                $usuario->token=null;
                unset($usuario->password2);
                $resultado=$usuario->guardar();

                if($resultado){
                    Usuario::setAlerta('exito', 'Tu contraseña fue cambiada con éxito');
                    $mostrar=false;
                }
                
            }
            
            
        }

        $alertas=Usuario::getAlertas();
        $router->render('auth/restablecer',[
            'titulo'=>'Restablecer',
            'alertas'=>$alertas,
            'mostrar'=>$mostrar
        ]);

    }
    public static function mensaje(Router $router){
        $router->render('auth/mensaje',[
            'titulo'=>'Mensaje'
        ]);

    }
    public static function confirmar(Router $router){

        $token=s($_GET['token']);
        $alertas=[];
        if(!$token){
            header('Location:/');
        }

        $usuario= Usuario::where('token',$token);

        if(empty($usuario)){
            Usuario::setAlerta('error','Token no válido o expirado, porfavor, vuelve a intentarlo');
        }else{
            Usuario::setAlerta('exito','Gracias por confirmar tu cuenta, Inicia Sesión para ingresar');
            $usuario->confirmado=1;
            unset($usuario->password2);
            $usuario->token=null;
            $usuario->guardar();
            
        }

        $alertas=Usuario::getAlertas();
        
        $router->render('auth/confirmar',[
            'titulo'=>'Confirmaste tu cuenta',
            'alertas'=>$alertas
        ]);

    }

}
