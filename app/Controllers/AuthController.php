<?php
namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as Validator;
use Zend\Diactoros\Response\RedirectResponse;

class AuthController extends BaseController {
    public function getLogin(){
        return $this->renderHTML('login.twig');   
    
    }
    public function postLogin($request){
        $postData = $request->getParsedBody();
        $responseMessage = null;

        $user = User::where('email', $postData['email'])->first();
        if($user){
           if(\password_verify($postData["password"], $user->password)){
               $_SESSION['userId'] = $user->id;
                return new RedirectResponse('/Cursos/IntroduccionPHP/admin');
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
        return new RedirectResponse('/Cursos/IntroduccionPHP/login');
    }

}
