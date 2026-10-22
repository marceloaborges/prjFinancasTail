<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Painel\CargoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Painel\UserController;
use App\Http\Controllers\Log\LogController;

Route::redirect('/', '/login');

Route::middleware(['auth','verified','check2fa'])
    ->prefix('painel')
    ->as('painel.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('cargos', CargoController::class)->only(['index', 'store', 'update', 'destroy']);
        // Route::resource('cargos', CargoController::class)->except(['create', 'edit', 'show']);

        Route::resource('logs', LogController::class)->only(['index']);

        Route::resource('users', UserController::class);


        /*
            Route::resource('clientes', ClienteController::class);

            Route::any('clientes-search', [
                ClienteController::class,
                'search'
            ])->name('clientes.search');
        
        */

        

});


// 🔓 ROTAS DO 2FA (O usuário precisa estar logado, mas elas ficam ANTES do middleware)
Route::middleware(['auth'])->prefix('painel/2fa')->name('painel.2fa.')->group(function () {
    
    // Passando o caminho completo da classe para evitar erros de Target Class
    Route::get('/', [\App\Http\Controllers\Painel\TwoFactorController::class, 'index'])->name('index');
    Route::post('/verificar', [\App\Http\Controllers\Painel\TwoFactorController::class, 'verificar'])->name('verificar');
    
});


Route::any('/register', function () {
    abort(404);
});

Route::any('/forgot-password', function () {
    abort(404);
});


/*

// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');

*/

