<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{


    public static function login(Router $router)
    {

        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);

           $alertas = $auth->validarLogin();

           if(empty($alertas)){
            //compronar si el usario existe

            $usuario = Usuario::where('email',$auth->email);

            if($usuario){
                //verificar el password

                if($usuario->comprobarPasswordAndVerificado($auth->password)){

                    //autenticar el susario

                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //redireccionamiento

                    if($usuario->admin === "1"){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');


                    }else{
                        header('Location: /cita');
                    }



                }

            }else{
                Usuario::setAlerta('error','Usuario no encontrado');
            }

           }

           $alertas = Usuario::getAlertas();
        }
        
        
        $router->render('auth/login',[
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router)
    {

        $alertas = [];

              
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);
            $auth->validarEmail();

            if(empty($alertas)){

                $usuario = Usuario::where('email',$auth->email);

                if($usuario && $usuario->confirmado === "1"){

                        //generar token de un solo uso

                        $usuario->crearToken();

                        $usuario->guardar();

                        //TODO: enviar el email
                        $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                        $email->enviarInstrucciones();




                        //alerta de exito
                        Usuario::setAlerta('exito','Revisa tu email');
                      

                }else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confirmado');
                    $alertas = Usuario::getAlertas();
                }

                 $alertas = Usuario::getAlertas();

                

            }

        
        }


    $router->render('/auth/olvide-password',[

        'alertas' => $alertas

    ]);
        
    }

    
    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;


        $token = s($_GET['token']);
        //buscar usuario por su token

        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){

            Usuario::setAlerta('error','Token no valido');
            $error = true;

        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //LLER EL NUEVO PASSWORD Y GUARDARLO

            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }

                
            }


        }



        $usuario = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error



        ]);
    }

    public static function crear(Router $router)
    {

         $usuario = new Usuario;
         $alertas= [];


        if($_SERVER['REQUEST_METHOD'] === 'POST'){

           

            $usuario->sincronizar($_POST);
            $alertas =$usuario->validarNuevaCuenta();

            //revisar si laertas esta vacion

            if(empty($alertas)){
                    
                $resultado = $usuario->exiteUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //no esta registrado
                    //hashear password

                    $usuario->hashPassword();

                    //generar token unico

                    $usuario->crearToken();

                    //enviar email

                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);

                    $email->enviarConfirmacion();

                    //crear elk suario

                    $resultado = $usuario->guardar();

                    if($resultado){

                        header('Location: /mensaje');

                    }



                
                }
                
            }

            

        }


        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);
        
    }

    public static function mensaje(Router $router){


        $router->render('auth/mensaje');
    }



    public static function confirmar(Router $router){

    $alertas = [];
    $token = s($_GET['token']);
    $usuario = Usuario::where('token',$token);

    if(empty($usuario)){
        //mostarar laertas

        Usuario::setAlerta('error','Token no valido');

    }else{  
        //nodificar token

        $usuario->confirmado = "1";
        $usuario->token = null;
        $usuario->guardar();
        Usuario::setAlerta('exito','Usuario confirmado correctamente');

    
    }





        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[

            'alertas' => $alertas

        ]);
        

    

    }



}
