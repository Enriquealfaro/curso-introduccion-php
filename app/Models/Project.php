<?php
namespace App\Models;

use App\Traits\HasDefaultImage;
use  Illuminate\Database\Eloquent\Model; 

class Project extends Model{
    use HasDefaultImage;
    protected $table = 'projects';

}