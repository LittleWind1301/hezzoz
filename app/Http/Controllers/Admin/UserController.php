<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }



    public function index(){
        $roles = Role::all();
        return view('admin.user.index', ['roles' => $roles]);
    }

    public function fetchAll(){
        $users = $this->user::all()->where('utype', 'ADM');

        $output = '';
        if($users->count()>0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Họ Tên</th>
                                    <th>Email</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($users as $item){

                    $output .='<tr>
                                    <td>'.$item->id.'</td>
                                    <td>'.$item->name.'</td>
                                    <td>'.$item->email.'</td>
                                    <td>
                                        <a href="#" id="'.$item->id.'" class="text-success mx-1 editIcon"
                                        data-toggle="modal" data-target="#editUserModal">
                                            <i class="bi-pencil-square h4"></i>
                                        </a>

                                        <a href="javascript:;" id="" onclick="deleteItem('.$item->id.')" class="text-danger mx-1 deleteIcon">
                                            <i class="bi-trash h4"></i>
                                        </a>
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
        try{
            DB::beginTransaction();
            $data = [
                'email'         =>$request->user_email,
                'password'      =>bcrypt($request->user_pwd),
                'remember_token'=>' ',
                'status'        =>true,
                'utype'         =>'ADM'
            ];

            $createUser = $this->user->create($data);
            $createUser->roles()->attach($request->role_id);

            DB::commit();
            return response()->json([
                'status'=>200
            ]);

        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json([
                'status'=>0
            ]);
        }


    }

    public function edit(Request $request){
        $userById = $this->user::find($request->id);
        $roleOfUser = $userById->roles;

        return response()->json([
            'userById'=>$userById,
            'roleOfUser'=>$roleOfUser
        ]);
    }

    public function update(Request $request){

        $userById = $this->user::find($request->user_id);
        $data = [
            'name'=>$request->user_name,
            'email'=>$request->user_email,
        ];

        $userById->update($data);

        $userById->roles()->sync($request->role_id);

        return response()->json([
            'status'=>200
        ]);

    }

    public function delete(Request $request){
        try{
            $this->user::find($request->id)->delete();

            return response()->json([
                'code'=>200,
                'message'=>'success'
            ], 200);
        }catch(\Exception $ex){
            return response()->json([
                'code'=>500,
                'message'=>'fail'
            ], 500);
        }
    }
}
