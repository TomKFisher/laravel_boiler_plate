<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $resource_route_suffix;
    
    /**
     * Controller constructor.
     * @param Request $request
     * @param null $resource_route_suffix
     */
    public function __construct(Request $request, $resource_route_suffix = null){
        if($resource_route_suffix != null){
            $this->middleware(function ($request, $next) use ($resource_route_suffix) {
                if(!auth()->user()->can('browse-'.$resource_route_suffix))
                    return $this->redirect('to browse', $resource_route_suffix);
                return $next($request);
            })->only('index');
            
            $this->middleware(function ($request, $next) use ($resource_route_suffix) {
                if(!auth()->user()->can('read-'.$resource_route_suffix))
                    return $this->redirect('to view this', $resource_route_suffix);
                return $next($request);
            })->only('show');
    
            $this->middleware(function ($request, $next) use ($resource_route_suffix) {
                if(!auth()->user()->can('edit-'.$resource_route_suffix))
                    return $this->redirect('to amend this', $resource_route_suffix);    
                return $next($request);
            })->only('edit', 'update');    
            
            $this->middleware(function ($request, $next) use ($resource_route_suffix) {
                if(!auth()->user()->can('add-'.$resource_route_suffix))
                    return $this->redirect('to create new', $resource_route_suffix);
                return $next($request);
            })->only('create', 'store');

            $this->middleware(function ($request, $next) use ($resource_route_suffix) {
                if(!auth()->user()->can('delete-'.$resource_route_suffix))
                    return $this->redirect('to archive', $resource_route_suffix);    
                return $next($request);
            })->only('destroy', 'archive', 'restore', 'delete');
        }
    }

    public function redirect($action = 'to browse', $resource_route_suffix = '', $alert_type = 'error'){
        return redirect()->back()->with($alert_type, 'You are not authorised ' . $action . ' ' . str_plural(ucfirst($resource_route_suffix)) . ', please contact your admin for access.');
    }
}
