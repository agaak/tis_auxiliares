@extends('convocatory.layoutConvocatory')

@section('content-convocatory')

<div class="overflow-auto content">

    <h3 class="text-uppercase text-center">Nueva Convocatoria</h3>
    <form class="form-title-description" method="POST" action="{{ route('requestValid') }}">
        {{ csrf_field() }}
        <div class="form-group my-5">
            <label class="text-uppercase" for="convocatory-title">titulo</label>
            <input class="form-control" name="titulo-conv" id="convocatory-title" autofocus
                placeholder="Ingrese el título de la CONVOCATORIA" required>{{ old('titulo-conv') }}
            {{ $errors->first('titulo-conv') }}
        </div>
        <div class="form-row my-5">
            <label class="col-auto col-form-label text-uppercase" for="department-conv">departamento</label>
            <div class="col-xl">
                <select class="form-control" id="department-conv" name="departamento-ant">
                    @php
                        function valor($dato) {
                        $direction = '';
                        if ( old('departamento_ant') == $dato ) {
                        $direction = 'selected';
                        }
                        return $direction;
                        }
                    @endphp
                    @forelse($departamets as $item)
                        <option {{ $item->departament_conv }} value={{ $item->id }}>{{ $item->departament_conv }}
                        </option>
                    @empty
                        <option {{ valor('NONE') }}>NONE</option>
                    @endforelse
                </select>
            </div>
            <label class="col-auto col-form-label text-uppercase" for="date-ini">fecha inicio</label>
            <div class="col-xl input-group date">
                <input type="text" class="form-control" name="fecha-ini" id="date-ini" autocomplete="off"
                    value="{{ old('fecha-ini') }}" placeholder="Mes/Día/Año" required>
                <span class="input-group-addon">
                    <img class="center-y-icon" src="{{ asset('img/calendar.png') }}" width="34"
                        height="34" alt="icon-calendar">
                </span>
                {{ $errors->first('fecha-ini') }}
            </div>
            <label class="col-auto col-form-label text-uppercase" for="date-end">fecha fin</label>
            <div class="col-xl input-group date">
                <input type="text" class="form-control" name="fecha-fin" id="date-end" autocomplete="off"
                value="{{ old('fecha-ini') }}" placeholder="Mes/Día/Año" required>
                <span class="input-group-addon">
                    <img class="center-y-icon" src="{{ asset('img/calendar.png') }}" width="34"
                        height="34" alt="icon-calendar">
                </span>
            </div>
        </div>
        <div class="form-group my-5">
            <label class="text-uppercase" for="description-conv">descripcion</label>
            <textarea style="resize: none;" class="form-control" name="descripcion-conv" id="description-conv" rows="4"
                required
                placeholder="Ingrese la descripción de la CONVOCATORIA">{{ old('descripcion-conv') }}</textarea>
            {{ $errors->first('descripcion-conv') }}
        </div>
        <div class="text-center">
            <input class="btn btn-info text-uppercase form-title-description" type="submit" value="siguiente">
        </div>
    </form>
</div>
@endsection