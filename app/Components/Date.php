<?php
namespace App\Components;

class Date
{

    public static function isFuture($date)
    {
        $now=static::getNow();
        return $date > $now;
    }

    public static function getNow($format='Y-m-d H:i:s'){
        return date($format);
    }

    public static function isPast($date){
       $now=static::getNow();
       return $date < $now; 
    }

    public static function getMonthName($month_number=1){
     $monthes=static::getMonthes();
     return $monthes[$month_number];
    }

    public static function getMonthes(){
        return [
         '1'=>'january','2'=>'february','3'=>'march',
         '4'=>'april','5'=>'may','6'=>'june',
         '7'=>'july','8'=>'august','9'=>'september',
         '10'=>'october','11'=>'november','12'=>'decempber',
        ];
    }
    public static function getMonthNumber($month){
        foreach(static::getMonthes() as $number=>$m){
            if(strtolower($month)==$m){
                return $number;
            }
        }
        return null;
    }
    public static function buildFirstDayDate($month){
        $number=static::getMonthNumber($month);
        if(!$number){
            return null;
        }
        $number=(int)$number;
        if($number < 10){
            $number='0'.$number;
        }
        $year=date('Y');
        return $year.'-'.$number.'-01';
    }
    public static function buildLastDayDate($month){
        $number=static::getMonthNumber($month);
        if(!$number){
            return null;
        }
        $number=(int)$number;
        if($number < 10){
            $number='0'.$number;
        }
        $year=date('Y');
        return $year.'-'.$number.'-31';
    }
}
