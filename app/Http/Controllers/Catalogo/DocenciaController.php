<?php

namespace App\Http\Controllers\Catalogo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Auxiliatura;
use App\Models\Tematica;

class DocenciaController extends Controller
{
    public function index() {
        $idUnidadAcademica = auth()->user()->unidad_academica_id;
        $auxiliaturas = Auxiliatura::where('id_unidad_academica', $idUnidadAcademica)
        ->where('id_tipo_convocatoria', 2)->orderBy('id', 'ASC')->get();

        $tematicas = Tematica::where('id_unidad_academica', $idUnidadAcademica)
        ->where('id_tipo_convocatoria', 2)->orderBy('id', 'ASC')->get();

        $areas = Area::where('id_unidad_academica', $idUnidadAcademica)
        ->where('id_tipo_convocatoria', 2)->orderBy('id', 'ASC')->get();

        return view('catalogo.docencia', compact('auxiliaturas', 'tematicas', 'areas'));
    }

    public function save() {

        $idUnidadAcademica = auth()->user()->unidad_academica_id;

        if (request()->has('nombre-tem-doc')) {

            request()->validate([
                'nombre-tem-doc' => 'unique:tematica,nombre,0,id,id_unidad_academica,'.$idUnidadAcademica
            ], [
                'nombre-tem-doc.unique' => 'El nombre de la temática ya existe.'
            ]);

            Tematica::create([
                'id_unidad_academica' => $idUnidadAcademica,
                'id_tipo_convocatoria' => 2,
                'nombre' => request()->input('nombre-tem-doc')
            ]);

        } else if (request()->has('nombre-area-lab')) {

            request()->validate([
                'nombre-area-lab' => 'unique:area_calificacion,nombre,0,id,id_unidad_academica,'.$idUnidadAcademica
            ], [
                'nombre-area-lab.unique' => 'El nombre de area ya existe.'
            ]);

            Area::create([
                'id_unidad_academica' => $idUnidadAcademica,
                'id_tipo_convocatoria' => 2,
                'nombre' => request()->input('nombre-area-lab')
            ]);

        } else {

            request()->validate([
                'nombre-auxs-lab' => 'unique:auxiliatura,nombre_aux,0,id,id_unidad_academica,'.$idUnidadAcademica,
                'codigo-auxs-lab' => 'unique:auxiliatura,cod_aux,0,id,id_unidad_academica,'.$idUnidadAcademica
            ], [
                'nombre-auxs-lab.unique' => 'El nombre de auxiliatura ya existe.',
                'codigo-auxs-lab.unique' => 'El código de auxiliatura ya existe.'
            ]);
    
            Auxiliatura::create([
                'id_unidad_academica' => $idUnidadAcademica,
                'id_tipo_convocatoria' => 2,
                'nombre_aux' => request()->input('nombre-auxs-lab'),
                'cod_aux' => request()->input('codigo-auxs-lab')
            ]);

            Tematica::create([
                'id_unidad_academica' => $idUnidadAcademica,
                'id_tipo_convocatoria' => 2,
                'nombre' => request()->input('nombre-auxs-lab')
            ]);

        }


        return back();
    }
    
    public function update() {

        $idUnidadAcademica = auth()->user()->unidad_academica_id;

        
        if (request()->has('nombre-auxs-edit')) {
            $idAuxiliatura = request()->input('id-auxiliatura');
            request()->validate([
                'nombre-auxs-edit' => 'unique:auxiliatura,nombre_aux,'.$idAuxiliatura.',id,id_unidad_academica,'.$idUnidadAcademica,
                'codigo-auxs-edit' => 'unique:auxiliatura,cod_aux,'.$idAuxiliatura.',id,id_unidad_academica,'.$idUnidadAcademica
            ], [
                'nombre-auxs-edit.unique' => 'El nombre de auxiliatura ya existe.',
                'codigo-auxs-edit.unique' => 'El código de auxiliatura ya existe.'
            ]);
    
            Auxiliatura::where('id', $idAuxiliatura)->update([
                'nombre_aux' => request()->input('nombre-auxs-edit'),
                'cod_aux' => request()->input('codigo-auxs-edit')
            ]);
        } else if (request()->has('nombre-area-edit')) {

            $idArea = request()->input('id-area');

            request()->validate([
                'nombre-area-edit' => 'unique:area_calificacion,nombre,'.$idArea.',id,id_unidad_academica,'.$idUnidadAcademica,
            ], [
                'nombre-area-edit.unique' => 'El nombre de area ya existe.',
            ]);

            Area::where('id', $idArea)->update([
                'nombre' => request()->input('nombre-area-edit')
            ]);

        } else {
            $idTematica = request()->input('id-tematica');

            request()->validate([
                'nombre-tem-edit' => 'unique:tematica,nombre,'.$idTematica.',id,id_unidad_academica,'.$idUnidadAcademica,
            ], [
                'nombre-tem-edit.unique' => 'El nombre de la temática ya existe.',
            ]);

            Tematica::where('id', $idTematica)->update([
                'nombre' => request()->input('nombre-tem-edit')
            ]);
        }

        

        return back();
    }
}
