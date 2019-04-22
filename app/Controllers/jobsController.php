<?php

namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator as Validator;

class JobsController extends BaseController {
    public function getAddJobAction($request)  {

        //var_dump((string)$request->getBody());
        //trae el objeto con toda la cadena de informacion 
        //var_dump($request->getParsedBody());
        //trae el array con los elementos enviados

        $responseMessage = null;

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $jobValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty());
            //atribute:para valirar los elementos de un objeto
            //Key: los miembros de un arreglo
            try{
            $jobValidator->assert($postData);
            $postData = $request->getParsedBody();

            $files = $request->getUploadedFiles();
            $logo = $files['logo'];

            if($logo->getError() == UPLOAD_ERR_OK){
                $fileName = $logo->getClientFileName();
                $logo->moveTo("uploads/$fileName");
                
            }
            $fileName = $logo->getClientFileName();

            
            $job = new Job();
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            
            if(Empty($fileName)){
                $job->img = "public/uploads/iconoRoto.jpg";
            }else{
                $job->img = "public/uploads/$fileName";
            }
            $job->save();

            $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();
            }
            

            
           
        }

        return $this->renderHTML('addJob.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
} 