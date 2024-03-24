<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'IndexController@get');

// Setup & installation routes...
Route::get('setup/cache', 'SetupController@cache');
Route::get('setup/install', 'SetupController@install');
Route::get('setup', 'SetupController@get');
Route::post('setup/configure', 'SetupController@configure');

// Dynamic JS asset route
Route::get('assets/js/insura.js', 'IndexController@javascript');

// Dashboard route
Route::get('dashboard', 'IndexController@getDashboard');

// Authentication routes...
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth', 'Auth\AuthController@getAuth');
Route::post('auth/login', 'Auth\AuthController@postLogin');

// Registration routes...
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Activation routes...
Route::get('auth/activate/{token}', 'Auth\PasswordController@getActivate');
Route::post('auth/activate', 'Auth\PasswordController@postActivate');

// Password routes...
Route::get('auth/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('auth/reset/email', 'Auth\PasswordController@postEmail');
Route::post('auth/reset/password', 'Auth\PasswordController@postReset');
Route::post('auth/reset', 'Auth\PasswordController@update');

// User routes...
Route::post('users/{user}', 'UserController@edit');

// Settings routes...
Route::get('settings/cache', 'SettingController@cache');
Route::get('settings/load', 'SettingController@load');
Route::get('settings', 'SettingController@get');
Route::post('settings', 'SettingController@edit');

// Company routes...
Route::delete('companies/{company}', 'CompanyController@delete');
Route::get('companies', 'CompanyController@getAll');
Route::post('companies/{company}', 'CompanyController@edit');

// Product routes...
Route::delete('products/{product}', 'ProductController@delete');
Route::get('products', 'ProductController@getAll');
Route::post('products/{product}', 'ProductController@edit');
Route::post('products', 'ProductController@add');

// Staff routes...
Route::delete('staff/{staff}', 'StaffController@delete');
Route::get('staff/{staff}', 'StaffController@getOne');
Route::get('staff', 'StaffController@getAll');
Route::post('staff', 'StaffController@add');
Route::post('staff/{staff}', 'StaffController@edit');

// Broker routes...
Route::delete('brokers/{broker}', 'BrokerController@delete');
Route::get('brokers/{broker}', 'BrokerController@getOne');
Route::get('brokers', 'BrokerController@getAll');
Route::post('brokers', 'BrokerController@add');
Route::post('brokers/{broker}', 'BrokerController@edit');

// Client routes...
Route::delete('clients/{client}', 'ClientController@delete');
Route::get('clients/{client}', 'ClientController@getOne');
Route::get('clients', 'ClientController@getAll');
Route::post('clients', 'ClientController@add');
Route::post('clients/{client}', 'ClientController@edit');

// Note routes...
Route::delete('notes/{note}', 'NoteController@delete');
Route::post('notes', 'NoteController@add');

// Reminder routes...
Route::delete('reminders/{reminder}', 'ReminderController@delete');
Route::post('reminders/{company}', 'ReminderController@update');

// Policy routes...
Route::delete('policies/{policy}', 'PolicyController@delete');
Route::get('policies/{policy}', 'PolicyController@getOne');
Route::get('policies', 'PolicyController@getAll');
Route::post('policies', 'PolicyController@add');
Route::post('policies/{policy}', 'PolicyController@edit');

// Attachment routes...
Route::delete('attachments/{attachment}', 'AttachmentController@delete');
Route::post('attachments', 'AttachmentController@add');

// Payment routes...
Route::delete('payments/{payment}', 'PaymentController@delete');
Route::post('payments', 'PaymentController@add');

// Text routes...
Route::delete('texts/{text}', 'TextController@delete');
Route::get('communication/texts/{recipient}', 'TextController@getAll');
Route::post('texts', 'TextController@add');

// Email routes...
Route::delete('emails/{email}', 'EmailController@delete');
Route::get('communication/emails/{recipient}', 'EmailController@getAll');
Route::post('emails', 'EmailController@add');

// Inbox routes...
Route::get('inbox', 'InboxController@getAll');

// Chat routes...
Route::get('chats/live', 'ChatController@live');
Route::post('chats/see', 'ChatController@see');
Route::post('chats', 'ChatController@send');

// Reports routes...
Route::get('reports', 'ReportController@get');

// Communication routes...
Route::get('communication', 'CommunicationController@get');

// Update routes...
Route::get('update/{status}', 'UpdateController@load');
Route::get('update', 'UpdateController@get');
