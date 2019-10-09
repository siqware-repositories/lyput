<?php

namespace App\Http\Controllers;

use App\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountTypeController extends Controller
{
    /*Create*/
    public function create(){
        return view('account.create-type');
    }
    /*store*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'type'=>'required',
            'value'=>'required',
            'class'=>'required'
        ]);
        if ($validator->passes()) {
            if (AccountType::create($input)){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*search stock product*/
    public function search_select2(Request $request){
        $inputTerm = $request->_term;
        $results = AccountType::where('type','like',"%$inputTerm%")->get();
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result['value'],
                'text' => $result['type'],
            ];
        }
        return response()->json(['results' => $data]);
    }
}
