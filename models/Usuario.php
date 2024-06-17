<?php 

namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla='usuarios';
    protected static $columnasDB=['id','nombre','email','password','token','confirmado'];
    


    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $password_confirmado;
    public $token;
    public $confirmado;


    public function __construct($args=[]){
        $this->id=$args['id'] ?? null;
        $this->nombre=$args['nombre'] ?? '';
        $this->email=$args['email'] ?? '';
        $this->password=$args['password'] ?? '';
        $this->password2=$args['password2'] ?? '';
        $this->password_actual=$args['password_actual'] ?? '';
        $this->password_nuevo=$args['password_nuevo'] ?? '';
        $this->password_confirmado=$args['password_confirmado'] ?? '';
        $this->token=$args['token'] ?? '';
        $this->confirmado=$args['confirmado'] ?? 0;

    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]="El email es obligatorio";
        }
        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]='El  email no es válido';
        }
        if(!$this->password){
            self::$alertas['error'][]="La contraseña es obligatoria";
        }

        return self::$alertas;
    }

    public function validarNuevaCuenta (){
        if(!$this->nombre){
            self::$alertas['error'][]="El nombre es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][]="El email es obligatorio";
        }
        if(!$this->password){
            self::$alertas['error'][]="La contraseña es obligatoria";
        }
        if(strlen($this->password) < 8) {
        self::$alertas['error'][] = "La contraseña debe tener al menos 8 caracteres";
        }
        if(!preg_match('/[A-Z]/', $this->password)) {
            self::$alertas['error'][] = "La contraseña debe tener al menos una letra mayúscula";
        }
        if(!preg_match('/[a-z]/', $this->password)) {
            self::$alertas['error'][] = "La contraseña debe tener al menos una letra minúscula";
        }
        if(!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $this->password)) {
            self::$alertas['error'][] = "La contraseña debe tener al menos un carácter especial";
        }
        if($this->password!==$this->password2){
            self::$alertas['error'][]="Las contraseñas no coinciden";
        }


        return self::$alertas;
    }

    public function hashPassword(){
        $this->password=password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken(){
        $this->token= md5(uniqid());
    }

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][]='El campo email es obligatorio';
        }

        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]='El  email no es válido';
        }

        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]="La contraseña es obligatoria";
        }
        if(strlen($this->password) < 8) {
        self::$alertas['error'][] = "La contraseña debe tener al menos 8 caracteres";
        }
        if(!preg_match('/[A-Z]/', $this->password) || !preg_match('/[a-z]/', $this->password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $this->password)) {
            self::$alertas['error'][] = "La contraseña debe tener al menos una letra mayúscula, una letra minúscula y un carácter especial";
        }
        if($this->password!==$this->password2){
            self::$alertas['error'][]="Las contraseñas no coinciden";
        }


        return self::$alertas;
    }

    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        return self::$alertas;
    }

    public function nuevo_password(){
        if(!$this->password_actual){
            self::$alertas['error'][]='el campo de contraseña actual no puede estar vacio';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][]='el campo de contraseña nueva no puede estar vacio';
        }
        if(strlen($this->password_nuevo) < 8) {
        self::$alertas['error'][] = "La contraseña debe tener al menos 8 caracteres";
        }

        if(!preg_match('/[A-Z]/', $this->password_nuevo) || !preg_match('/[a-z]/', $this->password_nuevo) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $this->password_nuevo)) {
            self::$alertas['error'][] = "La contraseña debe tener al menos una letra mayúscula, una letra minúscula y un carácter especial";
        }
        if(!$this->password_confirmado){
            self::$alertas['error'][]='Porfavor, Confirma tu contraseña';
        }
        
        return self::$alertas;
    }

    public function comprobar_password() : bool{
        return password_verify($this->password_actual,$this->password);
    }

    public function comprobar_confirmacion(){
        if($this->password_nuevo !== $this->password_confirmado){
            self::$alertas['error'][]='Las Contraseña Nueva y su confirmación no coinciden';
        }
        return self::$alertas;
    }
}