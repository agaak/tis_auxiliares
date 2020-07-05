<?php

namespace App\Http\Controllers\Aviso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\Convocatoria\RequisitoComp;
use App\Models\Aviso;

class AvisosController extends Controller
{
    // public function __construct(){
    //     $this->middleware(['auth', 'roles:secretaria']);
    // }
    
    public function index(){
        $listAvisos = Aviso::join('convocatoria','aviso.id_convocatoria','=','convocatoria.id')
                              ->join('unidad_academica','convocatoria.id_unidad_academica','=','unidad_academica.id')
                              ->get();
        return view('avisos',compact('listAvisos'));
    }
}
