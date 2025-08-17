<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Role;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct() {
        if(! isLoggedIn()){
            return response()->json([
                'success' => false, 
                'message' => "You need to log in",
                'redirect' => '/web/login'
            ]);
        }
    }

    public function users()
    {
        header('Content-Type: application/json');

        $users = User::all();

        if ($users) {
            return response()->json([
                'success' => true, 
                'message' => "All users successfully fetched.", 
                'users'   => $users
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => "No Data Found"
        ]);

    }

    public function createUser()  {
        $roles = User::getRoles();

        
        return response()->json(['success' => false, 'message' => 'Invalid credentials'], 200);
    }

    public function students()
    {        
        return view('admin/students');
    }

    public function roles(){
        $roles = [];

        $roles = Role::all();

        //show($roles);

        return view('admin/roles', [
            'roles' => $roles
        ]);
    }

    public function createRole() {
        $roles = [];

        $roles = Role::all();

        show($roles);

        return view('admin/create_role', [
            'permissions' => $permisions
        ]);
    }

    public function permissions() {
        $permisions = [];

        return view('admin/permissions', [
            'permissions' => $permisions
        ]);
    }

    public function createPermission() {
        $permisions = [];

        return view('admin/create_permission', [
            'permissions' => $permisions
        ]);
    }

}
