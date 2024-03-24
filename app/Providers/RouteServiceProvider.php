<?php

namespace App\Providers;

use App\Exceptions\InsuraModelNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends ServiceProvider {
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router) {
        //

        parent::boot($router);

        $router->bind('broker', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('brokers.message.error.missing');
            }
        });
        $router->bind('client', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('clients.message.error.missing');
            }
        });
        $router->bind('recipient', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('users.message.error.missing');
            }
        });
        $router->bind('sender', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('users.message.error.missing');
            }
        });
        $router->bind('staff', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('staff.message.error.missing');
            }
        });
        $router->bind('user', function($value) {
            try {
                return \App\Models\User::withStatus()->findOrFail($value);
            }catch(ModelNotFoundException $e) {
                throw new InsuraModelNotFoundException('users.message.error.missing');
            }
        });
        $router->model('attachment', 'App\Models\Attachment', function() {
            throw new InsuraModelNotFoundException('attachments.message.error.missing');
        });
        $router->model('chat', 'App\Models\Chat', function() {
            throw new InsuraModelNotFoundException('chats.message.error.missing');
        });
        $router->model('company', 'App\Models\Company', function() {
            throw new InsuraModelNotFoundException('companies.message.error.missing');
        });
        $router->model('email', 'App\Models\Email', function() {
            throw new InsuraModelNotFoundException('communication.message.error.missing.email');
        });
        $router->model('note', 'App\Models\Note', function() {
            throw new InsuraModelNotFoundException('notes.message.error.missing');
        });
        $router->model('policy', 'App\Models\Policy', function() {
            throw new InsuraModelNotFoundException('policies.message.error.missing');
        });
        $router->model('product', 'App\Models\Product', function() {
            throw new InsuraModelNotFoundException('products.message.error.missing');
        });
        $router->model('reminder', 'App\Models\Reminder', function() {
            $exc = new InsuraModelNotFoundException('reminders.message.errors.missing');
            $exc->setBack(true);
            throw $exc;
        });
        $router->model('text', 'App\Models\Text', function() {
            throw new InsuraModelNotFoundException('communication.message.error.missing.text');
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router) {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
