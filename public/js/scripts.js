$('.input-group.date').datepicker({
    format: 'mm/dd/yyyy',
    language: "es",
    orientation: "bottom left",
    todayHighlight: true,
    autoclose: true,
    orientation: "bottom auto"
});

$('.timepicker').timepicker({
    timeFormat: 'HH:mm:ss',
    interval: 10,
    minTime: '00',
    maxTime: '23:59',
    startTime: '00',
    dynamic: true,
    dropdown: true,
    scrollbar: true
});

$('body').mouseleave(function() {
    $('.ui-timepicker-container').css('display', 'none');
});

$('.timepicker').click(function() {
    $('.ui-timepicker-container').css('display', 'block');
});

function editDatesList(listDates) {
    // date_create_from_format('d/m/Y:H:i:s', );
    $('#id-datos-edit').val(listDates['id']);
    if ('Presentación de Documentos' === listDates.titulo_evento) $('#titulo-evento-edit').attr('readonly', true)
    else $('#titulo-evento-edit').attr('readonly', false);
    $('#titulo-evento-edit').val(listDates['titulo_evento']);
    $('#lugar-evento-edit').val(listDates['lugar_evento']);
    $('#fecha-ini-evento-edit').val(listDates['fecha_inicio'].replace(" ", "T"));
    $('#fecha-fin-evento-edit').val(listDates['fecha_final'].replace(" ", "T"));
}

function editEvaluadorMeritos(evaluadorMeritos) {
    $('#id-evaluador').val(evaluadorMeritos.id);
    $('#adm-cono-ci-edit').val(evaluadorMeritos.ci);
    $('#adm-cono-nombre-edit').val(evaluadorMeritos.nombre);
    $('#adm-cono-apellidos-edit').val(evaluadorMeritos.apellido);
    $('#adm-cono-correo-edit').val(evaluadorMeritos.correo);
    $('#adm-cono-correo2-edit').val(evaluadorMeritos.correo_alt);
}

function comprobarEvaluadorMerit(listaCi) {
    let existe = true;
    let evaluadorExist;
    for (const item of listaCi) {
        if($('#adm-meritos-ci').val() == item['ci']) {
            existe = false;
            $('#ci-no-existe').removeClass('d-none');
            $('#ci-existe').addClass('d-none');
            setTimeout(() => {
                $('#ci-no-existe').addClass('d-none');
            }, 5000);
            evaluadorExist = item;
        }else{
        }
    }
    if (existe) {
        $('#ci-existe').removeClass('d-none');
        $('#ci-no-existe').addClass('d-none');
        setTimeout(() => {
            $('#ci-existe').addClass('d-none');
        }, 5000);
    }else{
        $('#adm-meritos-nombre').val(evaluadorExist['nombre']);
        $('#adm-meritos-apellidos').val(evaluadorExist['apellido']);
        $('#adm-meritos-correo').val(evaluadorExist['correo']);
        $('#adm-meritos-correo-alter').val(evaluadorExist['correo_alt']);
    }
}

$('#requestEditModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var mid = button.data('id')
    var mcantidad = button.data('cantidad')
    var mhoras_mes = button.data('horas_mes')
    var mnombre = button.data('nombre')
    var mid_aux = button.data('id_auxiliatura')
    var modal = $(this)
    var sel = document.getElementById("id-aux-request");
    sel.remove(sel.selectedIndex);
    var opt = document.createElement("option");
    opt.value = mid_aux;
    opt.text = mnombre;
    sel.add(opt, null);
    $("#id-aux-request").val(mid_aux);
    modal.find('.modal-body #id-request').val(mid);
    modal.find('.modal-body #cantidad-request').val(mcantidad);
    modal.find('.modal-body #horas_mes-request').val(mhoras_mes);
    
})

$('#tematicaModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var mid_aux = button.data('id_auxiliatura')
    var modal = $(this)
    modal.find('.modal-body #id-auxiliatura').val(mid_aux);
})

function addModalTem(tematics){
    var x = document.getElementById("id-tematica");
    var length = x.options.length;
    for (i = length-1; i >= 0; i--) {
        x.options[i] = null;
    }
    tematics.forEach(tem => {
        var option = document.createElement("option");
        option.text = tem['nombre'];
        option.value = tem['id'];
        x.add(option);
    });
    
}

