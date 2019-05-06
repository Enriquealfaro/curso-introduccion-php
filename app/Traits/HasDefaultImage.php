<?php

namespace App\Traits;

trait HasDefaultImage
{
    public function getImage($altText) {
        if(!$this->img){
            return "https://ui-avatars.com/api/?name=$altText&size=160";
        }else{
            return $this->img;
        }
        
    }
}