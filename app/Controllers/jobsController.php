<?php

namespace App\Controllers;
use App\Models\Job;
use Respect\Validation\Validator as Validator;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;
use App\Services\JobService;

class JobsController extends BaseController {
    private $jobService;

    public function __construct(JobService $jobService){
        parent::__construct();
        $this->jobService = $jobService;
        //el primero es el de al variable, el segundo es el del parametro
    }

    public function indexAction(){
        //$jobs = Job::all();
        //para traer todos objetos

        $jobs = job::withTrashed()->get();
        //esto para traer los objetos dentro borrados con el softDelete
        return $this->renderHTML('jobs/index.twig', compact('jobs'));
        //compact si pasamos los nombres agrega en un arreglo todas
        //las variables.
    }
    /**-------------------------------deleteJob */
    public function deleteAction(ServerRequest $request){
       $params = $request->getQueryParams();
       //Que le de al request solo el id=QueryParamns()

       $this->jobService->deleteJob($params['id']);

       return new RedirectResponse('/jobs');
       // hace un redireccionamiento.
    }
    /**-----------------------------forceDeleteJob */
    public function forceDeleteAction(ServerRequest $request){
        $params = $request->getQueryParams();
        //Que le de al request solo el id=QueryParamns()
        $this->jobService->forceDeleteJob($params['id']);
        //mandamas el id por parametro hacia el metodo dentro del jobService
        return new RedirectResponse('/jobs');
        // hace un redireccionamiento.
     }
     /**-----------------------------restoreJob */
     public function restoreAction(ServerRequest $request){
        $params = $request->getQueryParams();
        $this->jobService->restoreJob($params['id']);
        return new RedirectResponse('/jobs');
     }
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
           
            $job = new Job();
            $job->title = $postData['title'];
            $job->description = $postData['description'];
            $job->months = $postData['months'];
            $job->img = $filePath;
            $job->save();

            $responseMessage = 'Saved';
            }catch(\Exception $e){
                $responseMessage = $e->getMessage();
            }
            

            
           
        }

        return $this->renderHTML('jobs/addJob.twig', [
            'responseMessage' => $responseMessage
        ]);
    }
} 