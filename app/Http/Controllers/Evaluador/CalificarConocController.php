<?php

namespace App\Http\Controllers\Evaluador;

use App\Models\Convocatoria;
use App\Models\EvaluadorConocimientos;
use App\Models\EvaluadorConovocatoria;
use App\Models\PostuCalifConoc;
use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Postulante_conovocatoria;
use App\Http\Controllers\Utils\AdmConvocatoria\EvaluadorComp;
use App\Http\Controllers\Utils\Evaluador\MenuDina;
use App\Http\Controllers\Utils\Evaluador\PostulanteComp;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\ConvocatoriaComp;

class CalificarConocController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'roles:evaluador']);
    }
    
    public function index($id_tem,$nom){
        $menu = new MenuDina();
        $convs = $menu->getConvs(); 
        $compEval = new EvaluadorComp();
        $idEC = $compEval->getIdEvaConv();
       
        $roles = $compEval->getRolesEvaluador($idEC);
        $tipoConv = Convocatoria::where('id', session()->get('convocatoria'))->value('id_tipo_convocatoria');
        $auxsTemsEval = $compEval->getTematicsEvaluador2($idEC);
        $compPost = new PostulanteComp();
        $postulantes= $compPost->getPostulantesByTem($id_tem,$nom); 
        
        $publicado_habilitados = Postulante_conovocatoria::where('id_convocatoria', session()->get('convocatoria'))
            ->where('estado','publicado')->get()->isNotEmpty();
        
        $entregado = $compPost->getEntregado($postulantes);
        $publicado = $compPost->getPublicado($postulantes);
        if(!$publicado_habilitados){
            $postulantes = [];
        }
        $dependiente = false;
        
        if(count($postulantes)>0){
            $dependiente = $postulantes->collapse()[0]->id_porc_dependiente != null;

            if($dependiente){
                $postulantes = $compPost->getDependencia($postulantes);
                $postulantes = $postulantes->collapse()->reject(function ($value) {
                    return !$value->habilitado && !$value->esperando_dep;
                });
                $postulantes = $postulantes->groupBy('id');
            }
        } 
        return view('evaluador.calificarConocimiento', compact('convs', 'roles', 'tipoConv', 'dependiente',
            'auxsTemsEval','postulantes','id_tem','nom','publicado','entregado','publicado_habilitados'));
    }

    public function store(Request $request){
        $cont = 0;
        if ($request->input('id-tipo') == 1) {
            foreach($request->input('nota') as $nota) {
                $id_post = $request->input('id-post')[$cont++];
                foreach ($request->input('id_nota') as $ids) {
                    $parseID = explode(',',$ids);
                    if(intval($parseID[1]) == $id_post){
                        PostuCalifConoc::where('id', intval($parseID[0]))->update([
                            'calificacion' => $nota
                        ]);
                    }  
                }
                
            }
        } else {
            foreach ($request->input('nota') as $nota) {
                $id_post = $request->input('id-post')[$cont++];
                PostuCalifConoc::where('id', $id_post)->update([
                    'calificacion' => $nota
                ]);
            }
        }
        return back();
    }

    public function entregar(Request $request,$id_tem,$nom){
        
        $compPost = new PostulanteComp();
        $postulantes = $compPost->getPostulantesByTem($id_tem,$nom); 
        foreach($postulantes as $postulante){
            foreach($postulante as $nota){
                if($request->has('desierto')){
                    PostuCalifConoc::where('id', $nota->id_nota)->update([
                        'estado' => 'publicado',
                    ]);
                } else {
                    PostuCalifConoc::where('id', $nota->id_nota)->update([
                        'estado' => 'entregado',
                    ]);
                }
                
            }
        }
        return back();
    }
}