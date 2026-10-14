<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\RoleElement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleElementController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $role = RoleElement::create($request -> all());
        $role_name = $role -> name;
        $crud = ['create', 'read', 'update', 'delete'];
        foreach ($crud as $value)
        {
            $permision_name = $value . '-' . $role_name ;
            Permission::firstOrCreate(['name' => $permision_name],[
                'name' => $permision_name,
                'display_name' => $this -> split_word($permision_name),
                'description' => $this -> split_word($permision_name),
            ]);
        }
        return response()->json('success', 200);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function load_elements(Request $request)
    {
        if ($request -> ajax())
        {
            $elements_list = RoleElement::all();
            return response() -> json(['elements_list' => $elements_list], 200);
        }
    }


    public function delete_element(Request $request)
    {
        if ($request -> ajax())
        {
            $role_id = $request -> role_id ;
            $role = RoleElement::findOrFail($role_id);
            $role  -> delete();
            $permissions_map = ['create', 'read', 'update', 'delete'];
            foreach ($permissions_map as $permission)
            {
                $permission_name = $permission .'-'. $role -> name;
                $permission_target = Permission::where('name', $permission_name)->first();
                if ($permission_target)
                {
                    $permission_target -> delete();
                }
            }
            return response() -> json('success', 200);
        }
    }

    public function split_word($value)
    {
        $replace_dash = str_replace('-', ' ', $value);
        $string = ucwords($replace_dash);
        return $string ;
    }
}
