<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Gravatar;
use Menu;
use Auth;
use Session;
use HTML;

/**
 * Class MenuServiceProvider
 * @package App\Providers
 */
class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['layouts.blank'], function($view){

            Menu::create('top-menu', function($menu) {
                $menu->setPresenter(\App\Presenters\NavBarMenuPresenter::class);
                $menu->enableOrdering();

                $menu->dropdown(auth()->user()->name, function($sub){
                    $sub->add([
                        'url' => '#', 
                        'attributes' => [
                            'just_content' => true,
                            'content' => '<div class="dropdown-header text-center">
                                <img class="img-md rounded-circle" src="'.Gravatar::get(auth()->user()->email).'" alt="'.auth()->user()->name.'">
                                <p class="mb-1 mt-3 font-weight-semibold">'.auth()->user()->name.'</p>
                                <p class="font-weight-light text-muted mb-0">'.auth()->user()->email.'</p>
                            </div>'
                        ],
                        'order' => 0
                    ]);
                    if( auth()->user()->hasPermissionTo('read-role') ) {
                        $sub->add([
                            'route' => ['roles.index', []],
                            'attributes' => [
                                'content' => 'Roles',
                                'icon' => 'fas fa-key pull-right'
                            ]
                        ]);
                    }
                    if( auth()->user()->hasPermissionTo('read-user') ) {
                        $sub->add([
                            'route' => ['users.index', []],
                            'attributes' => [
                                'content' => 'Users',
                                'icon' => 'fas fa-users pull-right'
                            ]
                        ]);
                    }
                    if( auth()->user()->can('read-audit') ) {
                        $sub->add([
                            'route' => ['logs.index', []],
                            'attributes' => [
                                'content' => 'Audit Logs',
                                'icon' => 'fas fa-file-text-o pull-right'
                            ]
                        ]);
                    }
                    $sub->add([
                        'route' => ['logout', []],
                        'attributes' => [
                            'content' => 'Logout',
                            'icon' => 'fas fa-sign-out-alt pull-right',
                        ],
                        'order' => 99
                    ]);
                }, null, [
                    'class' => 'nav-link',
                    'dropdown-class' => 'dropdown-menu dropdown-menu-right navbar-dropdown',
                    'content' => '<img class="img-xs rounded-circle" src="'.Gravatar::get(auth()->user()->email).'" alt="'.auth()->user()->name.'">',
                ]);
            });

            Menu::create('sidebar', function($menu) {
                $menu->setPresenter(\App\Presenters\SidebarMenuPresenter::class);
                $menu->enableOrdering();
            });

            $sidebar = Menu::instance('sidebar');

            if ($sidebar === null)
                return false;

            $sidebar->add(['route' => ['dashboard',[]], 'title' => 'Dashboard', 'icon' => 'menu-icon fas fa-home fa-fw fa-3x', 'name' => 'Dashboard'])->order(1);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
