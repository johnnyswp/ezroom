@extends('receptionists.master') 
<?php use Carbon\Carbon; ?>
@section('title', trans('main.reservaciones'))
@section('content')
<div id="check_in">	
	<div class="row">
		<div class="col-md-12">
			 <section class="panel">
			<header class="panel-heading sm" data-color="theme-inverse">
				<h2><strong>{{trans('main.reservaciones')}}</strong></h2>
        @if(Sentry::getUser()->type_user==3)
          @if(Payment::VeryPaymentMessage()==false)
             <label class="label label-danger">{{trans('main.message end plans')}}.  {{trans('main.date end')}} {{Carbon::parse(Payment::PaymentsDate())->format('d-m-Y')}}</label>
          @endif
        @endif
				@if (Session::has('flash_message'))
          <label class="color" style="color: white;background-color: rgba(0, 204, 23, 0.29);">{{ Session::get('flash_message') }}</label>
        @endif
        @if (Session::has('error'))
        <label class="color" style="font-size: 12px; color: red;">{{ Session::get('error') }}</label>
        @endif
			</header>
			<div class="panel-body">	

				<table class="table" id="sector_table">
					<thead>
						<tr>
							<td>{{trans('main.huesped')}}</td>
							<td>{{trans('main.reservable')}}</td>
                            <td>{{trans('main.Fecha')}}</td>
							<td>{{trans('main.hour')}}</td>
							<td>{{trans('main.state')}}</td>
                            @if(Helpers::typeU()==1 or Helpers::typeU()==3 or Helpers::typeU()==0)
							<td>{{trans('main.action')}}</td>
                            @endif
						</tr>
					</thead>
					<tbody>
					@foreach($reservas as $reserva)
                    <?php 
                    $menuLang = ReservablesLang::where('reservables_id', $reserva->reservables_id)->where('language_id', $lang->language_id)->first();
                    ?>
					<tr>
						<td>{{$reserva->stay}} </td>						
						<td>{{$menuLang->name}} </td>
						<td>{{$reserva->fecha}} </td>
						<td>{{$reserva->hora}}</td>
						<td>{{trans('main.status '.$reserva->state)}}</td>
			            @if(Helpers::typeU()==1 or Helpers::typeU()==3 or Helpers::typeU()==0)
                        <td>
			               <button class="btn btn-md btn-primary" type="submit" data-toggle="tooltip" data-placement="left" title="{{trans('main.confirmar')}}" onclick="var notice = new PNotify({
                            text: $('#form_notice_confirm_{{$reserva->id}}').html(),
                            icon: false,
                            width: 'auto',
                            hide: false,
                            addclass: 'custom',
                            icon: 'picon picon-32',
                            opacity: .8,
                            nonblock: {
                                nonblock: true
                            },
                            animation: {
                                effect_in: 'show',
                                effect_out: 'slide'
                            },
                            buttons: {
                              closer: false,
                              sticker: false
                            },
                            insert_brs: false
                            });
                            notice.get().find('form.pf-form').on('click', '[name=cancel]', function(){ notice.remove(); }).submit(function(){ $('#form_notice').submit(); });" @if($reserva->state==2) disabled @endif>Confirmar</button>
                            <div id="form_notice_confirm_{{$reserva->id}}" style="display: none;">
                              {{ Form::open(array('method'=>'get', 'class'=>'pf-form pform_custom','url' => 'receptionist/reservation-state/'.$reserva->id.'/2')) }}
                                <div class="pf-element pf-heading">
                                  <h3>{{trans('main.confime la reserva')}}</h3>
                                  <p></p>
                                </div>
                                <div class="pf-element pf-buttons pf-centered">
                                  <input class="pf-button btn btn-primary" type="submit" name="submit" value="{{trans('main.confirmar')}}" />
                                  <input class="pf-button btn btn-default" type="button" name="cancel" value="{{trans('main.cancelar')}}" />
                                </div>
                              {{ Form::close() }}
                            </div>

                            <button class="btn btn-md btn-theme" type="submit" data-toggle="tooltip" data-placement="left" title="{{trans('main.confirmar')}}" onclick="var notice = new PNotify({
                            text: $('#form_notice_cancel_{{$reserva->id}}').html(),
                            icon: false,
                            width: 'auto',
                            hide: false,
                            addclass: 'custom',
                            icon: 'picon picon-32',
                            opacity: .8,
                            nonblock: {
                                nonblock: true
                            },
                            animation: {
                                effect_in: 'show',
                                effect_out: 'slide'
                            },
                            buttons: {
                              closer: false,
                              sticker: false
                            },
                            insert_brs: false
                            });
                            notice.get().find('form.pf-form').on('click', '[name=cancel]', function(){ notice.remove(); }).submit(function(){ $('#form_notice').submit(); });" @if($reserva->state==4) disabled @endif>{{trans('main.cancel')}}</button>
                            <div id="form_notice_cancel_{{$reserva->id}}" style="display: none;">
                              {{ Form::open(array('method'=>'get', 'class'=>'pf-form pform_custom','url' => 'receptionist/reservation-state/'.$reserva->id.'/4')) }}
                                <div class="pf-element pf-heading">
                                  <h3>{{trans('main.reserva cancelada')}}</h3>
                                  <p></p>
                                </div>
                                <div class="pf-element pf-buttons pf-centered">
                                  <input class="pf-button btn btn-primary" type="submit" name="submit" value="{{trans('main.confirmar')}}" />
                                  <input class="pf-button btn btn-default" type="button" name="cancel" value="{{trans('main.cancelar')}}" />
                                </div>
                              {{ Form::close() }}
                            </div>

                            <button class="btn btn-info btn-transparent" type="submit" data-toggle="tooltip" data-placement="left" title="{{trans('main.confirmar')}}" onclick="var notice = new PNotify({
                            text: $('#form_notice_realizado_{{$reserva->id}}').html(),
                            icon: false,
                            width: 'auto',
                            hide: false,
                            addclass: 'custom',
                            icon: 'picon picon-32',
                            opacity: .8,
                            nonblock: {
                                nonblock: true
                            },
                            animation: {
                                effect_in: 'show',
                                effect_out: 'slide'
                            },
                            buttons: {
                              closer: false,
                              sticker: false
                            },
                            insert_brs: false
                            });
                            notice.get().find('form.pf-form').on('click', '[name=cancel]', function(){ notice.remove(); }).submit(function(){ $('#form_notice').submit(); });" @if($reserva->state==3) disabled @endif>Realizado</button>
                            <div id="form_notice_realizado_{{$reserva->id}}" style="display: none;">
                              {{ Form::open(array('method'=>'get', 'class'=>'pf-form pform_custom','url' => 'receptionist/reservation-state/'.$reserva->id.'/3')) }}
                                <div class="pf-element pf-heading">
                                  <h3>{{trans('main.reserva realizada')}}</h3>
                                  <p></p>
                                </div>
                                <div class="pf-element pf-buttons pf-centered">
                                  <input class="pf-button btn btn-primary" type="submit" name="submit" value="{{trans('main.confirmar')}}" />
                                  <input class="pf-button btn btn-default" type="button" name="cancel" value="{{trans('main.cancelar')}}" />
                                </div>
                              {{ Form::close() }}
                            </div>
			            </td>
                        @endif
					</tr>
					@endforeach
					</tbody>
				</table>
				{{$reservas->links()}}
			</div>
		</section>
		</div>

	</div>
	
</div>
@stop