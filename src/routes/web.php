<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    //project routes
    Route::post('/project', [App\Http\Controllers\ProjectsController::class, 'store'])->name('project.store');
    Route::get('/project', [App\Http\Controllers\ProjectsController::class, 'index'])->name('project.all');
    Route::delete('/project/{project}', [App\Http\Controllers\ProjectsController::class, 'destroy'])->name('project.delete');
    Route::get('/project/{project}', [App\Http\Controllers\ProjectsController::class, 'show'])->name('project.show');
    Route::patch('/project/{project}', [\App\Http\Controllers\ProjectsController::class, 'update'])->name('project.update');
    Route::patch('/project/{project}/notes', [\App\Http\Controllers\ProjectsController::class, 'updateNotes'])->name('project.update.notes');

    //project invitations
    Route::post('/project/{project}/invite', [App\Http\Controllers\ProjectInviteController::class, 'invite'])->name('project.invite');
    //project tasks routes
    Route::post('/project/{project}/task', [\App\Http\Controllers\TaskController::class, 'store'])->name('task.create');
    Route::patch('/project/{project}/task/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('task.update');

    Route::get('/activity', [\App\Http\Controllers\ActivityController::class, 'index'])->name('activity.index');
    Route::delete('/activity/{activity}', [\App\Http\Controllers\ActivityController::class, 'destroy'])->name('activity.delete');

    Route::get('/home', function () { return redirect()->route('project.all'); });
    Route::get('/', function () {  return redirect()->route('project.all'); })->name('home');
});