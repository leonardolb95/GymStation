<?php

namespace App\Http\Controllers;

use App\DatosUsuario;
use App\Direccion;
use App\Estatus;
use App\Pago;
use App\Telefono;
use App\TipoCuenta;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function index() {
        //Obtener usuarios
        $usuarios = DatosUsuario::with('usuario')->activos()->get();
        //Obtener promociones
        //Obtener profesores
        //Obtener tipos de cuenta
        $tiposCuenta = TipoCuenta::all();

        return view('admin.index', [
            'usuarios' => $usuarios,
            'tiposCuenta' => $tiposCuenta
        ]);
    }

    public function registrarUsuario(Request $request, $tipo) {
        $validar = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();

        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        DatosUsuario::create([
            'id_usuario' => $user->id,
            'id_tipo_cuenta' => $tipo,
            'confirmacion_cuenta' => false,
            'id_estatus' => 1
        ]);

        Direccion::create([
            'id_usuario' => $user->id
        ]);

        Pago::create([
            'id_usuario' => $user->id,
            'id_estatus' => 1
        ]);

        Telefono::create([
            'id_usuario' => $user->id
        ]);

        return redirect()->to('/admin');
    }

    public function editarUsuario($idUsuario) {
        $usuario = User::withTrashed()->find($idUsuario);
        $estatus = Estatus::all();

        return view('usuario.editar', [
            'usuario' => $usuario,
            'estatus' => $estatus
        ]);
    }

    public function guardarUsuario(Request $request, $idUsuario) {
        $usuario = User::withTrashed()->find($idUsuario);
        $datosUsuario = $usuario->datosUsuario;
        $direccion = $usuario->direccion;
        $telefono = $usuario->telefono;
        $pago = $usuario->pago;

        //Cambios en usuario
        $email = $request->input('email');

        if ($usuario->email != $email) {
            $validar = $request->validate([
                'email' => 'required|string|email|max:255|unique:users'
            ]);
        }

        $usuario->email = $email;
        $usuario->save();

        //Cambios en datos
        $nombre = $request->input('nombre');
        $a_paterno = $request->input('apellido_paterno');
        $a_materno = $request->input('apellido_materno');
        $estatusUsuario = $request->input('select-estatus');

        $datosUsuario->nombre = $nombre;
        $datosUsuario->apellido_paterno = $a_paterno;
        $datosUsuario->apellido_materno = $a_materno;
        $datosUsuario->id_estatus = $estatusUsuario;
        $datosUsuario->save();

        //Cambios en dirección
        $cp = $request->input('codigo_postal');
        $calle = $request->input('calle');
        $colonia = $request->input('colonia');
        $mun = $request->input('municipio');
        $esta = $request->input('estado');

        $direccion->codigo_postal = $cp;
        $direccion->calle = $calle;
        $direccion->colonia = $colonia;
        $direccion->municipio = $mun;
        $direccion->estado = $esta;
        $direccion->save();

        //Cambios en teléfono
        $numTel = $request->input('telefono');

        $telefono->telefono = $numTel;
        $telefono->save();

        //Cambios en pago
        $estatusPago = $request->input('select-estatus-pago');

        $pago->id_estatus = $estatusPago;
        $pago->save();

        return redirect()->to('/admin');
    }

    public function eliminarUsuario($idUsuario) {
        $usuario = User::withTrashed()->find($idUsuario);
        $datosUsuario = $usuario->datosUsuario;
        $direccion = $usuario->direccion;
        $telefono = $usuario->telefono;
        $pago = $usuario->pago;

        $usuario->delete();
        $datosUsuario->delete();
        $direccion->delete();
        $telefono->delete();
        $pago->delete();

        return redirect()->to('/admin');
    }

    public function recuperarUsuario($idUsuario) {
        $usuario = User::withTrashed()->find($idUsuario);
        $usuario->restore();

        $datosUsuario = $usuario->datosUsuario;
        $datosUsuario->restore();

        $direccion = $usuario->direccion;
        $direccion->restore();

        $telefono = $usuario->telefono;
        $telefono->restore();

        $pago = $usuario->pago;
        $pago->restore();

        return redirect()->to('/admin/editar-usuario/' . $usuario->id);
    }
}
