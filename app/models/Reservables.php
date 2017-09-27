<?php
class Reservables extends Eloquent {

	protected $table = 'reservables';
	public $timestamps = true;

    public static function state($id)
    {
    	$hotel = Hotel::where('user_id', Sentry::getUser()->id)->first();
    	$reservables = Reservables::find($id);
        $langs = LanguageHotel::where('state', 1)->where('hotel_id', $hotel->id)->get();
        foreach($langs as $lang){
        	$name = ReservablesLang::where('language_id', $lang->language_id)->where('Reservables_id', $reservables->id)->first();
        	if(!$name)
        		return false;
        	if($name->name=="")
        		return false;
        }

        return true;
    }

    public function business()
    {
        return $this->belongsTo('business');
    }
}