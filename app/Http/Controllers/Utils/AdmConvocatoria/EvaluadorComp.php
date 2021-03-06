<?php

namespace App\Http\Controllers\Utils\AdmConvocatoria;

use App\Models\EvaluadorConocimientos;
use App\Models\EvaluadorConovocatoria;
use App\Models\EvaluadorTematica;
use App\Models\EvaluadorAuxiliatura;
use App\Models\Tipo_evaluador;
use App\Http\Controllers\Utils\Convocatoria\ConocimientosComp;

class EvaluadorComp
{   
    
    public function getIdEvaConv(){
        $request = EvaluadorConovocatoria::where('id_convocatoria', session()->get('convocatoria'))->
        where('id_evaluador', session()->get('evaluador'))->value('id');
        return $request;
    }

    public function getEvaluadoresConvo($id){
        $requests = EvaluadorConocimientos::select('evaluador.*','convocatoria.titulo','evaluador_conovocatoria.id  as id_eva_con')
        ->join('evaluador_conovocatoria','evaluador.id','=','evaluador_conovocatoria.id_evaluador')
        ->where('evaluador_conovocatoria.id_convocatoria',$id)
        ->join('convocatoria','evaluador_conovocatoria.id_convocatoria','=','convocatoria.id')
        ->groupBy('evaluador.id','convocatoria.titulo','evaluador_conovocatoria.id')
        ->get();
        return $requests;
    }

    public function getAuxsEvaluador($id_eva_conv){
        $requests = EvaluadorAuxiliatura::select('auxiliatura.nombre_aux as nombre','auxiliatura.id') 
        ->join('evaluador_conovocatoria','evaluador_auxiliatura.id_evaluador_convocatoria','=','evaluador_conovocatoria.id') 
        ->where('evaluador_conovocatoria.id',$id_eva_conv)
        ->join('auxiliatura','evaluador_auxiliatura.id_auxiliatura','=','auxiliatura.id')->get();
        return $requests;
    }

    public function getTemsEvaluador($id_eva_conv){
        $requests = EvaluadorTematica::select('tematica.nombre', 'tematica.id') 
        ->join('evaluador_conovocatoria','evaluador_tematica.id_evaluador_convocatoria','=','evaluador_conovocatoria.id')
        ->where('evaluador_conovocatoria.id',$id_eva_conv)
        ->join('tematica','evaluador_tematica.id_tematica','=','tematica.id')->get();
        return $requests;
    }

    public function getTematicsEvaluador($id_eva_conv){
        $requests = EvaluadorTematica::select('tematica.nombre', 'tematica.id') 
        ->join('evaluador_conovocatoria','evaluador_tematica.id_evaluador_convocatoria','=','evaluador_conovocatoria.id')
        ->where('evaluador_conovocatoria.id',$id_eva_conv)
        ->join('tematica','evaluador_tematica.id_tematica','=','tematica.id')->get();

        $areas = (new ConocimientosComp)->getAreaByTem(session()->get('convocatoria'));
        foreach($requests as $tem){
            $tem->areas = $areas->has($tem->id)? $areas[$tem->id] : [];
        }
        
        return $requests;
    }

    public function getTematicsEvaluador2($id_eva_conv){
        $requests = EvaluadorTematica::select('tematica.nombre', 'tematica.id') 
        ->join('evaluador_conovocatoria','evaluador_tematica.id_evaluador_convocatoria','=','evaluador_conovocatoria.id')
        ->where('evaluador_conovocatoria.id',$id_eva_conv)
        ->join('tematica','evaluador_tematica.id_tematica','=','tematica.id')->get();

        $areas = (new ConocimientosComp)->getAreaByTem2(session()->get('convocatoria'));
        foreach($requests as $tem){
            $area_aux = $areas->has($tem->id)? $areas[$tem->id] : [];
            $area_aux = $area_aux->groupBy('id_area');
            $area_aux2 = [];
            foreach($area_aux as $aarea){
                array_push($area_aux2, $aarea[0]);
            }
            $tem->areas = $area_aux2;
        }
        
        return $requests;
    }

    public function getRolesEvaluador($id_eva_conv){
        $requests = Tipo_evaluador::select('tipo_rol_evaluador.id','tipo_rol_evaluador.nombre')
        ->where('id_evaluador_convocatoria',$id_eva_conv)
        ->join('tipo_rol_evaluador','tipo_evaluador.id_rol_evaluador','=','tipo_rol_evaluador.id')
        ->get();
        return $requests;
    }
    
}
