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
		@if($business=='0')
			<div class="row">
			<a style="margin: 10px 0;" href="#" class="col s12 m12 waves-effect waves-white">
				 No existe Datos
			</a>
			</div>
		@else
		<form action="/roomer/reservar-go-days" method="get" accept-charset="utf-8">			
			<div class="row">
				<div class="input-field col s12" style="margin-left: 0; padding-left: 0;">
					<input id="fecha_reserva" name="fecha_reserva" type="text" class="validate" style="height: 53px; font-size: 45px; font-weight: 800;border-bottom: 1px solid #FFFFFF;">				 
					<label for="username" style="left:0; color:white">Fecha</label>
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
				<ul id="items_date" class="items_date">
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>
					<li><a>00:00</a></li>				 
				</ul>
			</div>
			<div class="row">
				<input id="business_id" name="business_id" value="{{ $business_id }}" type="hidden">
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
			

		});
	</script>
@stop