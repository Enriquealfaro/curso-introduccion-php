<?php

namespace App\Services;

use App\Models\Job;

class JobService{
    /*-------------------------Delete */
    public function deleteJob($id){
        $job = Job::find($id);
        //Que cargue en job el objeto con el id igual al del params
        $job->delete();
        //aqui lo borra con delete que es un variable elocuent.
    }
    /*-------------------------forceDelete */
    public function forceDeleteJob($id){
        $job = Job::withTrashed()->where('id', $id);
        //Que cargue en job el objeto con el id igual al del params
        $job->forceDelete();
        //Con esto se borra el objeto permanenteme
    }
    /*-------------------------restore */
    public function restoreJob($id){
        $job = Job::withTrashed()->where('id',$id);
        //Que cargue en job el objeto con el id igual al del params
        $job->restore();
        //aqui lo restaura 
    }
}