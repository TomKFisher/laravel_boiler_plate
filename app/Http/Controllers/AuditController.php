<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * RoleController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request, 'audit');
        
        $this->middleware(function ($request, $next) {
            if(!auth()->user()->can('restore-audit'))
                return redirect()->back()->with('message', 'You are not authorised to restore Audit Logs, please contact your admin for access.');
            return $next($request);
        })->only('restore_audit');
    }

    /**
     * Display a listing of the audit logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if( !empty( request()->get('s') ) ){
        //     $roles = Audit::where('display_name', 'like', '%'.request()->get('s').'%')->paginate(10);
        // }else{
        //     $roles = Audit::paginate(10);
        // }
        $logs = AuditLog::paginate(10);
        return view('admin.audit_logs.index',[
            'logs' => $logs,
            's' => request()->get('s')
        ]);
    }

    /**
     * #Display the specified role.
     * @param Audit $log
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(AuditLog $log)
    {        
        return view('admin.audit_logs.show', [
            'log' => $log,
            'log_modified' => $log->getModified(),
            'log_meta' => $log->getMetadata()
        ]);
    }

    public function restore_audit(AuditLog $log)
    {
        if(!in_array($log->event, ['deleted', 'updated']))
            return redirect()->back()->with('error', 'This Audit Log is not a Delete or Update, you cannot restore its values.');
        
        try {   
            $restore = $log->auditable_type::where('id', $log->auditable_id)->withTrashed()->first();
            switch($log->event){
                case 'deleted':
                    if($restore == null){
                        $restore = new $log->auditable_type;
                    }else{
                        $restore->deleted_at = null;
                    }
                    break;
                case 'updated':
                    if($restore == null)
                        return redirect()->back()->with('error', 'There was an error restoring this Audit Log. Cannot find the Object, it may have been deleted by a later action.');        
                    break;
            }
            
            foreach($log->getModified() as $field => $values){
                if(!in_array($field, ['created_at', 'updated_at']))
                    $restore->{$field} = $values['old'];
            }
            $restore->save();

            return redirect()->route('logs.index')->with('success', 'Audit Log successfully reverted.');
        } catch (Exception $e) { 
            return redirect()->back()->with('error', 'There was an error restoring this Audit Log.');
        }
    }
}
