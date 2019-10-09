<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function list()
    {
        $user = User::all();
        return DataTables::of($user)
            ->editColumn('picture',function ($picture){
                return '<img src="'.asset($picture->picture).'" width="35" height="35" class="rounded-circle">';
            })
            ->editColumn('status',function ($status){
                return view('user.status',compact('status'));
            })
            ->editColumn('email',function ($email){
                return '<a href="mailto:'.$email->email.'">'.$email->email.'</a>';
            })
            ->editColumn('created_at',function ($date){
                return Carbon::parse($date->created_at)->format('d, m-M, Y');
            })
            ->addColumn('action',function ($action){
                return '<div class="list-icons">
										<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
											<a href="#" id="user-edit" data-id="'.$action->id.'" data-toggle="modal" data-target="#modal_action" class="dropdown-item text-success"><i class="icon-database-edit2"></i> កែប្រែ</a>
												<a href="#" id="user-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
											</div>
										</div>
									</div>';
            })
            ->rawColumns(['picture','action','status','email'])
            ->make(true);
    }

    public function index()
    {
        return view('user.index');
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'gender'=>'required',
            'picture'=>'required',
            'role'=>'required',
            'status'=>'required',
            'email'=>'required',
            'password'=>'required'
        ]);
        $input['password'] = Hash::make($input['password']);
        if ($validator->passes()) {
            $newUser = User::create($input);
            if ($newUser){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function edit($id)
    {
        $userEdit = User::findOrFail($id);
        return view('user.edit',compact('userEdit'));
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name'=>'required',
            'gender'=>'required',
            'picture'=>'required',
            'role'=>'required',
            'status'=>'required',
            'email'=>'required'
        ]);
        if ($validator->passes()) {
            $userUpdate = User::findOrFail($id);
            if (Auth::user()->role=='super_admin'){
                if ($input['password']==null){
                    $input['password']=$userUpdate->password;
                }else{
                    $input['password'] = Hash::make($input['password']);
                }
                $update = $userUpdate->update($input);
                if ($update){
                    return response()->json(['success'=>'Updated record.']);
                }
            }else{
                return response()->json(['error'=>'You are not admin.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($id)
    {
        $userDelete = User::findOrFail($id);
        if ($userDelete->role=='super_admin'){
            return response()->json(['error'=>'you do not have permission to delete this user.']);
        }else{
            $userDelete->delete();
            if ($userDelete){
                return response()->json(['success'=>'deleted records.']);
            }
        }
    }
}
