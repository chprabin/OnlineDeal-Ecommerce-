<?php
namespace App\Components;
class Payment{
    public $PAYMENT_URL='http://localhost:8000/payment-done';
    public $NOPAYMENT_URL='http://localhost:8000/payment-fail';
    public $PAYEE_ACCOUNT='U27359375'; 
    public $PAYEE_NAME='Prabin Chapagain';
    public $PAYMENT_AMOUNT;
    public $PAYMENT_UNITS='USD';
    public $BAGGAGE_FIELDS='firstname lastname userId countryId total city street email phone';
    public $firstname;
    public $lastname;
    public $userId;
    public $countryI;
    public $total;
    public $city;
    public $street;
    public $email;
    public $phone;

    public function setData(array $data){
        foreach($data as $n=>$v){
            $this->$n=$v;
        }   
        return $this;
    }

    public function getData(){
        return get_object_vars($this);
    }
        
}