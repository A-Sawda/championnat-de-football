<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!

php artisan serve
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
Route::get('/', [Controller::class, 'showRanking'])->name('ranking.show');
Route::get('/teams/{teamId}', [Controller::class, 'showTeam'])->where('teamId', '[0-9]+')->name('teams.show');
Route::get('/teams/create', [Controller::class, 'createTeam'])->name('teams.create');
Route::post('/teams', [Controller::class, 'storeTeam'])->name('teams.store');
Route::get('/matches/create', [Controller::class, 'createMatch'])->name('matches.create');
Route::post('/matches', [Controller::class, 'storeMatch'])->name('matches.store');
Route::get('/login', [Controller::class, 'showLoginForm'])->name('login');
Route::post('/login', [Controller::class, 'login'])->name('login.post');
Route::get('/teams/{teamId}/follow', [Controller::class, 'followTeam'])->where('teamId', '[0-9]+')->name('teams.follow');
Route::post('/logout', [Controller::class, 'logout'])->name('logout');
Route::get('/deleteMatch', [Controller::class, 'showdeleteMatchForm'])->name('deleteMatch');
Route::post('/deleteMatch', [Controller::class, 'deleteMatch'])->name('deleteMatch.post');
Route::get('/changePassword', [Controller::class, 'showchangePasswordForm'])->name('changePassword');
Route::post('/changePassword', [Controller::class, 'changePassword'])->name('changePassword.post');
Route::get('/addUser', [Controller::class, 'showaddUserForm'])->name('addUser');
Route::post('/addUser', [Controller::class, 'addUser'])->name('addUser.post');
Route::get('/deleteTeam', [Controller::class, 'showdeleteTeamForm'])->name('deleteTeam');
Route::post('/deleteTeam', [Controller::class, 'deleteTeam'])->name('deleteTeam.post');

