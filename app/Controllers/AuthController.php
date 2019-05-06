<?php
namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as Validator;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class AuthController extends BaseController {
    public function getLogin(){
        return $this->renderHTML('login.twig');   
    
    }
    public function postLogin(ServerRequest $request){
        $postData = $request->getParsedBody();
        $responseMessage = null;

        $user = User::where('email', $postData['email'])->first();
        //busque dentro de la tabla el email y traigame al primero
        if($user){
           if(\password_verify($postData["password"], $user->password)){
               $_SESSION['userId'] = $user->id;
               // sasbemos mediante el userID que hay una 
               //sesion iniciada
                return new RedirectResponse('/admin');
                //con esto hacemos que se redireccione mandando los datos por un header de HTTP
           }else{
            $responseMessage = 'Bad credentials'; 
           }
        }else{
            $responseMessage = 'Bad credentials';
        }

        return $this->renderHTML('login.twig', [
            'responseMassage' => $responseMessage
            ]);
    }
    public function getLogout(){
        unset($_SESSION['userId']);
        //unset sirve para eliminar un elemento asosiativo
        return new RedirectResponse('/login');
    }

}
