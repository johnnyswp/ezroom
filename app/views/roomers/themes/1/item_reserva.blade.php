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
		<div class="row">
					<div class="input-field col s12" style="margin-left: 0; padding-left: 0;">
						<input id="fecha_reserva" name="fecha_reserva" type="text" class="validate" style="height: 53px; font-size: 45px; font-weight: 800;border-bottom: 1px solid #FFFFFF;">
						 
						<label for="username" style="left:0; color:white">Fecha</label>
					</div>
		</div>	
		@endif
		 
	</div>
</div>
@stop