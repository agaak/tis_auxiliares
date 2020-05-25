<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use phpDocumentor\Reflection\DocBlock\Tags\Uses;


Route::get('/', ['as' => 'home', 'uses' => 'NavbarPages@home']);

Route::get('/convocatoria', ['as' => 'convocatory', 'uses' => 'NavbarPages@convocatory']);

Route::get('/resultados', ['as' => 'results', 'uses' => 'NavbarPages@results']);

Route::get('/tramites-documentos', ['as' => 'proceduresDocs', 'uses' => 'NavbarPages@proceduresDocs']);

Route::get('/convocatoria/titulo-descripcion', ['as' => 'titleDescription', 'uses' => 'Pages\Convocatory@titleDescription']);
Route::post('/convocatoria/titulo-descripcion', ['as' => 'titleDescriptionValid', 'uses' => 'Pages\Convocatory@titleDescriptionValid']);

Route::get('/convocatoria/requerimientos', ['as' => 'requests', 'uses' => 'Pages\Convocatory@requests']);
Route::post('/convocatoria/requerimientos', ['as' => 'requestValid', 'uses' => 'Pages\Convocatory@requestValid']);
Route::put('/convocatoria/requerimientos', ['as' => 'requestUpdate', 'uses' => 'Pages\Convocatory@requestUpdate']);
Route::delete('/convocatoria/requerimientos/{id}', ['as' => 'requestDelete', 'uses' => 'Pages\Convocatory@requestDelete']);

Route::get('/convocatoria/requisitos', ['as' => 'requirement', 'uses' => 'Pages\Convocatory@requirements']);
Route::post('/convocatoria/requisitos', ['as' => 'requirementValid', 'uses' => 'Pages\Convocatory@requirementsValid']);

Route::get('/convocatoria/fechas-importantes', ['as' => 'importantDates', 'uses' => 'Pages\Convocatory@importantDates']);
Route::post('/convocatoria/fechas-importantes', ['as' => 'importantDatesValid', 'uses' => 'Pages\Convocatory@importantDatesValid']);
Route::put('/convocatoria/fechas-importantes', ['as' => 'importantDateSave', 'uses' => 'Pages\Convocatory@importantDateSave']);
Route::delete('/convocatoria/fechas-importantes/{id}', ['as' => 'importantDatesDelete', 'uses' => 'Pages\Convocatory@importantDatesDelete']);

Route::get('/convocatoria/calificacion-meritos', ['as' => 'meritRating', 'uses' => 'Pages\Convocatory@meritRating']);
Route::post('/convocatoria/calificacion-meritos', ['as' => 'meritRatingValid', 'uses' => 'Pages\Convocatory@meritRatingValid']);
Route::put('/convocatoria/calificacion-meritos', ['as' => 'meritRatingUpdate', 'uses' => 'Pages\Convocatory@meritRatingUpdate']);
Route::delete('/convocatoria/calificacion-meritos/{id}', ['as' => 'meritRatingDelete', 'uses' => 'Pages\Convocatory@meritRatingDelete']);

Route::get('/convocatoria/calificacion-conocimientos', ['as' => 'knowledgeRating', 'uses' => 'Pages\Convocatory@knowledgeRating']);