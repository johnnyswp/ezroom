@extends('hotel.master') 

@section('title', trans('main.panel de control'))

@section('content')
<div id="content">
	
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
		<section class="panel">
			<header class="panel-heading sm" data-color="theme-inverse">
				<h2><strong> {{trans('main.alta')}}</strong>  {{trans('main.business reservables')}}</h2>
				@if (Session::has('flash_message'))
                <label class="color" style="color: white;background-color: rgba(0, 204, 23, 0.29);">{{ Session::get('flash_message') }}</label>
                @endif
			</header>
			<div class="panel-body">
				{{ Form::open(['files' => 'true', 'method' => 'POST', 'route' => ['hotel.business.reservables.store']]) }}
					
					<div class="form-group offset">
						<div>
							{{ Form::submit(trans('main.save'), ['class' => 'btn btn-primary']) }}
                            {{ Form::reset(trans('main.cancel'), ['class' => 'btn btn-default']) }}
						</div>
					</div>

					<div class="form-group">
                        <label class="control-label">{{trans('main.business')}}</label>
                        <div>
                            {{ Form::select('business_id', $business, null, ['class' => 'form-control selectpicker', 'data-size'=>'10', 'data-live-search'=>'true', 'autocomplete'=>'off']) }}
                            {{ errors_for('business_id', $errors) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">{{trans('main.price')}}</label>
                        <div>
                            {{ Form::text('price', null, ['class' => 'form-control', 'autocomplete'=>'off']) }}
                            {{ errors_for('price', $errors) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">{{trans('main.time')}}</label>
                        <div>
                            {{ Form::text('time', null, ['class' => 'form-control', 'autocomplete'=>'off']) }}
                            {{ errors_for('time', $errors) }}
                        </div>
                    </div>
                    
					<div class="form-group">
						<label class="control-label">{{trans('main.select a Picture')}}</label><br>
						<div>
							<div class="fileinput fileinput-exists" data-provides="fileinput">
								<input type="hidden" value="" name="">
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; line-height: 150px;">
									<img src="/assets/img/no-image.png" />
								</div>
							<div>
								<span class="btn btn-default btn-file">
									<span class="fileinput-new">{{trans('main.select image')}}</span><span class="fileinput-exists">{{trans('main.change')}}</span>
									<input type="file" name="picture">
								</span>
							<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">{{trans('main.delete')}}</a>
							</div>
						</div>
						<br>

						<span class="label label-danger ">{{trans('main.note')}}</span>
						<span>{{trans('main.you must select single files of')}}</span>
						</div>
					</div>

					<label class="control-label">{{trans('main.Nombre en idiomas activos')}}</label><br/>
					<?php $x=0; ?>
					@foreach($langs as $lang_)
					        @if($lang_->state==0 and $x==0)
                            <label class="control-label">{{trans('main.Nombre en idiomas no activos')}}</label><br/>
                            <?php $x=1; ?>
                            @endif
					        <label class="control-label">{{trans('main.Nombre en')}} {{$lang_->language->language}}</label>
					        <div class="form-group">
					        	<div>
					        		{{ Form::text($lang_->language->language, NULL, ['class' => 'form-control', 'placeholder'=>$lang_->language->language, 'autocomplete'=>'off']) }}
                                    {{ errors_for($lang_->language->language, $errors) }}
					        	</div>
					        </div>
					        <div class="form-group">
					        	<div>
					        		{{ Form::textarea('descrption_'.$lang_->language->language, NULL, ['class' => 'form-control', 'style'=>'height: 75px;','placeholder'=>trans('main.descrpcion en').' '.$lang_->language->language, 'autocomplete'=>'off']) }}
                                    {{ errors_for('descrption_'.$lang_->language->language, $errors) }}
					        	</div>
					        </div>
					@endforeach
                    
                    <div id="schedule">
                    <label class="control-label">{{trans('main.Horario de disponibilidad')}}</label><br/>
                    @foreach($weekdays as $weekday => $value)
                    <div class="col-lg-12" style="border-bottom: 1px solid #E4E4E4; margin-bottom: 10px;">
                        <label class="control-label col-lg-12">{{trans('main.'.$value)}}</label>
					    <div class="col-lg-3">
					        <label class="control-label">{{trans('main.Desde')}} 1</label>
					    	<div class="form-group">
					    		{{ Form::text('desde_1_'.$value, NULL, ['class' => 'form-control timepicker', 'placeholder'=>'Desde 1', 'autocomplete'=>'off']) }}
                                {{ errors_for('desde_1_'.$value, $errors) }}
					    	</div>
					    </div>
					    <div class="col-lg-3">
					        <label class="control-label">{{trans('main.Hasta')}} 1</label>
					    	<div class="form-group">
					    		{{ Form::text('hasta_1_'.$value, NULL, ['class' => 'form-control timepicker', 'placeholder'=>'Hasta 1', 'autocomplete'=>'off']) }}
                                {{ errors_for('hasta_1_'.$value, $errors) }}
					    	</div>
					    </div>
					    <div class="col-lg-3">
					        <label class="control-label">{{trans('main.Desde')}} 2</label>
					    	<div class="form-group">
					    		{{ Form::text('desde_2_'.$value, NULL, ['class' => 'form-control timepicker', 'placeholder'=>'Desde 2', 'autocomplete'=>'off']) }}
                                {{ errors_for('desde_2_'.$value, $errors) }}
					    	</div>
					    </div>
					    <div class="col-lg-3">
					        <label class="control-label">{{trans('main.Hasta')}} 2</label>
					    	<div class="form-group">
					    		{{ Form::text('hasta_2_'.$value, NULL, ['class' => 'form-control timepicker', 'placeholder'=>'Hasta 2', 'autocomplete'=>'off']) }}
                                {{ errors_for('hasta_2_'.$value, $errors) }}
					    	</div>
					    </div>
					</div>
                    @endforeach
                    </div>

                    <div class="form-group offset">
						<div>
							{{ Form::submit(trans('main.save'), ['class' => 'btn btn-primary']) }}
                            {{ Form::reset(trans('main.cancel'), ['class' => 'btn btn-default']) }}
						</div>
					</div>

				{{ Form::close() }}
			</div>
		</section>
		</div>

	</div>
	
</div>
@stop

@section('js-script')

$('.selectpicker').selectpicker();

$('.timepicker').timepicker({
    minuteStep: 1,
    template: 'modal',
    appendWidgetTo: 'body',
    showSeconds: true,
    showMeridian: false,
    defaultTime: false
});
@stop