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

        $name = 'Hector Benitez';
        $limitMonths = 2000; 

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