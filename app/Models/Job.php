<?php
namespace App\Models;

use App\Traits\HasDefaultImage;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;
  

//agregando este use con el traits hace que toda la clase job tenga 
//ya las funciones del trair
class job extends Model  {
    use HasDefaultImage;
    use softDeletes;

    protected $table = 'jobs';

    public function getDurationAsString(){
        $year = floor($this->months / 12);
        $extraMonths = $this->months % 12;

        return "Job duration: $year year $extraMonths months";
    }

   
      
    
}