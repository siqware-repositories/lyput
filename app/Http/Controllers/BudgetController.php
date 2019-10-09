<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BudgetController extends Controller
{
    /*index*/
    public function index(){
        return view('budget.index');
    }
    /*create*/
    public function create(){
        $receiver= Account::whereIn('type',['income'])->get();
        return view('budget.create',compact('receiver'));
    }
    /*create expense*/
    public function expense_create(){
        $sender= Account::whereIn('type',['income'])->get();
        $receiver= Account::whereIn('type',['expense'])->get();
        return view('budget.expense-create',compact(['sender','receiver']));
    }
    /*store income*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'receiver'=>'required',
            'income.*.*'=>'required'
        ]);
        if ($validator->passes()) {
            /*total*/
            $amount = 0;
            foreach ($input['income'] as $value){
                $amount+=$value['balance'];
            }
            /*account*/
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $amount;
            $receiver->save();
            /*account detail data*/
            $acc_detail_data = [];
            foreach ($input['income'] as $value){
                $acc_detail_data[]=[
                    'account_id'=>$receiver->id,
                    'invoice_id'=>0,
                    'desc'=>$value['desc'],
                    'balance'=>$value['balance'],
                    'is_receiver'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            //receiver detail
            AccountDetail::insert($acc_detail_data);
            /*end account*/
            if (true){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*destroy income*/
    public function incomeDestroy(Request $request,$id){
        $input = $request->all();
        $validator = Validator::make($input, [
            '_token'=>'required'
        ]);
        if ($validator->passes()) {
            $income = AccountDetail::findOrFail($id);
            $income->status = 0;
            $income->save();
            /*account*/
            //receiver
            $receiver = Account::findOrFail($income->account_id);
            $receiver->balance -= $income->balance;
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'ប្រតិបត្តិការលុបចំណូល',
                'balance'=>$income->balance,
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            /*end account*/
            return response()->json(['success'=>'Deleted records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*destroy expense*/
    public function expenseDestroy(Request $request,$id){
        $input = $request->all();
        $validator = Validator::make($input, [
            '_token'=>'required'
        ]);
        if ($validator->passes()) {
            $expense = AccountDetail::findOrFail($id);
            $expense->status = 0;
            $expense->save();
            //sender
            $sender = Account::findOrFail($expense->account_id);
            $sender->balance -= $expense->balance;
            $sender->save();
            /*account detail data*/
            //sender detail
            AccountDetail::insert([
                'account_id'=>$sender->id,
                'invoice_id'=>0,
                'desc'=>'លុបចំណាយ',
                'balance'=>$expense->balance,
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            /*account*/
            //receiver
            $receiver = Account::findOrFail($expense->sender_id);
            $receiver->balance += $expense->balance;
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'sender_id'=>$sender->id,
                'desc'=>'ទទួលពីការលុបចំណាយ',
                'balance'=>$expense->balance,
                'is_receiver'=>true,
                'status'=>false,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            /*end account*/
            return response()->json(['success'=>'Deleted records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*expense income*/
    public function expense_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'receiver'=>'required',
            'sender'=>'required',
            'expense.*.*'=>'required'
        ]);
        if ($validator->passes()) {
            /*total*/
            $amount = 0;
            foreach ($input['expense'] as $value){
                $amount+=$value['balance'];
            }
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $amount;
            $sender->save();
            /*account detail data*/
            $acc_detail_data = [];
            foreach ($input['expense'] as $value){
                $acc_detail_data[]=[
                    'account_id'=>$sender->id,
                    'invoice_id'=>0,
                    'desc'=>$value['desc'],
                    'balance'=>$value['balance'],
                    'is_sender'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            //sender detail
            AccountDetail::insert($acc_detail_data);
            /*account*/
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $amount;
            $receiver->save();
            /*account detail data*/
            $acc_detail_data = [];
            foreach ($input['expense'] as $value){
                $acc_detail_data[]=[
                    'account_id'=>$receiver->id,
                    'invoice_id'=>0,
                    'sender_id'=>$sender->id,
                    'desc'=>$value['desc'],
                    'balance'=>$value['balance'],
                    'is_receiver'=>true,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            }
            //receiver detail
            AccountDetail::insert($acc_detail_data);
            /*end account*/
            if (true){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*Expense list*/
    public function expense_list(){
        $account = Account::whereIn('desc',['ចំណាយផ្សេងៗ'])->get();
        $first=$account[0]->id;
        $account_detail = AccountDetail::whereIn('account_id',[$first])->where('is_receiver',1)->where('status',1)->get();
        return DataTables::of($account_detail)
            ->addColumn('account',function ($account){
                return $account->account->desc;
            })
            ->addColumn('type',function ($type){
                return $type->account->type;
            })
            ->addColumn('action',function ($action){
                return '<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="expense-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
											</div>
										</div>';
            })
            ->make(true);
    }
    /*Income list*/
    public function income_list(){
        $account = Account::whereIn('desc',['ចំណូលលក់អេតចាយ','ចំណូលថ្លៃឈ្នួល'])->get();
        $first=$account[0]->id;
        $second=$account[1]->id;
        $account_detail = AccountDetail::whereIn('account_id',[$first,$second])->where('is_receiver',1)->where('status',1)->get();
        return DataTables::of($account_detail)
            ->addColumn('account',function ($account){
                return $account->account->desc;
            })
            ->addColumn('type',function ($type){
                return $type->account->type;
            })
            ->addColumn('action',function ($action){
                return '<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="income-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
											</div>
										</div>';
            })
            ->make(true);
    }
}