$('#tematicaEditModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var mid = button.data('id')
        var mid_aux = button.data('id_auxiliatura')
        var modal = $(this)
        modal.find('.modal-body #id-tematica-edit').val(mid);
        modal.find('.modal-body #id-auxiliatura-edit').val(mid_aux);
})

$(document).ready(function(){
    $("#deleteDates").click(function(){
        $("#important-dates-delete").submit(); // Submit the form
    });
});


function editMeritModal(lista) {
    formaterar = lista[1].split(' ')
    formaterar.splice(0, 1);
    $('#merit-descripcion-edit').val(formaterar.join(" "));
    $('#merit-porcentaje-edit').val(lista[2]);
    $('#merit-id').val(lista[3]);
}

function editPorcentajes(porcentaje) {
    $('#porcent-merit').val(porcentaje);
}



function requirementsEditModal(requisito,inc) {
    console.log(requisito);
    console.log(inc);
    document.getElementById("inc-req-edit").innerHTML = inc;
    $('#id-requirement').val(requisito.id);
    $('#descripcion-requirement').val(requisito.descripcion);
}

function editSubMeritModal(lista) {
    formaterar = lista[1].split(' ')
    formaterar.splice(0, 1);
    seleccionarOpcion(lista[0]);
    disableOpcion(lista[3]);
    $('#submerit-descripcion-edit').val(formaterar.join(" "));
    $('#submerit-porcentaje-edit').val(lista[2]);
    $('#submerit-id').val(lista[3]);
}
$('#requirementsEditModal').on('hidden.bs.modal', () => {
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

function selectTematicaModal(mporcentajes,mareas) {
    $('#nombre-tem-edit')
        .find('option')
        .remove()
        .end()
        .append('<option value="whatever">'+mporcentajes['nombre']+'</option>')
        .val('whatever')
    ;
    mareas.forEach(area => {
        document.getElementById('.'+area['id']+'-edit').value = "";
        document.getElementById('.'+area['id']+'-edit').disabled = true;
        document.getElementById(area['id']+'-edit').checked = false; 
        document.getElementById(area['id']+'-edit').value = area['id'];
        document.getElementById(area['id']+'-check2-edit').disabled = true;
        document.getElementById(area['id']+'-check2-edit').checked = false;
        document.getElementById(area['id']+'-dep-edit').disabled = true;
    });
    mporcentajes['areas'].forEach(area => {
        document.getElementById('.'+area['id_area']+'-edit').value = area['porcentaje'];
        document.getElementById('.'+area['id_area']+'-edit').disabled = false;
        document.getElementById(area['id_area']+'-edit').checked = true;
        // document.getElementById(area['id_area']+'-edit').value = area['id'];
        document.getElementById(area['id_area']+'-check2-edit').disabled = false;
        if(area['id_porc_dependiente'] != null){
            document.getElementById(area['id_area']+'-check2-edit').checked = true;
        }
    });
    mporcentajes['areas'].forEach(area => {
        if(area['id_porc_dependiente'] != null){
            check_dep_edit(area['id_area'],mareas);
            document.getElementById(area['id_area']+'-dep-edit').disabled = false;
        }
    });
}

function seleccionarOpcion(dato) {
    document.getElementById('id-option-' + dato).setAttribute('selected','');
}

function disableOpcion(dato) {
    $('option').removeAttr('disabled');
    document.getElementById('id-option-' + dato).setAttribute('disabled','');
}

$('#convocatoriaModal').on('hidden.bs.modal', () => {
    $("#conv-titulo").val("");
    $("#conv-descripcion").val("");
    $("#conv-fecha-ini").val("");
    $("#conv-fecha-fin").val("");
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

$('#meritModal').on('hidden.bs.modal', () => {
    $("#merit-descripcion").val("");
    $("#merit-porcentaje").val("");
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

$('#subMeritModal').on('hidden.bs.modal', () => {
    $("#submerit-descripcion").val("");
    $("#submerit-porcentaje").val("");
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

$('#meritModalEdit').on('hidden.bs.modal', () => {
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

$('#subMeritModalEdit').on('hidden.bs.modal', () => {
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
});

$(document).ready(function() {
    $('.select2').select2({
        width: "100%",
        language: "es",
        allowClear: true,
        theme: "classic",
        placeholder: "Selecciona tus opciones"
    });
});

function comprobar(listaEva) {
    document.getElementById("button-guardar").disabled = false;
    let existe = true;
    for (const item of listaEva) {
        if($('#adm-cono-ci').val() == item['ci']) {
            existe = false;
            $('#err').removeClass('error');
            $('#ci-no-existe').removeClass('d-none');
            $('#ci-existe').addClass('d-none');
            setTimeout(() => {
                $('#ci-no-existe').addClass('d-none');
            }, 5000);
            document.getElementById("adm-nom").value = item['nombre'];
            document.getElementById("adm-ape").value = item['apellido'];
            document.getElementById("adm-correo").value = item['correo'];
            document.getElementById("adm-correo2").value = item['correo_alt'];
            document.getElementById("adm-nom").disabled = true;
            document.getElementById("adm-ape").disabled = true;
            document.getElementById("adm-correo").disabled = true;
            document.getElementById("adm-correo2").disabled = true;
        }
    }

    if (existe) {
        document.getElementById("adm-nom").disabled = false;
        document.getElementById("adm-nom").value = "";
        document.getElementById("adm-nom").placeholder="Ingrese su nombre"
        document.getElementById("adm-ape").disabled = false;
        document.getElementById("adm-ape").value = "";
        document.getElementById("adm-ape").placeholder="Ingrese su apellido"
        document.getElementById("adm-correo").disabled = false;
        document.getElementById("adm-correo").value = "";
        document.getElementById("adm-correo").placeholder="Ingrese su correo"
        document.getElementById("adm-correo2").disabled = false;
        document.getElementById("adm-correo2").value = "";
        document.getElementById("adm-correo2").placeholder="Ingrese su correo alternativo"
        $('#ci-existe').removeClass('d-none');
        $('#ci-no-existe').addClass('d-none');
        setTimeout(() => {
            $('#ci-existe').addClass('d-none');
        }, 5000);
    }
}

function comprobarRotulo(listaRotulos,listaAux) {
    document.getElementById("bttn-post").disabled = false;
    let existe = true;
    for (const item of listaRotulos) {
        if($('#adm-post-rotulo').val() == item['rotulo']) {
            existe = false;
            $('#err').removeClass('error');
            $('#rotulo-no-existe').removeClass('d-none');
            $('#rotulo-existe').addClass('d-none');
            setTimeout(() => {
                $('#rotulo-no-existe').addClass('d-none');
            }, 5000);
            document.getElementById("id-conv-postulante").value = item['id_convocatoria'];
            document.getElementById("post-cod").value = item['cod_sis'];
            document.getElementById("post-nom").value = item['nombre'];
            document.getElementById("post-ape").value = item['apellido'];
            document.getElementById("post-cor").value = item['correo'];
            document.getElementById("post-dir").value = item['direccion'];
            document.getElementById("post-ci").value = item['ci'];
            document.getElementById("post-tel").value = item['telefono'];
            document.getElementById("post-cod").disabled = false;
            document.getElementById("post-nom").disabled = false;
            document.getElementById("post-ape").disabled = false;
            document.getElementById("post-dir").disabled = false;
            document.getElementById("post-cor").disabled = false;
            document.getElementById("post-ci").disabled = false;
            document.getElementById("post-tel").disabled = false;
            document.getElementById("post-hojas").disabled = false;
            var auxs = listaAux[item['id']];
            var selectem = document.getElementById("auxiliaturas");
            $('#auxiliaturas').find('option').remove().end() ;
            for(i = 0; i < auxs.length; i++){
                var option = new Option(auxs[i].nombre_aux, auxs[i].id_aux);
                option.selected = true;
                selectem.append(option);
            }
            break;
        }
    }
    if (existe) {
        document.getElementById("bttn-post").disabled = true;
        var selectem = document.getElementById("auxiliaturas");
        $('#auxiliaturas').find('option').remove().end() ;
        $('#rotulo-existe').removeClass('d-none');
        $('#rotulo-no-existe').addClass('d-none');
        setTimeout(() => {
            $('#rotulo-existe').addClass('d-none');
        }, 5000);
        document.getElementById("id-conv-postulante").value = "";
        document.getElementById("post-cod").value = "";
        document.getElementById("post-nom").value = "";
        document.getElementById("post-ape").value = "";
        document.getElementById("post-cor").value = "";
        document.getElementById("post-dir").value = "";
        document.getElementById("post-ci").value = "";
        document.getElementById("post-tel").value = "";
        document.getElementById("post-cod").disabled = true;
        document.getElementById("post-nom").disabled = true;
        document.getElementById("post-ape").disabled = true;
        document.getElementById("post-dir").disabled = true;
        document.getElementById("post-cor").disabled = true;
        document.getElementById("post-ci").disabled = true;
        document.getElementById("post-tel").disabled = true;
        document.getElementById("post-hojas").disabled = true;
    }
}


$('#recipeCarousel').carousel({
    interval: 10000
  })
  
  $('.carousel .carousel-item').each(function(){
      var minPerSlide = 3;
      var next = $(this).next();
      if (!next.length) {
      next = $(this).siblings(':first');
      }
      next.children(':first-child').clone().appendTo($(this));
      
      for (var i=0;i<minPerSlide;i++) {
          next=next.next();
          if (!next.length) {
              next = $(this).siblings(':first');
            }
          
          next.children(':first-child').clone().appendTo($(this));
        }
  });

  $('#upload-pdf').on('change', function () {
    //get the file name
    var fileName = $(this).val().replace(/^.*[\\\/]/, '');
    //replace the "Choose a file" label
    $(this).next('.custom-file-label').html(fileName);
  })
 
  function editEvalConociminetos(evaluador, tematicas, listamulti){
    var selectem = document.getElementById("select-cono");
    $('#select-cono').find('option').remove().end() ;
    for(i = 0; i < tematicas.length; i++){
        if(tematicas[i].id_eva == evaluador.id_eva_conv){
            var option = new Option(tematicas[i].nombre, tematicas[i].id);
            option.selected = true;
            selectem.append(option);
        }   
    }
    for(i = 0; i < listamulti.length; i++){
        if($('#select-cono').find("option[value=" + listamulti[i].id_unico + "]").length != 1){
            var option = new Option(listamulti[i].nombre, listamulti[i].id_unico);
            option.selected = false;
            selectem.append(option);
        }    
    }
    $('#id_eva_conv').val(evaluador.id_eva_conv);
    $('#id-evaluador').val(evaluador.id);
    $('#adm-cono-ci-edit').val(evaluador.ci);
    $('#adm-cono-nombre-edit').val(evaluador.nombre);
    $('#adm-cono-apellidos-edit').val(evaluador.apellido);
    $('#adm-cono-correo-edit').val(evaluador.correo);
    $('#adm-cono-correo2-edit').val(evaluador.correo_alt);
}

setTimeout(() => {
    document.querySelectorAll(".message-error").forEach(e => e.parentNode.removeChild(e));
}, 8000);

function listaAux(datos, id) {
    document.querySelectorAll(".eliminar").forEach(e => e.parentNode.removeChild(e));
    $('#post-ci').val('');
    $('#post-tel').val('');
    $('#post-nom').val('');
    $('#post-ape').val('');
    $('#post-cod').val('');
    $('#post-cor').val('');
    $('#post-dir').val('');
    $('#id-conv-postulante').val(id);
    datos.forEach(data => {
        if (data.id_conv == id) {
            $('#auxiliaturas').prepend(`<option class="eliminar" value="${data.id}">${data.nombre_aux}</option>`);
        }
    });
}

// scripts de la navegacion de evaluador
if ((window.location.pathname).match(/calificar/) !== null) {
    document.querySelector('.mis-convocatorias .menu-icono').addEventListener('click', () => {
        $('.mis-convocatorias .menu').toggleClass('d-none');
    });
} else if ((window.location.pathname).match(/evaluador/) !== null) {
    document.querySelector('.mis-convocatorias .menu-icono').addEventListener('click', () => {
        $('.mis-convocatorias .menu').toggleClass('d-none');
    });
}

$('.btn-2').on('click', () => {
    $('.menu-2').toggleClass('d-none');
});

// fin de los scripts de la navegacion del evaluador

// Inicio script de revision de requisitos
function validarRequisito($idAuxiliatura, $idRequisito) {
    $mapVerifications = JSON.parse(document.getElementById("mapverification").value);
    $mapVerifications[$idAuxiliatura][$idRequisito]['esValido'] = true;
    $('#mapverification').val( JSON.stringify($mapVerifications));
    document.getElementById('options'+$idAuxiliatura+$idRequisito+'1').className = 'btn btn-success';
    document.getElementById('options'+$idAuxiliatura+$idRequisito+'2').className = 'btn btn-secondary';

    document.getElementById('obsLabel'+$idAuxiliatura+$idRequisito).style.display = "none";

    tagTextArea = document.getElementById('obsText'+$idAuxiliatura+$idRequisito);
    tagTextArea.style.display = 'none';
    tagTextArea.required = false;
    // $('#obsText'+$idAuxiliatura+$idRequisito).val('');
}

function desValidarRequisito($idAuxiliatura, $idRequisito) {
    $mapVerifications = JSON.parse(document.getElementById("mapverification").value);
    $mapVerifications[$idAuxiliatura][$idRequisito]['esValido'] = false;
    $('#mapverification').val(JSON.stringify($mapVerifications));
    document.getElementById('options'+$idAuxiliatura+$idRequisito+'1').className = "btn btn-secondary";
    document.getElementById('options'+$idAuxiliatura+$idRequisito+'2').className = 'btn btn-danger';

    document.getElementById('obsLabel'+$idAuxiliatura+$idRequisito).style.display='block';

    tagTextArea = document.getElementById('obsText'+$idAuxiliatura+$idRequisito);
    tagTextArea.style.display='block';
    tagTextArea.required = false;
}


function mostrarModalMeritos(calificacionMerito, formato){
    document.getElementById("porcentajeMerito").innerHTML = calificacionMerito.porcentaje;
    document.getElementById("descripcion").innerHTML=formato[1];
    $("#idMerito").val(calificacionMerito.idCalificacion);
    $("#procentajeMer").val(calificacionMerito.porcentaje);
}

function verificarNotasMerito(lst){
    rs=true;
    for(k=0; k<lst.length; k++){
        aux=parseInt(lst[k]);
        console.log(aux);
        rs= rs && (aux < 101);
    }
    return rs;
}

function calcular(){
    notas=document.getElementById("notasMeritos").value;
    if((notas !== "") && (notas !== null)){
        listaNotas=validar(notas);
        if (listaNotas !== null){

            if(document.getElementById('inlineRadio2').checked){
                if(verificarNotasMerito(listaNotas)){
                    notadelMerito= 0;
                    for(var i2 = 0; i2 < listaNotas.length; i2++){
                        numero = parseInt(listaNotas[i2]);
                        notadelMerito+= numero;
                    }
                    notadelMerito=notadelMerito/(listaNotas.length);
                    multiplicador=parseInt($("#procentajeMer").val())/100;
                    console.log(multiplicador)
                    console.log(notadelMerito)
                    console.log(trunc((notadelMerito*multiplicador),2));
                    $("#notaMerito").val(trunc((notadelMerito*multiplicador),2));
                    document.getElementById("guardar").disabled=false;
                }else{
                    console.log('las notas no deven pasar de 100');
                }
            }    
            else if(document.getElementById('inlineRadio1').checked){
                notadelMerito= 0;
                for(var i2 = 0; i2 < listaNotas.length; i2++){
                    numero = parseInt(listaNotas[i2]);
                    notadelMerito+= numero;
                }
                maximo=parseInt($("#procentajeMer").val());
                if(notadelMerito>maximo){
                    $("#notaMerito").val(maximo);
                    document.getElementById('guardar').disabled=false;
                }else{
                    $("#notaMerito").val((notadelMerito));
                    document.getElementById('guardar').disabled=false;
                }
            }else{
                console.log('selecionar una opcion');
            }
        }
    }else{
        $("#porcentaje").val('');
        $("#notaMerito").val('');
        console.log("insertar notas");
    }

} 

function validar(notas){
    lista=notas.split("+");
    longi=lista.length;
    for(indice= 0; indice < longi; indice++){
        if(isNaN(lista[indice])){
            lista=null;
            break;
        }
    }
    return lista;
}
    
function trunc (x, posiciones = 0) {
    var s = x.toString()
    var l = s.length
    var decimalLength = s.indexOf('.') + 1
  
    if (l - decimalLength <= posiciones){
      return x
    }
    var isNeg  = x < 0
    var decimal =  x % 1
    var entera  = isNeg ? Math.ceil(x) : Math.floor(x)
    var decimalFormated = Math.floor(
      Math.abs(decimal) * Math.pow(10, posiciones)
    )
    var finalNum = entera + 
      ((decimalFormated / Math.pow(10, posiciones))*(isNeg ? -1 : 1))
    
    return finalNum
  }
//fin calificar merito estudiante

// Asignacion de items

$('#invitarPostulanteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var mid = button.data('asig_id_auxiliatura')
    var modal = $(this)
    modal.find('.modal-body #asig_id_auxiliatura').val(mid);
    
})

//fin asignar items
$('#pre-posts-habilitar').on('click', () => {
    res = confirm('Esta seguro de habilitar o desabilitar a los PRE POSTULANTES');
    if (!res) event.preventDefault();
});

$('#eliminar-pre-postulantes').on('click', () => {
    res = confirm('Esta seguro de Publicar Ganadores');
    if (!res) event.preventDefault();
});

// Auxiliaturas del laboratorio y docencia

function cargarAuxLab(auxiliatura) {
    $('#nombre-aux-lab').val(auxiliatura['nombre_aux']);
    $('#codigo-aux-lab').val(auxiliatura['cod_aux']);
    $('#id-aux-lab').val(auxiliatura['id']);
}

// Tematica de laboratorio

function cargarAuxTem(tematica){
    $('#nombre-tem-id').val(tematica['nombre']);
    $('#id-tem-lab').val(tematica['id']);
}

// Script editar avisos

function avisoEditModal(aviso) {
    $('#idAvisoEdit').val(aviso['id']);
    $('#avisoTituloEdit').val(aviso['titulo_aviso']);
    $('#avisoDescripcionEdit').val(aviso['descripcion_aviso']);
}


$(document).ready(function() {
    $('#tablaMeritos').DataTable({
    "pageLength":70,"bPaginate": false,
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    },"bLengthChange": false,responsive: true,
    order: [0, 'asc'],  "bInfo" : false
    });

});

function cargarAuxArea(area){
    $('#nombre-area-id').val(area['nombre']);
    $('#id-area-lab').val(area['id']);
}

$('#area-guardar').on('click', () => {
    res = confirm('Esta seguro de modificar esta Area, los cambios afectaran a las consultas historicas. Si es una modificación drastica se sujiere crear otra Area.');
    if (!res) event.preventDefault();
});
$('#tematica-guardar').on('click', () => {
    res = confirm('Esta seguro de modificar esta Tematica, los cambios afectaran a las consultas historicas. Si es una modificación drastica se sujiere crear otra Tematica.');
    if (!res) event.preventDefault();
});
$('#auxiliatura-guardar').on('click', () => {
    res = confirm('Esta seguro de modificar esta Auxiliatura, los cambios afectaran a las consultas historicas. Si es una modificación drastica se sujiere crear otra Auxiliatura.');
    if (!res) event.preventDefault();
});

function startTime() {
    let hora = new Date();
    let hr = hora.getHours();
    let min = hora.getMinutes();
    let sec = hora.getSeconds();
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);
    document.getElementById("time-real").innerHTML = `${hr}:${min}:${sec}`;
    setTimeout(function(){ startTime() }, 1000);
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

$('#btn-entregar').on('click', () => {
    res = confirm('Esta usted seguro de entregar las notas de esta Temática.');
    if (!res) event.preventDefault();
});

$('#btn-desierto').on('click', () => {
    res = confirm('Esta usted seguro de declarar como desierto las notas de esta Temática.');
    if (!res) event.preventDefault();
});

$('.notas-guardar').each((index, element) => {
    if (element.value !== '') {
        $('#btn-entregar').addClass('d-inline-block')
    }
});

function enviarCorreo() {
    res = confirm('Se enviara un correo al evaluador con su usuario y nueva contraseña.');
    if (!res) event.preventDefault();
}


function datosAsignacion(data, postulante) {
    if (postulante['estado'] === 'Postulante Reprobado') {
        for (let i = 0; i < data.length; i++) {
            if (data[i]['estado'] === 'Postulante Aprobado'){
                res = confirm('Esta seguro de invitar al postulante, exiten postulantes aprobados sin asignar.');
                if (!res) event.preventDefault();
                break;
            }
            
        }
    }
}
function calificarRequisito(id){
    res = confirm('Este postulante ya esta siendo calificado, esta usted seguro de calificar.');
    if (!res) event.preventDefault();
}

$('#btn-entregarRequisitos').on('click', () => {
    res = confirm('¿Esta seguro que desa entregar todas las calificaciones?.');
    if (!res) event.preventDefault();
})

$('#btn-entregarMeritos').on('click', () => {
    res = confirm('¿Esta seguro que desa entregar todas las calificaciones?.');
    if (!res) event.preventDefault();
})

function calificarMeritos(id){
    res = confirm('Este postulante ya esta siendo calificado, esta usted seguro de calificar.');
    if (!res) event.preventDefault();
}

//Verificacion publicar asignaciones
function isChecked(checkbox, sub1) {
    document.getElementById(sub1).disabled = !checkbox.checked;
}