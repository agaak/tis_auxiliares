@extends('layout')

@section('content')
    <div class="container">
        {{-- Boton para crear una nueva convocatoria --}}
        <div class="row my-3">
            <a type="button" data-toggle="modal" data-target="#convocatoriaModal">
                <img src="{{ asset('img/addBLUE.png') }}" width="30" height="30">
                <span class="mx-1">Crear Convocatoria</span>
            </a>
        </div>
    </div>
    {{-- Moadal pra crear nueva convocatoria --}}
    {{-- conv = convocatoria --}}
    <div class="modal fade" id="convocatoriaModal" tabindex="-1" role="dialog" aria-labelledby="convModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="convModalTitle">Crear Nueva Convocatoria</h5>
                    <button type="button" class="modal-icon" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('convocatoria.store') }}" id="conv-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="conv-titulo">Título</label>
                            <textarea class="form-control" name="conv-titulo" id="conv-titulo"
                                rows="2" placeholder="Ingrese el título de la convocatoria" required minlength="30" maxlength="150">{{ old('conv-titulo') }}</textarea>
                            {{ $errors->first('conv-titulo') }}
                        </div>
                        <div class="form-group">
                            <label for="conv-descripcion">Descripción</label>
                            <textarea class="form-control" name="conv-descripcion" id="conv-descripcion"
                                rows="3" placeholder="Ingrese el título de la convocatoria" required minlength="10">{{ old('conv-descripcion') }}</textarea>
                            {{ $errors->first('conv-descripcion') }}
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-sm-6 text-center">
                                <label for="conv-fecha-ini">Fecha Inicio</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="conv-fecha-ini"
                                        id="conv-fecha-ini" placeholder="Mes/Día/Año"
                                        value="{{ old('conv-fecha-ini') }}" required readonly>
                                    <span class="input-group-addon">
                                        <img class="center-y-icon"
                                            src="{{ asset('img/calendar.png') }}" width="34"
                                            height="34" alt="icon-calendar">
                                    </span>
                                </div>
                                {{ $errors->first('conv-fecha-ini') }}
                            </div>
                            <div class="col-sm-6 text-center">
                                <label for="conv-fecha-fin">Fecha Final</label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" name="conv-fecha-fin"
                                        id="conv-fecha-fin" placeholder="Mes/Día/Año"
                                        value="{{ old('conv-fecha-fin') }}" required readonly>
                                    <span class="input-group-addon">
                                        <img class="center-y-icon"
                                            src="{{ asset('img/calendar.png') }}" width="34"
                                            height="34" alt="icon-calendar">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-row text-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="conv-tipo">Tipo</label>
                                    <select class="form-control" id="conv-tipo" name="conv-tipo">
                                        @foreach ($tipos as $item)
                                        <option value="{{ $item->id }}">{{ $item->departament_conv }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="conv-gestion">Gestion</label>
                                    <select name="conv-gestion" id="conv-gestion" class="form-control">
                                        <option>{{ $anioActual }}</option>
                                        <option>{{ $anioActual+1 }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info" form="conv-form">Gurardar</button>
                </div>
            </div>
        </div>
    </div>

@endsection