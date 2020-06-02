@extends('convocatory.layoutConvocatory')

@section('content-convocatory')

<div class="overflow-auto content">

    <h3 class="text-uppercase text-left">Seccion eventos importantes</h3>

    <!-- Button trigger modal -->
    <div class="my-3" style="margin-left: 3ch">
        <a class="text-decoration-none" type="button" data-toggle="modal" data-target="#importantDatesModalCreate">
            <img src="{{ asset('img/calendarAdd.png') }}" width="30" height="30">
            <span class="mx-1">Añadir Evento</span>
        </a>
    </div>

    {{-- Crear Base de datos --}}
    <div class="modal fade" id="importantDatesModalCreate" tabindex="-1" role="dialog"
        aria-labelledby="importanDatesTitleCreate" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importanDatesTitleCreate">Añadir nuevo evento</h5>
                    <button type="button" class="modal-icon" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('importantDateSave') }}"
                        id="important-dates-create">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="titulo-event">Titulo de evento</label>
                            <input type="text" class="form-control" name="titulo-evento" id="title-event"
                                placeholder="Ingrese el titulo del evento" required minlength="10"
                                value="{{ old('titulo-evento') }}">
                            <div>
                                {!! $errors->first('titulo-evento', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event">Lugar de evento</label>
                            <input type="text" class="form-control" name="lugar-evento" id="place-event"
                                placeholder="Ingrese el lugar del evento" required minlength="10"
                                value="{{ old('lugar-evento') }}">
                            <div>
                                {!! $errors->first('lugar-evento', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event-date-ini">Fecha inicial</label>
                            <div class="col-10">
                                <input class="form-control" type="datetime-local" id="place-event-date-ini"
                                    name="fecha-ini-evento" required
                                    value="{{ old('fecha-ini-evento') }}">
                            </div>
                            <div>
                                {!! $errors->first('fecha-ini-evento', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event-date-end">Fecha final</label>
                            <div class="col-10">
                                <input class="form-control" type="datetime-local" id="place-event-date-end"
                                    name="fecha-fin-evento" required
                                    value="{{ old('fecha-fin-evento') }}">
                            </div>
                            <div>
                                {!! $errors->first('fecha-fin-evento', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        @if($errors->any())
                            <script>
                                window.onload = () => {
                                    $('#importantDatesModalCreate').modal('show');
                                }
                            </script>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-secondary" value="Cancelar" data-dismiss="modal">
                    <input type="submit" class="btn btn-info" value="Guardar" form="important-dates-create">
                </div>
            </div>
        </div>
    </div>
    {{-- Actulizar base de datos --}}
    <div class="modal fade" id="importantDatesModalUpdate" tabindex="-1" role="dialog"
        aria-labelledby="importanDatesTitleUpdate" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importanDatesTitleUpdate">Actualizar evento</h5>
                    <button type="button" class="modal-icon" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('importantDatesUpdate') }}"
                        id="important-dates-update">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <input type="hidden" id="id-datos-edit" name="id-datos-edit">
                        <div class="form-group">
                            <label for="title-event">Titulo de evento</label>
                            <input type="text" class="form-control" name="titulo-evento-edit" id="titulo-evento-edit"
                                placeholder="Ingrese el titulo del evento" required>
                            <div>
                                {!! $errors->first('titulo-evento-edit', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event">Lugar de evento</label>
                            <input type="text" class="form-control" name="lugar-evento-edit" id="lugar-evento-edit"
                                placeholder="Ingrese el lugar del evento" required minlength="10"
                                value="{{ old('lugar-evento-edit') }}">
                            <div>
                                {!! $errors->first('lugar-evento-edit', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event-date-ini">Fecha inicial</label>
                            <div class="col-10">
                                <input class="form-control" type="datetime-local" id="fecha-ini-evento-edit"
                                    name="fecha-ini-evento-edit" required
                                    value="{{ old('fecha-ini-evento-edit') }}">
                            </div>
                            <div>
                                {!! $errors->first('fecha-ini-evento-edit', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="place-event-date-end">Fecha final</label>
                            <div class="col-10">
                                <input class="form-control" type="datetime-local" id="fecha-fin-evento-edit"
                                    name="fecha-fin-evento-edit" required
                                    value="{{ old('fecha-fin-evento-edit') }}">
                            </div>
                            <div>
                                {!! $errors->first('fecha-fin-evento-edit', '<strong
                                    class="message-error text-danger">:message</strong>') !!}
                            </div>
                        </div>
                        @if ($errors->has('fecha-fin-evento-edit')||$errors->has('fecha-ini-evento-edit')
                                ||$errors->has('titulo-evento-edit')||$errors->has('lugar-evento-edit'))
                            <script>
                                window.onload = () => {
                                    $('#importantDatesModalUpdate').modal('show');
                                }
                            </script>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <input type="submit" class="btn btn-info" value="Guardar" form="important-dates-update">
                </div>
            </div>
        </div>
    </div>

    @php
        function convertir($object) {
        return json_encode($object);
        }
    @endphp


    <div class="table-requests">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <thead class="thead-dark text-center">
                    <tr>
                        <th class="font-weight-normal" scope="col">Evento</th>
                        <th class="font-weight-normal" scope="col">Lugar</th>
                        <th class="font-weight-normal" scope="col">Fecha Inicial</th>
                        <th class="font-weight-normal" scope="col">Fecha Fin</th>
                        <th class="font-weight-normal" scope="col">Opciones</th>
                    </tr>
                </thead>
            <tbody class="bg-white">
                @foreach($importantDatesList as $item)
                    <tr>
                        <td>{{ $item->titulo_evento }}</td>
                        <td>{{ $item->lugar_evento }}</td>
                        <td class="text-center">{{ $item->fecha_inicio }}</td>
                        <td class="text-center">{{ $item->fecha_final }}</td>
                        <td class="text-center">
                            <a type="button" onclick="editDatesList({{ convertir($item) }})" data-toggle="modal"
                                data-target="#importantDatesModalUpdate">
                                <img src="{{ asset('img/pen.png') }}" width="30" height="30">
                            </a>
                            <form class="d-inline"
                                action="{{ route('importantDatesDelete', $item->id) }}"
                                method="POST" id="important-dates-delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-link">
                                    <img src="{{ asset('img/trash.png') }}" width="30" height="30">
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="my-5 py-5 text-center">
        <a href="{{ route('documentos') }}" class="btn btn-info" tabindex="-1" role="button"
            aria-disabled="true">Anterior</a>
        <a href="{{ route('calificacion-meritos.index') }}" class="btn btn-info" tabindex="-1"
            role="button" aria-disabled="true">Siguiente</a>
    </div>
</div>
@endsection