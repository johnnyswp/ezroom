<?php
class HotelReservasController extends \BaseController {
	
	public function index()
	{
		if(Sentry::getUser()->type_user!=3){ return View::make('404');}

        if(Payment::DisabledPayment()==false)
           return View::make('hotel.Payment.renews-payment');

        $hotel = Hotel::where('user_id', Sentry::getUser()->id)->first();
        $lang = LanguageHotel::where('main', 1)->where('hotel_id', $hotel->id)->first();

        $business = array(''=>trans('main.Seleccione un business'));
        $businessAll = Business::where('hotel_id', $hotel->id)->orderBy('businessOrder', 'ASC');
        foreach($businessAll->get() as $busines)
        {
            $businessLang = BusinessLang::where('business_id', $busines->id)->where('language_id', $lang->language_id)->first();
            $business[$busines->id] = $businessLang->name;
        }

        if(Input::has('business')){
            $first_business = Input::get('business');
        }else{
            $first_business = Business::where('hotel_id', $hotel->id)->orderBy('businessOrder', 'ASC')->first();
            if($first_business)
                $first_business = $first_business->id;
            else
                $first_business ='';
        }
        
        $menus = Reservables::where('business_id', $first_business)->where('hotel_id', $hotel->id)->orderBy('reservablesOrder', 'ASC')->get();
        return View::make('hotel.pages.reservas.menus_business')->with(array('menus'=>$menus, 'lang'=>$lang, 'business'=>$business, 'first_business'=>$first_business));
	}

	public function create()
	{
		if(Sentry::getUser()->type_user!=3){ return View::make('404');}

        if(Payment::DisabledPayment()==false)
           return View::make('hotel.Payment.renews-payment');

        $hotel = Hotel::where('user_id', Sentry::getUser()->id)->first();
        $lang = LanguageHotel::where('main', 1)->where('hotel_id', $hotel->id)->first();
        $lang_active = LanguageHotel::where('hotel_id', $hotel->id)->orderBy('main', 'DESC')->orderBy('state', 'DESC');

        $business = array(''=>trans('main.Seleccione un business'));
        $businessAll = Business::where('hotel_id', $hotel->id)->orderBy('businessOrder', 'ASC');
        foreach($businessAll->get() as $busines)
        {
            $businessLang = BusinessLang::where('business_id', $busines->id)->where('language_id', $lang->language_id)->first();
            $business[$busines->id] = $businessLang->name;
        }
        $weekdays = array('0'=>'Domingo', '1'=>'Lunes', '2'=>'Martes', '3'=>'Miercoles', '4'=>'Jueves', '5'=>'Viernes', '6'=>'Sabado');

        return View::make('hotel.pages.reservas.alta_menu_business')->withHotel($hotel)
                                                  ->withLangs($lang_active->get())
                                                  ->withBusiness($business)
                                                  ->withWeekdays($weekdays);
    }

