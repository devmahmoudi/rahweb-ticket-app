<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    // role
    Route::prefix('role/')->name('role.')->group(function (){
       Route::get('/', App\Livewire\Role\Index::class)->name('index');
       Route::get('/{role}/permissions', App\Livewire\Role\Permissions::class)->name('permission');
       Route::get('/create', App\Livewire\Role\Create::class)->name('create');
       Route::get('/{role}/edit', App\Livewire\Role\Edit::class)->name('edit');
       Route::get('/{role}/users', App\Livewire\Role\Users::class)->name('users');
       Route::get('/{role}/add/user', App\Livewire\Role\AddUser::class)->name('add.user');
    });

    // workgroup
    Route::prefix('workgroup/')->name('workgroup.')->group(function (){
        Route::get('/', \App\Livewire\Workgroup\Index::class)->name('index');
        Route::get('/create', App\Livewire\Workgroup\Create::class)->name('create');
        Route::get('/{workgroup}/edit', App\Livewire\Workgroup\Edit::class)->name('edit');
        Route::get('/{workgroup}/users', App\Livewire\Workgroup\Users::class)->name('users');
        Route::get('/{workgroup}/add/user', App\Livewire\Workgroup\AddUser::class)->name('add.user');

    });

    // users
    Route::prefix('user/')->group(function(){
        // operator
        Route::prefix('operator/')->name('operator.')->group(function (){
            Route::get('/', \App\Livewire\Operator\Index::class)->name('index');
            Route::get('/create', App\Livewire\Operator\Create::class)->name('create');
            Route::get('/{operator}/edit', App\Livewire\Operator\Edit::class)->name('edit');
        });

        // customer
        Route::prefix('customer/')->name('customer.')->group(function (){
            Route::get('/', \App\Livewire\Customer\Index::class)->name('index');
            Route::get('/{user}', \App\Livewire\Customer\Show::class)->name('show');
        });
    });

    // ticket
    Route::prefix('ticket/')->name('ticket.')->group(function(){
        Route::get('/', \App\Livewire\Ticket\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Ticket\Create::class)->name('create');
        Route::get('/close/{ticket}', \App\Http\Controllers\CloseTicketController::class)->name('close');
    });

    // cartable
    Route::prefix('cartable/')->name('cartable.')->group(function(){
        Route::get('/', \App\Livewire\Cartable\Index::class)->name('index');
    });

    // chat
    Route::prefix('/chat')->group(function(){
       Route::get('/{chat?}', \App\Livewire\Messenger\Chat::class)->name('chat');
    });

    // Task
    Route::prefix('/task')->name('task.')->group(function(){
        Route::get('/', \App\Livewire\Task\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Task\Create::class)->name('create');
        Route::get('/close/{task}', \App\Http\Controllers\CloseTaskController::class)->name('close');
        Route::get('/referral/{task}', \App\Livewire\Task\Referral::class)->name('referral');
    });

    // Purchase
    Route::prefix('/purchase')->name('purchase.')->group(function(){
        Route::get('/', \App\Livewire\Purchase\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Purchase\Create::class)->name('create');
        Route::get('/{purchase}/edit', App\Livewire\Purchase\Edit::class)->name('edit');
        Route::get('/{purchase}', \App\Livewire\Purchase\Show::class)->name('show');
    });

    // Meeting
    Route::prefix('/meeting')->name('meeting.')->group(function(){
        Route::get('/', \App\Livewire\Meeting\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Meeting\Create::class)->name('create');
        Route::get('/{meeting}/edit', App\Livewire\Meeting\Edit::class)->name('edit');
        Route::get('/{meeting}', \App\Livewire\Meeting\Show::class)->name('show');
    });

    // Bug
    Route::prefix('/bug')->name('bug.')->group(function(){
        Route::get('/', \App\Livewire\Bug\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Bug\Create::class)->name('create');
        Route::get('/{bug}/edit', App\Livewire\Bug\Edit::class)->name('edit');
        Route::get('/{bug}', \App\Livewire\Bug\Show::class)->name('show');
    });

    // Media
    Route::prefix('/media')->name('media.')->group(function(){
        Route::get('/', \App\Livewire\Media\Index::class)->name('index');
        Route::get('/upload', \App\Livewire\Media\Upload::class)->name('upload');
//        Route::get('/{media}/edit', App\Livewire\Media\Edit::class)->name('edit');
//        Route::get('/{media}', \App\Livewire\Media\Show::class)->name('show');
    });
});
//
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
