<?php
class ReservablesDetalle extends Eloquent {

	protected $table = 'reservablesdetalles';
	public $timestamps = true;

	public function reservables()
	{
		return $this->hasMany('Reservables');
	}

}