
@extends('admin.master')
@section('title', 'Admin Dashboard')
@section('content')
<div id="content">
            <div class="row">
                        
                <div class="col-lg-12">
                    <section class="panel corner-flip">
                            <header class="panel-heading sm" data-color="theme-inverse">
                                <div class="pull-letf"><h2><strong>Registro de Frase en el lenguaje</strong>.</h2></div>
                            </header>
                            @if (Session::has('flash_message'))
                              <div class="form-group">
                                  <p>{{ Session::get('flash_message') }}</p>
                              </div>
                            @endif
                            @if (Session::has('error'))
                              <label class="label label-danger">{{ Session::get('error') }}</label>
                            @endif
                            <div class="panel-body">
                           {{Form::open(array('action' => 'AdminPhrasesLanguagesController@store','files'=>true))}}
                            
                            <div class="form-group">
                                <label class="control-label">Texto</label>
                                <div>
                                    <div class="row">
                                        <div class=" col-lg-12">
                                            {{ Form::text("name", NULL, ['class' => 'form-control']) }}
                                            {{ errors_for('name', $errors) }}
                                        </div>
                                    </div>
                                </div>
                            </div><!-- //form-group-->
                            <div class="form-group">
                                <label class="control-label">{{trans('main.languages')}}</label>
                                <div>
                                    {{ Form::select('language_id', $langs, null, ['class' => 'form-control selectpicker', 'data-size'=>'10', 'data-live-search'=>'true', 'autocomplete'=>'off']) }}
                                    {{ errors_for('language_id', $errors) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{trans('main.phrase')}}</label>
                                <div>
                                    {{ Form::select('phrase_id', $phrases, null, ['class' => 'form-control selectpicker', 'data-size'=>'10', 'data-live-search'=>'true', 'autocomplete'=>'off']) }}
                                    {{ errors_for('phrase_id', $errors) }}
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="col-md-12">
                                      <button type="submit"  class="btn btn-theme previous pull-left"> Guardar</button>
                                </div>
                            </div>
                        {{ Form::close() }}
                        </div>         
                        <!-- //content > row > col-lg-8 -->
                  </section>
                  <!-- //tabbable -->
              </div>
              <!-- //main-->
          </div>
          <!-- //main-->
      </div>
      <!-- //main-->
@stop