<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    /*list*/
    public function list(){
        $account = Account::all();
        return DataTables::of($account)
            ->addColumn('action',function ($action){
                return $action->type=='ap'?'<a href="#" id="account-pay" data-id="'.$action->id.'" data-toggle="modal" data-target="#modal_action" class="text-success"><i class="icon-chevron-right"></i> សង</a>':'';
            })
            ->addColumn('history',function ($history){
                return '<a href="#" data-toggle="modal" id="history-show" data-target="#modal_action" data-id="'.$history->id.'">បង្ហាញ</i></a>';
            })
            ->rawColumns(['action','history'])
            ->make(true);
    }
    public function show_history_data(Request $request,$id)
    {
        $input = $request->all();
        $account_detail_send_receive = Account::findOrFail($id)->account_detail->whereBetween('created_at', [$input['range']['start'], $input['range']['end']]);
        $totalSend = 0;
        $totalReceive = 0;
        foreach ($account_detail_send_receive as $value) {
            if ($value['is_sender'] == 1) {
                $totalSend += $value['balance'];
            }
            if ($value['is_receiver'] == 1) {
                $totalReceive += $value['balance'];
            }
        }
        return response()->json(['totalSend'=>$totalSend,'totalReceive'=>$totalReceive]);
    }
    /*show*/
    public function show(Request $request,$id){
        $input = $request->all();
        $account_detail = Account::findOrFail($id)->account_detail->whereBetween('created_at',[$input['range']['start'],$input['range']['end']]);
        return DataTables::of($account_detail)
            ->addColumn('action',function ($action){
                return '<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="account-edit" data-id="'.$action->id.'" data-toggle="modal" data-target="#modal_action" class="dropdown-item text-success"><i class="icon-database-edit2"></i> កែប្រែ</a>
											</div>
										</div>';
            })
            ->addColumn('history',function ($history){
                return '<a href="#" data-toggle="modal" id="history-show" data-target="#modal_action" data-id="'.$history->id.'">បង្ហាញ</i></a>';
            })
            ->addColumn('DT_RowClass',function ($row_class){
                return $row_class->is_receiver==1?'bg-info':'bg-warning';
            })
            ->addColumn('balance',function ($balance){
                return $balance->is_receiver==1?'+'.$balance->balance:'-'.$balance->balance;
            })
            ->rawColumns(['action','history'])
            ->make(true);
    }
    /*show history*/
    public function show_history(){
        return view('account.history-show');
    }
    /*index*/
    public function index(){
        return view('account.index');
    }
    /*create*/
    public function create(){
        return view('account.create');
    }
    /*deposit_create*/
    public function deposit_create(){
        $receiver = Account::whereIn('type',['owner-equity','ap'])->get();
        return view('account.deposit-create',compact('receiver'));
    }
    /*store*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'desc'=>'required',
            'type'=>'required',
            'balance'=>'required'
        ]);
        if ($validator->passes()) {
            if (Account::create($input)){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*store*/
    public function deposit_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'desc'=>'required',
            'receiver'=>'required',
            'balance'=>'required'
        ]);
        if ($validator->passes()) {
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['balance'];
            $receiver->save();
            /*if account payable*/
            if ($receiver->type=='ap'){
                $_receiver = Account::findOrFail(7);
                $_receiver->balance += $input['balance'];
                $_receiver->save();
                //receiver detail
                AccountDetail::insert([
                    'account_id'=>$_receiver->id,
                    'invoice_id'=>0,
                    'desc'=>'ដាក់លុយចូលគណនីកម្ចីប្រើប្រាស់',
                    'memo'=>'ដាក់លុយចូលគណនីកម្ចីប្រើប្រាស់',
                    'is_receiver'=>true,
                    'balance'=>$input['balance'],
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
            }
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'ដាក់លុយចូលគណនី',
                'memo'=>'ដាក់លុយចូលគណនី',
                'is_receiver'=>true,
                'balance'=>$input['balance'],
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            /*end account*/
            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*edit*/
    public function edit($id){
        $account = Account::findOrFail($id);
        return view('account.edit',compact('account'));
    }
    /*update*/
    public function update(Request $request,$id){
        $input = $request->all();
        $validator = Validator::make($input, [
            'desc'=>'required'
        ]);
        if ($validator->passes()) {
            $account = Account::findOrFail($id);
            if ($account->update($input)){
                return response()->json(['success'=>'updated records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*dashboard data*/
    public function dashboard_data(){
        $account = Account::whereIn('type',['income','owner-equity'])->sum('balance');
        return response()->json(['retain_earning'=>$account,'product_revenue'=>$this->product_revenue()]);
    }
    public function product_revenue(){
        $account = Account::findOrFail(2);
        return $revenue = $account->balance-$account->other_balance;
    }
    /*end dashboard data*/
    /*return create*/
    public function return_create(Request $request){
        $input = $request->all();
        $sender = Account::whereIn('type',['income','owner-equity'])->get();
        $receiver= Account::whereIn('type',['ap'])->get();
        $account_payable = Account::findOrFail($input['id']);
        return view('account.return-create',compact(['sender','receiver','account_payable']));
    }
    /*account payable return store*/
    public function ap_return_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender'=>'required',
            'receiver'=>'required',
            'balance'=>'required'
        ]);
        if ($validator->passes()) {
            /*account*/
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $input['balance'];
            $sender->save();
            //sender detail
            AccountDetail::insert([
                'account_id'=>$sender->id,
                'invoice_id'=>0,
                'desc'=>'សងបំណុលកម្ចីប្រើប្រាស់',
                'balance'=>$input['balance'],
                'memo'=>'សងបំណុលកម្ចីប្រើប្រាស់',
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance -= $input['balance'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'សងកម្ចីប្រើប្រាស់',
                'balance'=>$input['balance'],
                'memo'=>'សងកម្ចីប្រើប្រាស់',
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            /*end account*/
            if (true){
                return response()->json(['success'=>'Added new records.']);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
