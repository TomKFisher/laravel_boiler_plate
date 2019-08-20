<?php

namespace App\Http\Middleware;

use Closure;
use View;
use Storage;
use \Hyn\Tenancy\Website\Directory;
use \Hyn\Tenancy\Environment;

class SetTenancyValuesForViews
{
    private $media_folder = 'media';
    private $logo_fallback = 'images/your_logo_here.png';
    private $mini_logo_fallback = 'images/your_mini_logo_here.png';
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hostname = app(Environment::class)->hostname();
        View::share('host_name', $hostname->name);
        View::share('logo_title', 'Add your logo here');
        View::share('logo_url', '#edit_tenant_profile');
        View::share('host_logo', $this->logo_fallback);
        View::share('mini_logo_title', 'Add your icon here');
        View::share('mini_logo_url', '#edit_tenant_profile');
        View::share('host_mini_logo', $this->mini_logo_fallback);

        if(Storage::disk('tenant')->exists($this->media_folder.'/'.$hostname->logo)){
            View::share('logo_title', $hostname->name);
            View::share('logo_url', route('dashboard'));
            View::share('host_logo', $this->media_folder.'/'.$hostname->logo);
        }
        if(Storage::disk('tenant')->exists($this->media_folder.'/'.$hostname->mini_logo)){
            View::share('mini_logo_title', $hostname->name);
            View::share('mini_logo_url', route('dashboard'));
            View::share('host_mini_logo', $this->media_folder.'/'.$hostname->mini_logo);
        }
        
        return $next($request);
    }
}
