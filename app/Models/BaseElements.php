<?php
namespace App\Models;

require_once 'Printable.php';

class BaseElements implements Printable{
    protected $title;
    public $description;
    public $visible = true;
    public $months;

    public function __construct($title, $description){
        $this->setTitle($title);
        $this->description = $description;
    }

    public function setTitle($title){
        if($title == ''){
            $this->title = 'N/A';
        }else{
            $this->title = $title;
        }
    }
    public function getTitle(){
        return $this->title;
    }

    public function getDurationAsString(){
        $year = floor($this->months / 12);
        $extraMonths = $this->months % 12;
      
        if($year != 0){
          if($extraMonths != 0){
            echo "$year year $extraMonths months";
          }else{
            echo "$year year";
          }
        }else{
          echo " $extraMonths months";  
        }
      }

    public function getDescription(){
        return $this->description;
    }
}