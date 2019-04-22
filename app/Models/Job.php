<?php
namespace App\Models;

use  Illuminate\Database\Eloquent\Model; 


class job extends Model  {
    protected $table = 'jobs';

    public function getDurationAsString(){
        $year = floor($this->months / 12);
        $extraMonths = $this->months % 12;

        return "Job duration: $year year $extraMonths months";
    }

   
      
    
}