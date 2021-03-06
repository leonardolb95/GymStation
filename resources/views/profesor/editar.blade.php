@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 div-panel" id="div-login">
                @if($usuario->trashed())
                    <div class="alert alert-danger fade show" id="alerta-eliminado" role="alert">
                        Este profesor se encuentra actualmente eliminado.
                    </div>
                    <a href="{{ url('/admin/recuperar-profesor/' . $usuario->id) }}" class="btn btn-primary">Recuperar profesor</a>
                @else
                    <form method="POST" action="{{ url('/admin/guardar-profesor/' . $usuario->id) }}">
                        {{ csrf_field() }}

                        <h5 class="text-primary">Cuenta</h5>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Correo electrónico:</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ $usuario->email }}" required>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>

                        <h5 class="text-primary">Datos Generales</h5>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ $usuario->datosUsuario->nombre }}">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="apellido_paterno">Apellido paterno:</label>
                                <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" value="{{ $usuario->datosUsuario->apellido_paterno }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="apellido_materno">Apellido materno:</label>
                                <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" value="{{ $usuario->datosUsuario->apellido_materno }}">
                            </div>
                        </div>
                        @auth
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="select-estatus">Elige el estatus del profesor:</label><br/>
                                    <select class="custom-select" id="select-estatus" name="select-estatus">
                                        @foreach($estatus as $key => $est)
                                            @if($usuario->datosUsuario->estatus->id == $est->id)
                                                <option value="{{ $est->id }}" selected>{{ $est->estatus }}</option>
                                            @else
                                                <option value="{{ $est->id }}">{{ $est->estatus }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endauth

                        <h5 class="text-primary">Dirección</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="codigo_postal">Código postal:</label>
                                <input type="number" min="1" step="1" id="codigo_postal" name="codigo_postal" class="form-control"
                                       value="{{ $usuario->direccion->codigo_postal }}">
                            </div>
                            <div class="form-group col-md-6">
                                <div class="alert alert-info fade show" id="alerta-cp" role="alert">
                                    Ingresa el código postal y calle para obtener la información.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="calle">Calle y número:</label>
                                <input type="text" id="calle" name="calle" class="form-control"
                                       value="{{ $usuario->direccion->calle }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="colonia">Colonia:</label>
                                <input type="text" id="colonia" name="colonia" class="form-control" readonly
                                       value="{{ $usuario->direccion->colonia }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="municipio">Municipio:</label>
                                <input type="text" id="municipio" name="municipio" class="form-control" readonly
                                       value="{{ $usuario->direccion->municipio }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="estado">Estado:</label>
                                <input type="text" id="estado" name="estado" class="form-control" readonly
                                       value="{{ $usuario->direccion->estado }}">
                            </div>
                        </div>

                        <h5 class="text-primary">Teléfono</h5>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telefono">Número de teléfono:</label>
                                <input type="number" min="1" step="1" id="telefono" name="telefono" class="form-control"
                                       value="{{ $usuario->telefono->telefono }}">
                            </div>
                        </div>
                        <br/>
                        <button type="submit" style="cursor: pointer;" class="btn btn-primary">Guardar</button>
                        <a href="{{ url('/admin/eliminar-profesor/' . $usuario->id) }}" class="btn btn-danger">Eliminar profesor</a>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('#barra-perfil').parent().addClass('active');

        $('#codigo_postal').on('change', function (evt) {
            evt.stopPropagation();

            var codigoPostal = $(this).val();

            $.ajax({
                url: 'https://api-codigos-postales.herokuapp.com/v2/codigo_postal/' + codigoPostal,
                data: {},
                type: 'GET',
                dataType: 'JSON',
                error: function (error) {
                    console.log('Error: ' + error);
                },
                beforeSend: function () {
                    $('#alerta-cp').html('Obteniendo información...');
                },
                success: function (response) {
                    $('#colonia').val(response.colonias[0]);
                    $('#estado').val(response.estado);
                    $('#municipio').val(response.municipio);
                    $('#alerta-cp').html('Ingresa el código postal y calle para obtener la información.');
                }
            });
        });
    </script>
@endsection
