<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use App\User;
//using Facades
use App\Repositories\Facades\UserRepository;
//using dependency injection
//use App\Repositories\Contracts\UserInterface;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    /**
     * Using dependency injection
     */
    // protected $userRepository;
    //
    // public function __construct(UserInterface $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $user = UserRepository::all();

        return view('admin.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::get()->pluck('name', 'name');
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required',
          'password' => 'required|min:6',
          'roles' => 'required',
          'email' => 'required|email'
        ]);
        $requestData = $request->except('roles');
        $roles = $request->roles;
        $user = UserRepository::create($requestData);

        $user->assignRole($roles); // assign Role for user
        return redirect('admin/admin/user')->with('flash_message', 'User added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = UserRepository::findOrFail($id);
        $roles = $user->roles->pluck('name')->toArray();

        $permissions = UserRepository::getAllPermission($id);
        return view('admin.user.show', compact('user', 'roles', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = UserRepository::findOrFail($id);
        $roles = Role::get()->pluck('name', 'name');

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $request->validate([
          'name' => 'required',
          'email' => 'required|email',
          'roles' => 'required'
        ]);
        $requestData = $request->except('roles');
        $user = UserRepository::update($id, $requestData);


        $user->syncRoles($request->roles);
        return redirect('admin/admin/user')->with('flash_message', 'User updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        UserRepository::destroy($id);

        return redirect('admin/admin/user')->with('flash_message', 'User deleted!');
    }
}
