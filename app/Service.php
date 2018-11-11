<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'cms_padsolicitudes';
    public $timestamps = false;

    public function user(){
    	// Regresa todos los datos del modelo User donde el usuario sea igual al 'user_id' proporcionado
    	return $this->belongsTo('App\User', 'partner_id');
    }

    public function car(){
    	// Regresa todos los datos del modelo User donde el usuario sea igual al 'user_id' proporcionado
    	return $this->belongsTo('App\Car', 'vehiculo_id');
    }

    public function payment(){
    	// Regresa todos los datos del modelo User donde el usuario sea igual al 'user_id' proporcionado
    	return $this->belongsTo('App\PaymentType', 'tipopago_id');
    }

    public function service(){
    	// Regresa todos los datos del modelo User donde el usuario sea igual al 'user_id' proporcionado
    	return $this->belongsTo('App\TypeService', 'tiposervicio_id');
    }
}
