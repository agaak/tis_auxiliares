<?php

namespace App\Http\Controllers\AdmConvocatoria;

use App\Auxiliatura;
use App\Convocatoria;
use App\EvaluadorAuxiliatura;
use App\EvaluadorConocimientos;
use App\EvaluadorConovocatoria;
use App\EvaluadorTematica;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdmConvocatoria\AdmConocimientosRequest;
use App\Porcentaje;
use App\Requerimiento;
use App\Tipo_evaluador;
use App\Tipo_rol_evaluador;
use TipoNotaSeeder;

class AdmConocimientosController extends Controller
{
    public function index()
    {
        $id_conv = session()->get('convocatoria');
        $listaMultiselect = [];
        $lista_tem_aux = [];
        $evaluadores = EvaluadorConocimientos::select('evaluador.*','evaluador_conovocatoria.id as id_eva_conv')
            ->join('evaluador_conovocatoria','evaluador.id','=','evaluador_conovocatoria.id_evaluador')
            ->where('evaluador_conovocatoria.id_convocatoria',$id_conv)
            ->join('tipo_evaluador','evaluador_conovocatoria.id','=','tipo_evaluador.id_evaluador_convocatoria')
            ->where('id_rol_evaluador','2')->get();
        $tipoConvocatoria  = Convocatoria::where('id',$id_conv)->value('id_tipo_convocatoria');
        if ($tipoConvocatoria  === 2) {
            $listaMultiselect = Requerimiento::select('auxiliatura.nombre_aux as nombre', 'requerimiento.id as id_unico')
            ->where('id_convocatoria', $id_conv)
            ->join('auxiliatura','requerimiento.id_auxiliatura','=','auxiliatura.id')->get();
        
            $lista_tem_aux = EvaluadorAuxiliatura::select('auxiliatura.nombre_aux as nombre','auxiliatura.id','auxiliatura.cod_aux as cod','evaluador.id as id_eva') 
            ->join('evaluador_auxiliatura','id_convo','=','evaluador_auxiliatura.id_evaluador_convocatoria')  
            ->join('auxiliatura','evaluador_auxiliatura.id_auxiliatura','=','auxiliatura.id')
            ->get();
        } else {
            $listaMultiselect = Porcentaje::select('id_tematica as id_unico', 'tematica.nombre')
            ->join('requerimiento', 'porcentaje.id_requerimiento', '=', 'requerimiento.id')
            ->where('requerimiento.id_convocatoria', $id_conv)
            ->join('tematica','porcentaje.id_tematica','=','tematica.id')->groupBy('tematica.nombre','id_tematica')->get();
            
            $lista_tem_aux = EvaluadorTematica::select('tematica.nombre','tematica.id','evaluador_conovocatoria.id as id_eva') 
            ->join('evaluador_conovocatoria','evaluador_tematica.id_evaluador_convocatoria','=','evaluador_conovocatoria.id_evaluador')
            ->where('evaluador_conovocatoria.id_convocatoria',$id_conv)
            ->join('tipo_evaluador','evaluador_conovocatoria.id','=','tipo_evaluador.id_evaluador_convocatoria')
            ->where('id_rol_evaluador','2')
            ->join('tematica','evaluador_tematica.id_tematica','=','tematica.id')
            ->groupBy('tematica.id','evaluador_conovocatoria.id')->get();
        }
        $listaEva = EvaluadorConocimientos::get();
        return view('admConvocatoria.admConocimientos', compact('listaEva', 'listaMultiselect','lista_tem_aux','evaluadores','tipoConvocatoria'));
    }

    public function inicio($id) {
        session()->put('convocatoria', $id) ;
        return redirect()->route('admConvocatoria');
    }

    public function store(AdmConocimientosRequest $request) {
        $id_conv = session()->get('convocatoria');
        $tipoConvocatoria  = Convocatoria::where('id',$id_conv)->value('id_tipo_convocatoria');
        $evaluador = new EvaluadorConocimientos();
        $evaluador->ci = $request->input('adm-cono-ci');
        $evaluador->nombre = $request->input('adm-cono-nombre');
        $evaluador->apellido = $request->input('adm-cono-apellidos');
        $evaluador->correo = $request->input('adm-cono-correo');
        if($request->input('adm-cono-correo2') != null){
            $evaluador->correo_alt = $request->input('adm-cono-correo2');
        }
        $evaluador->save();

        $idEvaConvo = EvaluadorConovocatoria::where('id_convocatoria',$id_conv)
            ->where('id_evaluador',$evaluador->id)->value('id');

        if($idEvaConvo == null){
            $eva_con = new EvaluadorConovocatoria();
            $eva_con->id_evaluador = $evaluador->id; 
            $eva_con->id_convocatoria = $id_conv;
            $eva_con->save();
            $idEvaConvo = $eva_con->id;
        }

        Tipo_evaluador::create([
            'id_rol_evaluador' => 2,
            'id_evaluador_convocatoria' => $idEvaConvo
        ]);

        $arreglo = $request->input('adm-cono-tipo');
        if ($tipoConvocatoria === 2) {
            for($i=0; $i<count($arreglo); $i++) {
                EvaluadorAuxiliatura::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_auxiliatura' => $arreglo[$i]
                ]);
            }

            $jsonTematicas = Porcentaje::select('id_tematica as id_unico')
            ->join('requerimiento', 'porcentaje.id_requerimiento', '=', 'requerimiento.id')
            ->where('requerimiento.id_convocatoria', $id_conv)
            ->join('tematica','porcentaje.id_tematica','=','tematica.id')->groupBy('tematica.nombre','id_tematica')->get();

            foreach($jsonTematicas as $item){
                EvaluadorTematica::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_tematica' => $item['id_unico']
                ]);
            }

        } else {
            for($i=0; $i<count($arreglo); $i++) {
                EvaluadorTematica::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_tematica' => $arreglo[$i]
                ]);
            }

            $jsonAuxiliaturas = Requerimiento::select('requerimiento.id_auxiliatura as id_unico')
            ->where('id_convocatoria', $id_conv)->get();

            foreach($jsonAuxiliaturas as $item){
                EvaluadorAuxiliatura::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_auxiliatura' => $item['id_unico']
                ]);
            }
        }
        


        return back();
    }


    public function destroy($id)
    {
        EvaluadorTematica::where('id_evaluador',$id)->delete();
        EvaluadorAuxiliatura::where('id_evaluador',$id)->delete();
        Tipo_evaluador::where('id_evaluador',$id)->where('id_rol_evaluador','2')->delete();
        if(Tipo_evaluador::where('id_evaluador',$id)->get()->isExmpty()){
            EvaluadorConovocatoria::where('id_evaluador', $id)->delete();
        }
        return back();
    }
}
