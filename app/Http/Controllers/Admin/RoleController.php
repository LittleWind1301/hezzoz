<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\Permission;


class RoleController extends Controller
{
    private $role;
    private $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index()
    {
        $permissionsParent = $this->permission->where('parent_id', 0)->get();
        return view('admin.role.index', ['permissionsParent'=>$permissionsParent]);
    }

    public function fetchAll(){

        $roles = $this->role::all();
        $output = '';
        if($roles->count()>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Chức Vụ</th>
                                    <th>Mô Tả</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($roles as $item){
                    $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->name.'</td>
                                    <td>'.$item->display_name.'</td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editClassModal">
                                        <i class="bi-pencil-square h4"></i></a>
                                    </td>
                                </tr>';
                }
                $output .='</tbody></table>';
                echo $output;
        }else{
            echo '<h1 class="text-center text-secondary my-5">Không có bản ghi</h1>';
        }
    }

    public function store(Request $request){

        $data = [
            'name'=> $request->name,
            'display_name' => $request->display_name,
        ];

        $createRole = $this->role->create($data);

        $createRole->permissions()->attach($request->permission_id);

        return response()->json([
            'status'=>200
        ]);

    }

    public function edit(Request $request){

        $roleById = $this->role->find($request->id);
        $permissionsChecked = $roleById->permissions;

        return response()->json([
            'roleById'=>$roleById,
            'permissionsChecked'=>$permissionsChecked
        ]);
    }
    public function update(Request $request){
        
        $roleById = $this->role->find($request->role_id);

        $roleById->update([
            'name'=>$request->name,
            'display_name'=>$request->display_name
        ]);

        $roleById->permissions()->sync($request->permission_id);

        return response()->json([
            'status'=>200
        ]); 
    }
}