    public function store()
    {
        $hotel = Hotel::where('user_id', Sentry::getUser()->id)->first();
        $lang_main = LanguageHotel::where('main', 1)->where('hotel_id', $hotel->id)->first();
        $langs = LanguageHotel::where('hotel_id', $hotel->id);

        $data = array(
            "business_id"   =>  Input::get("business_id"),
            "price"         =>  Input::get("price"),
            "picture"       =>  Input::file("picture")
        );

        $data[$lang_main->language->language] = Input::get($lang_main->language->language);
        $data['descrption_'.$lang_main->language->language] = Input::get('descrption_'.$lang_main->language->language);

        $rules = array(
            "business_id"   =>  'required|min:1|max:255',
            "price"         =>  'required|numeric|between:0,99999.99',
            "picture"       =>  'mimes:jpeg,gif,png'
        );

        $rules[$lang_main->language->language]  = 'required|min:1|max:255';
        $rules['descrption_'.$lang_main->language->language]  = 'min:1|max:255';

        $messages = array();

        $validation = Validator::make(Input::all(), $rules, $messages);
             //si la validación falla redirigimos al formulario de registro con los errores
            //y con los campos que nos habia llenado el usuario
        if ($validation->fails())
        {
            return Redirect::back()->withErrors($validation)->withInput();
        }else{
            $hotel = Hotel::where('user_id', Sentry::getUser()->id)->first();
            $item = new Reservables;
            $item->state = 0;
            $item->hotel_id = $hotel->id;
            $item->price = Input::get('price');
            $item->business_id = Input::get('business_id');
            $item->reservablesOrder = 0;

            if(Input::file('picture')!=NULL)
            {
                //agrega imagen de picture
                $file_picture=Input::file('picture');
                $ext = Input::file('picture')->getClientOriginalExtension();
                $nameIMG=date('YmdHis');
                $picture= $hotel->id.$nameIMG.'.'.$ext;
                $picname = $picture;
                $picture= url().'/assets/pictures_hotels/item/PIC'.$picture;
                $item->picture = $picture;
            }else{
                $item->picture = url().'/assets/img/no-image.png';
            }

            if($item->save()){
                if(Input::file('picture')!=NULL)
                {
                    $file_picture->move("assets/pictures_hotels/item/",$picture); 
                    $path = base_path();
                    $in = $path.'/assets/pictures_hotels/item/PIC'.$picname;
                    $out     = $path.'/assets/pictures_hotels/item/PIC'.$picname;
                    Img::resize($in , null, 100 , 100 , false , $out , true , false ,100 );
                }

                foreach($langs->get() as $lang)
                {
                    if(Input::has($lang->language->language) or Input::has('descrption_'.$lang->language->language))
                    {
                        $name_item = new ReservablesLang;
                        $name_item->name = Input::get($lang->language->language);
                        $name_item->description = Input::get('descrption_'.$lang->language->language);
                        $name_item->reservables_id = $item->id;
                        $name_item->language_id = $lang->language->id;
                        $name_item->save();
                    }
                }

                $weekdays = array('0'=>'Domingo', '1'=>'Lunes', '2'=>'Martes', '3'=>'Miercoles', '4'=>'Jueves', '5'=>'Viernes'    , '6'=>'Sabado');
                foreach($weekdays as $weekday => $value)
                {
                    if(Input::get('desde_1_'.$value) or Input::get('hasta_1_'.$value) or Input::get('desde_2_'.$value) or Input    ::get('hasta_2_'.$value))
                    {
                        $availble = new ReservablesAvailable;
                        $availble->reservables_id = $item->id;
                        $availble->weekday = $weekday;
                        $availble->desde_1 = Input::get('desde_1_'.$value);
                        $availble->hasta_1 = Input::get('hasta_1_'.$value);
                        $availble->desde_2 = Input::get('desde_2_'.$value);
                        $availble->hasta_2 = Input::get('hasta_2_'.$value);
                        $availble->save();
                    }
                }

                return Redirect::to('hotel/business/reservables?business='.$item->business_id)->withFlash_message(trans('main.Guardado Exitosamente'));
            }else{
                return Redirect::back()->withErrors(trans('main.Error'))->withInput();
            }
        }
    }

    public function edit($id)
    {
    	if(Sentry::getUser()->type_user!=3){ return View::make('404');}

        if(Payment::DisabledPayment()==false)
           return View::make('hotel.Payment.renews-payment');
    }

    public function update($id)
    {
    	if(Sentry::getUser()->type_user!=3){ return View::make('404');}

        if(Payment::DisabledPayment()==false)
           return View::make('hotel.Payment.renews-payment');
    }

    public function destroy($id)
    {
    	if(Sentry::getUser()->type_user!=3){ return View::make('404');}

        if(Payment::DisabledPayment()==false)
           return View::make('hotel.Payment.renews-payment');
    }
}