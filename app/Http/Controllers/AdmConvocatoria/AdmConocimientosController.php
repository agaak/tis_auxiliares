<?php

namespace App\Http\Controllers\AdmConvocatoria;


use App\Convocatoria;
use App\EvaluadorAuxiliatura;
use App\EvaluadorConocimientos;
use App\EvaluadorConovocatoria;
use App\EvaluadorTematica;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdmConvocatoria\AdmConocimientosRequest;
use App\Porcentaje;
use App\Requerimiento;
use App\Tipo_evaluador;


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
            $lista_tem_aux = EvaluadorAuxiliatura::select('auxiliatura.nombre_aux as nombre','auxiliatura.id','auxiliatura.cod_aux as cod','evaluador.id as id_eva') 
            ->join('evaluador_auxiliatura','id_convo','=','evaluador_auxiliatura.id_evaluador_convocatoria')  
            ->join('auxiliatura','evaluador_auxiliatura.id_auxiliatura','=','auxiliatura.id')
            ->get();


            $listaMultiselect = Requerimiento::select('auxiliatura.nombre_aux as nombre', 'requerimiento.id as id_unico')
            ->where('id_convocatoria', $id_conv)
            ->join('auxiliatura','requerimiento.id_auxiliatura','=','auxiliatura.id')->get();
            
        } else {
            $lista_tem_aux = EvaluadorTematica::select('tematica.nombre','tematica.id','evaluador_conovocatoria.id as id_eva') 
            ->join('evaluador_conovocatoria','evaluador_tematica.id_evaluador_convocatoria','=','evaluador_conovocatoria.id')
            ->where('evaluador_conovocatoria.id_convocatoria',$id_conv)
            ->join('tipo_evaluador','evaluador_conovocatoria.id','=','tipo_evaluador.id_evaluador_convocatoria')
            ->where('id_rol_evaluador','2')
            ->join('tematica','evaluador_tematica.id_tematica','=','tematica.id')
            ->groupBy('tematica.id','evaluador_conovocatoria.id')->get();

            $tem_res = [];
            foreach($lista_tem_aux as $tem){
                array_push($tem_res, $tem->id);    
            }

            $listaMultiselect = Porcentaje::select('id_tematica as id_unico', 'tematica.nombre')
            ->join('requerimiento', 'porcentaje.id_requerimiento', '=', 'requerimiento.id')
            ->where('requerimiento.id_convocatoria', $id_conv)
            ->join('tematica','porcentaje.id_tematica','=','tematica.id')
            ->whereNotIn('id_tematica', $tem_res)->groupBy('tematica.nombre','id_tematica')->get();

        }
        $listaEva = EvaluadorConocimientos::get();
        return view('admConvocatoria.admConocimientos', compact('listaEva', 'listaMultiselect','lista_tem_aux','evaluadores','tipoConvocatoria'));
    }

    public function inicio($id) {
        session()->put('convocatoria', $id) ;
        return redirect()->route('admConvocatoria');
    }

    public function store(Request $request) {
        $id_conv = session()->get('convocatoria');
        $tipoConvocatoria  = Convocatoria::where('id',$id_conv)->value('id_tipo_convocatoria');
        $evaluadorID = EvaluadorConocimientos::where('ci', $request->input('adm-cono-ci'))->value('id');
        if($evaluadorID == null){
            request()->validate([
                'adm-cono-tipo' => 'required',
                'adm-cono-ci' => 'min:4|max:9|unique:evaluador,ci',
                'adm-cono-nombre' => 'regex:/^[a-zA-Z\s]*$/',
                'adm-cono-apellidos' => 'regex:/^[\pL\s\-]+$/u',
                'adm-cono-correo' => 'email|unique:evaluador,correo',
                'adm-cono-correo2' => 'nullable|email'
            ],[
                'adm-cono-tipo.required' => 'Este Campo es requerido.',
                'adm-cono-ci.min' => 'El campo CI contiene como minimo 4 carácteres.',
                'adm-cono-ci.max' => 'El campo CI contiene como maximo 10 carácteres.', 
                'adm-cono-ci.unique' => 'El ci ingresado ya existe.',
                'adm-cono-nombre.regex' => 'El campo Nombre solo permite letras y espacios en blanco.',
                'adm-cono-apellidos.regex' => 'El campo Apellidos solo permite letras y espacios en blanco.',
                'adm-cono-correo.unique' => 'El correo ingresado ya existe.',
                'adm-cono-correo.email' => 'El campo correo debe ser de tipo email.',
                'adm-cono-correo2.email' => 'El campo correo debe ser de tipo email.'
            ]);
            $evaluador = new EvaluadorConocimientos();
            $evaluador->ci = $request->input('adm-cono-ci');
            $evaluador->nombre = $request->input('adm-cono-nombre');
            $evaluador->apellido = $request->input('adm-cono-apellidos');
            $evaluador->correo = $request->input('adm-cono-correo');
            if($request->input('adm-cono-correo2') != null){
                $evaluador->correo_alt = $request->input('adm-cono-correo2');
            }
            $evaluador->save();
            $evaluadorID = $evaluador->id;
        }
        $idEvaConvo = EvaluadorConovocatoria::where('id_convocatoria',$id_conv)
            ->where('id_evaluador',$evaluadorID)->value('id');

        if($idEvaConvo == null){
            $eva_con = new EvaluadorConovocatoria();
            $eva_con->id_evaluador = $evaluadorID; 
            $eva_con->id_convocatoria = $id_conv;
            $eva_con->save();
            $idEvaConvo = $eva_con->id;
        }
        $tip_eva = Tipo_evaluador::where('id_evaluador_convocatoria',$idEvaConvo)->where('id_rol_evaluador',2)->get();
        if($tip_eva->isNotEmpty()){
            request()->validate([
                'adm-cono-ci' => 'unique:evaluador,ci'
            ],[
                'adm-cono-ci.unique' => 'El evaluador ya esta registrado.'
            ]);    
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

    public function updateEvaluador(Request $request){
        EvaluadorConocimientos::where('id', $request->input('id-evaluador'))->update([
            'ci' => $request->input('adm-cono-ci-edit'),
            'nombre' => $request->input('adm-cono-nombre-edit'),
            'apellido' => $request->input('adm-cono-apellidos-edit'),
            'correo' => $request->input('adm-cono-correo-edit'),
        ]);
        if($request->input('adm-cono-correo2-edit') != null){
            EvaluadorConocimientos::where('id', $request->input('id-evaluador'))->update([
                'correo_alt' => $request->input('adm-cono-correo2-edit'),
            ]);
        }

        $idEvaConvo =  $request->input('id_eva_conv');
        $tipoConvocatoria  = Convocatoria::where('id',session()->get('convocatoria'))->value('id_tipo_convocatoria');
        $arreglo = $request->input('adm-cono-tipo2');
        

        if ($tipoConvocatoria === 2) {
            EvaluadorAuxiliatura::where('id_evaluador_convocatoria',$idEvaConvo)->delete();
            foreach ($arreglo as $item) {
                EvaluadorAuxiliatura::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_auxiliatura' => $item
                ]);
            }
        } else {
            EvaluadorTematica::where('id_evaluador_convocatoria',$idEvaConvo)->delete();
            foreach($arreglo as $item) {
                EvaluadorTematica::create([
                    'id_evaluador_convocatoria' => $idEvaConvo,
                    'id_tematica' => $item
                ]);
            }
        }
        return back();
    }

    public function destroy($id)
    {
        $id_conv = session()->get('convocatoria');

        $id_eva_conv = EvaluadorConovocatoria::where('id_convocatoria',$id_conv)
            ->where('id_evaluador',$id)->value('id');
            
        EvaluadorTematica::where('id_evaluador_convocatoria',$id_eva_conv)->delete();
        EvaluadorAuxiliatura::where('id_evaluador_convocatoria',$id_eva_conv)->delete();

        Tipo_evaluador::where('id_evaluador_convocatoria',$id_eva_conv)->where('id_rol_evaluador','2')->delete();

        if(Tipo_evaluador::where('id_evaluador_convocatoria',$id_eva_conv)->get()->isEmpty()){
            EvaluadorConovocatoria::where('id_evaluador', $id)->delete();
        }
        return back();
    }
}
