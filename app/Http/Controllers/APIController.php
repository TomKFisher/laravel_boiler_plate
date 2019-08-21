<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository;

class APIController extends Controller
{
    private $client_model;
    private $client_repo;
    
    /**
     * APIController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request, 'api-key');
        
        $this->middleware(function ($request, $next) {
            if(!auth()->user()->can('generate-api-key'))
                return redirect()->back()->with('message', 'You are not authorised to generate a new API Key, please contact your admin for access.');
            return $next($request);
        })->only('generate');

        $this->middleware(function ($request, $next) {
            if(!auth()->user()->can('revoke-api-key'))
                return redirect()->back()->with('message', 'You are not authorised to revoke API Keys, please contact your admin for access.');
            return $next($request);
        })->only('revoke');

        $client_model = Passport::clientModel();
        $this->client_model = new $client_model();
        
        $this->client_repo = new ClientRepository();
    }

    /**
     * Display a page showing the generated API key.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return view('admin.api-key.index', [
            'client' => $this->client_model->where('revoked', 0)->where('password_client', 1)->first()
        ]);
    }

    public function generate()
    {
        $hostname  = app(\Hyn\Tenancy\Environment::class)->hostname();

        $this->client_repo->createPasswordGrantClient(
            null, $hostname->fqdn, ''
        );
        
        return redirect()->back()->with('success', 'Your REST API key has been generated');
    }

    public function revoke(Request $request)
    {        
        $client = $this->client_model->where('id', $request->id)->first();

        if(is_null($client))
            return redirect()->back()->with('error', 'The system cannot find the client key to revoke');

        $this->client_repo->delete($client);

        return redirect()->back()->with('success', 'Your REST API key has been revoked');
    }
}
