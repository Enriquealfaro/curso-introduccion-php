<?php

namespace App\Controllers;
use App\Models\User;
use Respect\Validation\Validator as Validator;

class UsersController extends BaseController {
    public function getAddUserAction($request){

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $userValidator = Validator::key('email', Validator::stringType()->notEmpty())
            ->key('password', Validator::stringType()->notEmpty());         
            try{
                $userValidator->assert($postData);
                $postData = $request->getParsedBody();

                $user = new User();
                $user->email = $postData['email'];
                $password = $postData['password'];
                $user->password = password_hash("$password", PASSWORD_DEFAULT);
                $user->save();
                $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();
            }
        }

        return $this->renderHTML('addUser.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
}