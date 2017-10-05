<?php $template = $hotel->theme; ?>

@extends("roomers.themes.$template.master_reservas")

@section('title', trans('main.catalog'))

@section('container')
<style>
	.card-image img {
	     width: 100%;
    	height: 125px;
    	display: block;
	}

	span.card-title {
	    font-size: 1.3rem;
	    padding: 12px 0px 7px 0px;
	    display: block;
	}
	 
	.textP{
		color:White;    line-height: 1.25;    text-align: left;
	}
	.textP span{
		font-size:18px;
	}	
	.textP i.fa {
		margin-right: 15px;
	}

	.items_date{

	}

	.items_date li{
		display: block; 
		float:left;
		margin:5px 10px 0 0;
		font-size: 14px;
		font-weight: 700;
		cursor: pointer;				
	}

	.items_date li a{
		border: 1px solid black;
		width: 100%;
		height: 100%;
		display: block;
		padding:3px 8px;
	}
	.items_date li a:active, .items_date li a:focus{
		border: 1px solid red;
	} 

	.items_date li a:visited{
		border: 1px solid yellow;
	}
	
	
	.items_date li a.select_li{
		border: 1px solid red;
	}

</style>

<div id="welcome" class="col s12 m8 offset-m2">
	<div class="card-panel teal box " style="position:relative">
		
		<?php 
			$template = $hotel->theme;
			$back = "/roomer/servicios-list/".$business->service_id;
		?>

		@include("roomers.themes.$template.partials.navBar-services")
		@include("roomers.themes.$template.partials.header")			
		<!--<div  class="col s12 m12">
			<h5 style="padding: 0;margin: 0 0 20px;" class="center">!! {{$lang->txt_catalogo}}</h5>
		</div>-->
		@if (Session::has('message'))
          <label class="color" style="font-size: 20px;color: white;background-color: rgba(0, 204, 23, 0.29);">{{ Session::get('message') }}</label>
        @endif
		@if($business=='0')
			<div class="row">
			<a style="margin: 10px 0;" href="#" class="col s12 m12 waves-effect waves-white">
				 No existe Datos
			</a>
			</div>
		@else
		<form action="/roomer/reservar-save" method="post" accept-charset="utf-8">			
			
			<div class="row">
				<div class="input-field col s12" style="margin-left: 0; padding-left: 0;">
				    <select id="bussines_id" name="bussines_id">
				      <option value="" disabled >Bussines</option>
				      @foreach($businesss as $busi)
			          	<?php 
                             $busiLang = BusinessLang::where('business_id', $busi->id)->where('language_id', $lang->id)->first();
                            $bussine_select =$business->id;
				  	    ?>
				  	    @if($busi->id==$bussine_select)
				      	<option selected value="{{$busi->id}}">{{$busiLang->name}}</option>
				      	@else
				      	<option value="{{$busi->id}}">{{$busiLang->name}}</option>
				      	@endif
				      @endforeach
				    </select>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12" style="margin-left: 0; padding-left: 0;">
				    <select id="reservables_id" name="reservables_id">
				      <option value="" disabled selected>Reservable</option>
				      @foreach($menus as $menu)
			          	<?php 
                             $menuLang = ReservablesLang::where('reservables_id', $menu->id)->where('language_id', $lang->id)->first();
                            
				  	    ?>
				      <option value="{{$menu->id}}">{{$menuLang->name}}</option>

				      @endforeach
				    </select>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12" style="margin-left: 0; padding-left: 0;">
					<input id="fecha_reserva" name="fecha_reserva" type="text" class="validate" style="height: 53px; font-size: 45px; font-weight: 800;border-bottom: 1px solid #FFFFFF;">				 
					<label for="username" style="left:0; color:white">Fecha</label>
				</div>
			</div>
			<div class="row">
				<ul id="items_date" class="items_date">
									 
				</ul>
			</div>
			<div class="row">
				<input id="business_id" name="business_id" value="{{ $business_id }}" type="hidden">
				<input id="hora" name="hora" value="" type="hidden">
				<button type="submit">Enviar</button>
			</div>
		</form>
		@endif
		 
	</div>
</div>
@stop

@section('script')

	<script>
		$(function(){
			var ul = $('#items_date');
			$('#items_date li').on('click', 'a', function(event) {
				event.preventDefault();
				/* Act on the event */
				 	$( "#items_date li" ).each(function( index ) {
					   $(this).find('a').removeClass('select_li')
					});
				$(this).addClass('select_li');
			});
				$('#reservables_id').on('change',function(){

					if($('#fecha_reserva').val()!=''){
						$.get(shopcar.url+'/roomer/reservar-go-days', 
		              	{
		              	  	bussines_id: $('#bussines_id').val(), 
		              	  	reservables_id: $('#reservables_id').val(), 
		              	  	fecha_reserva: $('#fecha_reserva').val(), 
		              	  	business_id: $('#bussines_id').val()                    	  
		              	}, 
			            function(data, textStatus, xhr) {
				           	var rick = JSON.parse(data);
				           	$('#items_date').html('');
							$.each(rick, function(i, item) {
							   $('#items_date').append('<li><a>'+item+'</a></li>') 
							});
							var ul = $('#items_date');
							$('#items_date li').on('click', 'a', function(event) {
								event.preventDefault();
								/* Act on the event */
								 	$( "#items_date li" ).each(function( index ) {
									   $(this).find('a').removeClass('select_li')
									});
								$(this).addClass('select_li');
								$('#hora').val($(this).text());
							});	                  		
						});
					}else{
						console.log('Llene Fecha Reservable');
					}
				});

				$('#bussines_id').on('change',function(){
					$.get(shopcar.url+'/roomer/negocios/'+$(this).val(), 		            
			        function(data, textStatus, xhr) {
			        	
			        	$('#reservables_id').material_select('destroy');
				       	
				       	
				       	$('#reservables_id').html(data);
			        	
			        	$('#reservables_id').material_select();

					});
				});
				
		});
	</script>
@stop