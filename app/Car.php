<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
	protected $table = 'cms_padvehiculos';
    public $timestamps = false;

    public function user(){
    	// Regresa todos los datos del modelo User donde el usuario sea igual al 'user_id' proporcionado
    	return $this->belongsTo('App\User', 'partner_id');
    }

    public function brand(){
    	return $this->belongsTo('App\Brand', 'marca_id');
    }

    public function typecar(){
    	return $this->belongsTo('App\TypeCar', 'tipovehiculo_id');
    }

    public function color(){
    	return $this->belongsTo('App\Color', 'colorvehiculo_id');
    }
}
