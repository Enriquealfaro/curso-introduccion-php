<?php

namespace App\Controllers;
use App\Models\Project;
use Respect\Validation\Validator as Validator;

class ProjectsController extends BaseController {
    public function getAddProjectAction($request)  {

        //var_dump((string)$request->getBody());
        //trae el objeto con toda la cadena de informacion 
        //var_dump($request->getParsedBody());
        //trae el array con los elementos enviados

        $responseMessage = null;

        if($request->getMethod() == 'POST'){
            $postData = $request->getParsedBody();
            $projectValidator = Validator::key('title', Validator::stringType()->notEmpty())
                ->key('description', Validator::stringType()->notEmpty());
            //atribute:para valirar los elementos de un objeto
            //Key: los miembros de un arreglo
            try{
            $projectValidator->assert($postData);
            $postData = $request->getParsedBody();
            //getParseBody es un objeto tipo arreglo con
            //los valores enviados

            $files = $request->getUploadedFiles();
            //requesame todos los archivos subidos
            $logo = $files['logo'];

            $filePath = "";
            if($logo->getError() == UPLOAD_ERR_OK){
                $fileName = $logo->getClientFileName();
                $filePath = "uploads/$fileName";
                $logo->moveTo($filePath);
                
            }
           
            $project = new Project();
            $project->title = $postData['title'];
            $project->description = $postData['description'];
            $project->img = $filePath;
            $project->save();

            $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();
            }
            

            
           
        }

        return $this->renderHTML('addProject.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
} 