<?php

namespace App\Controllers;

use App\Models\{Job,Project, User};

class IndexController extends BaseController{
    public function indexAction(){
        $jobs = Job::all();
        $projects = Project::all();
        $users = user::all();

        // $project1 = new Project('Project 1', 'Description 1');
        // $project = [
        //     $project1
        // ];

        $LimitMonths = 15;
               /******************** Array_filter ********************/
        //Lo malo de este tipo de filtro esque convierte al objeto en
        //un array haciendo que no reconosta los metodos que tiene o que
        //puede heredar

        // podemos utilizar use dentro de un closure para llamar una variable 
        // fuera de nuestro scope.
 
        // $filterFunction = function(array $jobs) use ($LimitMonths){
        //     return $jobs['months'] >= $LimitMonths;
        // };

        // //hacer una function sobre cada elemento del arreglo, si trae
        // //un true lo agrega al nuevo array, si el false lo elemina
        // $jobs = array_filter($jobs->toArray(), $filterFunction);
            /******************** Array_filter ********************/

        foreach ($jobs as $key => $job){
            if($job -> months <= $LimitMonths){
                unset($jobs[$key]);
            }
        }

        $name = 'Hector Benitez';

        return $this->renderHTML('index.twig', [
            'name' => $name,
            'jobs' => $jobs,
            'projects' => $projects,
            'Users' => $users
        ]);

        // return $this->renderHTML('index.twig', [
        //     'name' => $name,
        //     'projects' => $projects
        // ]);

    }
}