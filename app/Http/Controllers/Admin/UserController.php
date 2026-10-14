<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDatatable;
use App\Http\Requests\user\AddUserRequest;
use App\Http\Requests\user\UpdateUserRequest;
use App\Models\Branch;
use App\Models\JobTitle;
use App\Models\Role;
use App\Models\User;
use App\Traits\HelperTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use HelperTrait;

    public function __construct()
    {
        $this->middleware('permission:read-users')->only('index');
        $this->middleware('permission:create-users')->only('create');
        $this->middleware('permission:update-users')->only('edit');
        $this->middleware('permission:delete-users')->only('destroy');
    }

    public function index(UserDatatable $userDatatable)
    {
//        dd(Auth::user() ->roles->toArray());
        $branchName = '';
        if (request('branch_id')) {
            $branchName = Branch::findOrFail(request('branch_id'))->name; // Get [ branch name ] to send into view
        }
        return $userDatatable -> render ('admin.users.index', compact('branchName'));
    }

    public function create()
    {
        $branches = Branch::pluck('name', 'id')->toArray();

        $roles = Role::where(function ($query){
            if (Auth::user()->hasRole(['owner', 'general_manager']))
            {
                $query -> whereNotIn('name', ['super_owner', 'owner']);
            }
        })->pluck('name', 'id')->toArray();

        $jobTitle = JobTitle::where(function ($query){
            if (Auth::user()->hasRole(['owner', 'general_manager']))
            {
                $query -> whereNotIn('name', ['Super Owner', 'Owner']);
            }
        })->pluck('name', 'id')->toArray();

//        User::where('role_id', 1)->exists()?
//            $roles = Role::where('display_name', '!=', 'owner')->pluck('display_name', 'id')->toArray():
//            $roles = Role::pluck('display_name', 'id')->toArray();
//
//        User::where('role_id', 1)->exists()?
//            $jobTitle = JobTitle::where('name', '!=', 'owner')->pluck('name', 'id')->toArray():
//            $jobTitle = JobTitle::pluck('name', 'id')->toArray();
        return view('admin.users.create', compact('roles', 'branches', 'jobTitle'));
    }

    public function store(AddUserRequest $request)
    {
        $user_data = $request -> except('profile_picture', 'password'); // Get All Column Without [profile_picture]
        $user_data['password'] = Hash::make($request -> password);
        $image_data = $this->uploadImageProcessing($request -> profile_picture, 'user', 'profile', $request -> name, 'public', 100, 100); // Upload Image With Trait
        $user = User::create($user_data + $image_data); // Create New user From [user_data] Request And [image_data] Coming With Trait
        $user->attachRole($request -> role_id);
        return redirect() -> route('admin.users.index') -> with('success', __('trans.user added successfully'));
    }


    public function edit($id)
    {

        $user = User::findOrFail($id);
        $branches = Branch::pluck('name', 'id')->toArray();

        $roles = Role::where(function ($query){
            if (Auth::user()->hasRole(['owner', 'general_manager']))
            {
                $query -> where('name', '!=', 'super_owner');
            }
        })->pluck('name', 'id')->toArray();

        $jobTitle = JobTitle::where(function ($query){
            if (Auth::user()->hasRole(['owner', 'general_manager']))
            {
                $query -> where('name', '!=', 'Super Owner');
            }
        })->pluck('name', 'id')->toArray();

        return view('admin.users.edit',compact('user', 'roles', 'branches', 'jobTitle'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
//        dd($request->all());
        $user = User::findOrFail($id);
        $user_new_data = $request -> except('profile_picture', 'password', 'branch_id'); // Get All Column Without [profile_picture]
        if ($request -> password != NULL)
            $user_new_data['password'] = bcrypt($request -> password);

        $branch_id = $request -> branch_id ?? null;
        $image_data = $this->uploadImageProcessing($request -> profile_picture, 'user', 'profile', $request -> name, 'public', 100, 100, $user);
        $user->update($user_new_data + ['branch_id' => $branch_id] + $image_data); // Create New user From [userData] Request
        $user->syncRoles([$request -> role_id]);
        return redirect() -> back() -> with('success', __('trans.user edit successfully'));
//        return redirect() -> route('admin.users.index') -> with('success', __('trans.user edit successfully'));
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user -> image_name != 'default.png') { // Check If Profile Picture Name Not Equal Default Picture Name Do It
            $this -> deleteImageHandel('public', $user -> image_path); // Check If user Have Directory Profile Picture Delete It
        }
        $user -> delete(); // Delete user From user Table
        return redirect()->back()->with('delete', __('trans.user delete successfully'));
    }

}
