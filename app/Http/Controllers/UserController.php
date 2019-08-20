<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Hyn\Tenancy\Environment;
use App\Models\User;
use App\Models\Role;
use App\Notifications\UserInvite;
use App\Events\DeleteUser;
use Validator;
use DB;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * UserController constructor. Calls parent Controller constructor to set / check permissions on methods.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Request $request){
        parent::__construct($request, 'user');
    }
    
    /**
     * Display a paginated list of Users, inc. search function on User name or email fields.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);

        return view('admin.users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.Users.create', [
            'user' => new User(),
            'roles' => Role::pluck('display_name', 'id')->toArray()
        ]); 
    }

    /**
     * Store a newly created User in storage.
     * Sends an invitation email to the User for them to verify their email address and set a new password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // setup rules and validate
        $rules = [
            'name' => 'required|alpha_spaces',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required'
        ];
        $messages = [
            'name.required' => 'The User\'s Name is required',
            'name.alpha_spaces' => 'The User\'s Name must only contain letters and spaces',
            'email.required' => 'The User\'s Email is required',
            'email.email' => 'The User\'s Email is in the incorrect format. It must be in the format \'abc@example.com\'',
            'email.unique' => 'The Email address you have entered is already in use. Please enter another one',
            'roles.required' => 'You must select at least one Role for this User'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
            return redirect()->back()->with('error', 'There was an error with your form submission, please review below.')->withInput()->withErrors($validator);

        // create User account
        $user = User::create([
            'name' => ucwords($request->get('name')),
            'email' => strtolower($request->get('email')),
            'password' => Hash::make(str_random()),
        ]);
        
        // assign User Roles
        $user->guard_name = 'web';
        $user->assignRole($request->get('roles'));
        
        // send out User invite
        // we need the current hostname to get this to ensure that the link in the email is correct
        $hostname  = app(Environment::class)->hostname();
        $user->notify(new UserInvite($hostname));

        return redirect()->route('users.index')->with('success', $user->name . ' has been created successfully.');
    }

    /**
     * Display the specified User.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::pluck('display_name', 'id')->toArray()
        ]);
    }

    /**
     * Updates the specified User in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // we'll need this for checking if the verification email needs to go out again
        $resend_verification = false;
        
        // setup rules and validate
        $rules = [
            'name' => 'required|alpha_spaces',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'roles' => 'required'
        ];
        $messages = [
            'name.required' => 'The User\'s Name is required',
            'name.alpha_spaces' => 'The User\'s Name must only contain letters and spaces',
            'email.required' => 'The User\'s Email is required',
            'email.email' => 'The User\'s Email is in the incorrect format. It must be in the format \'abc@example.com\'',
            'email.unique' => 'The Email address you have entered is already in use. Please enter another one',
            'roles.required' => 'You must select at least one Role for this User'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
            return redirect()->back()->with('error', 'There was an error with your form submission, please review below.')->withInput()->withErrors($validator);

        $data = [
            'name' => ucwords($request->get('name')),
            'email' => strtolower($request->get('email'))
        ];

        // if the email is changing then we need to null out the email_verified_at timestamp and re-send the verification email
        if($user->email != $data['email']){
            $data['email_verified_at'] = null;
            $resend_verification = true;
        }

        // update User account with data
        $user->update($data);

        // if true then resend the verification email
        if($resend_verification)
            $user->sendEmailVerificationNotification();
        
        // sync User Roles - this removes all Roles and then assigns the ones selected
        $user->guard_name = 'web';
        $user->syncRoles($request->get('roles'));

        return redirect()->route('users.index')->with('success', $user->name . ' has been updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $email = $user->email;

        if(auth()->user() == $user)
            return redirect()->route('users.index')->with('message', 'You cannot archive your own user account');

        $user->delete();

        return redirect()->route('users.index')->with('success', $email . ' has been archived');
    }

    /**
     * Resends the invite email to the specified User Account.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function re_invite(User $user){
        $hostname  = app(Environment::class)->hostname();
        $user->notify(new UserInvite($hostname));

        return redirect()->back()->with('message', $user->email.' has been re-invited to the system');
    }

    /**
     * Sends a password reset email to the specified User Account.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function password_reset(User $user){
        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        return redirect()->back()->with('message', 'A password reset email has been sent to '.$user->email);
    }

    /**
     * Display a listing of the Archived / Soft Deleted Users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function archive(Request $request)
    {
        $users = User::onlyTrashed()->whereNull('queued_for_deletion')->get();

        return view('admin.users.archive', [
            'users' => $users
        ]);
    }

    /**
     * Search route / method to populate select2 for delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = '%'.strtolower($request->search).'%';
        $users = User::select(DB::raw('id, CONCAT(name, \', \', email) as text'))
            ->where('name', 'like', $search)
            ->orWhere('email', 'like', $search)
            ->orderBy('name', 'ASC')
            ->paginate(10);
        
        return [
            'results' => $users->items(),
            'pagination' => [
                'more' => ($users->total() > 10 && $request->page < $users->lastPage()) ? true : false
            ]
        ];
    }

    /**
     * Un-archive / remove Soft Delete from the chosen User in storage.
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function restore($user_id){
        $user = User::where('id', $user_id)->withTrashed()->first();
        if($user == null)
            return redirect()->back()->with('message', 'That User cannot be found.');

        $user->restore();

        return redirect()->back()->with('success', $user->email.' was successfully restored.');
    }

    /**
     * Permanently delete the chosen User from storage.
     * Assigns a required new User to ownership of all the old Users records
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $user_id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $user_id){
        $validator = Validator::make($request->all(), ['new_user_id' => 'required'], []);
        if ($validator->fails())
            return redirect()->back()->with('error', 'When deleting an account please ensure you provide a new User for re-allocation of record ownership. Please try again.');
        
        $user = User::where('id', $user_id)->withTrashed()->first();
        if($user == null)
            return redirect()->back()->with('message', 'The chosen User cannot be found.');
        
        $email = $user->email;
        
        $new_user = User::where('id', $request->new_user_id)->first();
        if($new_user == null)
            return redirect()->back()->with('message', 'The new User specified cannot be found.');

        // using a builder event instead of elequent will stop the update showing in the Audit trail
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'queued_for_deletion' => Carbon::now()
            ]);

        // we're going to use a queuable event as there may be a lot of relationships that need the ownership changing
        event(new DeleteUser($user, $new_user));

        return redirect()->back()->with('success', $email.' was permanently deleted.');
    }
}
