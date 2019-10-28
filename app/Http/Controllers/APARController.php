<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use App\Invoice;
use App\InvoiceDetail;
use App\Stock;
use App\StockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class APARController extends Controller
{
    /*index*/
    public function index(){
        return view('ap-ar.index');
    }
    /*Account payable create*/
    public function ap_create(){
        $stock = Stock::orderBy('created_at','desc')->get();
        $sender = Account::whereIn('type',['income'])->get();
        $receiver= Account::whereIn('type',['account-payable'])->get();
        return view('ap-ar.ap-create',compact(['sender','receiver','stock']));
    }
    /*Account receivable create*/
    public function ar_create(){
        $invoice = Invoice::orderBy('created_at','desc')->get();
        $sender = Account::whereIn('type',['income'])->get();
        $receiver= Account::whereIn('type',['account-receivable'])->get();
        return view('ap-ar.ar-create',compact(['sender','receiver','invoice']));
    }
    /*Account payable store*/
    public function ap_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender'=>'required',
            'receiver'=>'required',
            'desc'=>'required',
            'stock_invoice'=>'required',
            'balance'=>'required',
            'memo'=>'required'
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
                'invoice_id'=>$input['stock_invoice'],
                'desc'=>$input['desc'],
                'balance'=>$input['balance'],
                'memo'=>$input['memo'],
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['balance'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>$input['stock_invoice'],
                'desc'=>$input['desc'],
                'balance'=>$input['balance'],
                'memo'=>$input['memo'],
                'is_receiver'=>true,
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
    /*Account receivable store*/
    public function ar_store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'sender'=>'required',
            'receiver'=>'required',
            'desc'=>'required',
            'invoice'=>'required',
            'balance'=>'required',
            'memo'=>'required'
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
                'invoice_id'=>$input['invoice'],
                'desc'=>$input['desc'],
                'balance'=>$input['balance'],
                'memo'=>$input['memo'],
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['balance'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>$input['invoice'],
                'desc'=>$input['desc'],
                'balance'=>$input['balance'],
                'memo'=>$input['memo'],
                'is_receiver'=>true,
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
    /*Account receivable list*/
    public function ar_list(){
        $account_receivable = Account::where('type','account-receivable')->get();
        $id = $account_receivable[0]->id;
        $account_payable_detail = AccountDetail::where('account_id',$id)->where('is_receiver',1)->get();
        return DataTables::of($account_payable_detail)
            ->addColumn('account',function ($account){
                return $account->account->desc;
            })
            ->addColumn('type',function ($type){
                return $type->account->type;
            })
            ->addColumn('invoice',function ($invoice){
                return '<a href="#" data-id="'.$invoice->invoice_id.'" data-toggle="modal" id="ar-invoice-show" data-target="#modal_action">បង្ហាញ</a>';
            })
            ->addColumn('action',function ($action){
                return $action->balance!=0?'<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="ar-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
												<a href="#" data-toggle="modal" data-target="#modal_action" id="ar-return-create" data-id="'.$action->id.'" class="dropdown-item text-success"><i class="icon-backward"></i> សង</a>
											</div>
										</div>':'';
            })
            ->rawColumns(['invoice','action'])
            ->make(true);
    }
    /*Account payable list*/
    public function ap_list(){
        $account_payable = Account::where('type','account-payable')->get();
        $id = $account_payable[0]->id;
        $account_payable_detail = AccountDetail::where('account_id',$id)->where('is_receiver',1)->where('status',1)->get();
        return DataTables::of($account_payable_detail)
            ->addColumn('account',function ($account){
                return $account->account->desc;
            })
            ->addColumn('invoice',function ($invoice){
                return '<a href="#" data-id="'.$invoice->invoice_id.'" data-toggle="modal" id="ap-invoice-show" data-target="#modal_action">បង្ហាញ</a>';
            })
            ->addColumn('type',function ($type){
                return $type->account->type;
            })
            ->addColumn('action',function ($action){
                return $action->balance!=0?'<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>

											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="ap-destroy" data-id="'.$action->id.'" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
												<a href="#" data-toggle="modal" data-target="#modal_action" id="ap-return-create" data-id="'.$action->id.'" class="dropdown-item text-success"><i class="icon-backward"></i> សង</a>
											</div>
										</div>':'';
            })
            ->rawColumns(['invoice','action'])
            ->make(true);
    }
    /*stock invoice show*/
    public function ap_invoice_show($id){
        $stock_detail = StockDetail::where('stock_id',$id)->get();
        return view('ap-ar.ap-invoice-show',compact('stock_detail'));
    }
    /*invoice show*/
    public function ar_invoice_show($id){
        $invoice_detail = InvoiceDetail::where('invoice_id',$id)->get();
        return view('ap-ar.ar-invoice-show',compact('invoice_detail'));
    }
    /*account payable destroy*/
    public function ap_destroy($id){
        $account_detail = AccountDetail::findorFail($id);
        $account_detail->status = false;
        $account_detail->save();
        /*account*/
        //sender
        $sender = Account::findOrFail($account_detail->account_id);
        $sender->balance -= $account_detail->balance;
        $sender->save();
        //sender detail
        AccountDetail::insert([
            'account_id'=>$sender->id,
            'invoice_id'=>0,
            'desc'=>'លុបប្រតិបត្តការ',
            'balance'=>$account_detail->balance,
            'memo'=>'លុបប្រតិបត្តការ',
            'is_sender'=>true,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        //receiver
        $receiver = Account::findOrFail(2);
        $receiver->balance += $account_detail->balance;
        $receiver->save();
        //receiver detail
        AccountDetail::insert([
            'account_id'=>$receiver->id,
            'invoice_id'=>0,
            'desc'=>'ទទួលពីការលុបប្រតិបត្តការ',
            'balance'=>$account_detail->balance,
            'memo'=>'ទទួលពីការលុបប្រតិបត្តការ',
            'is_receiver'=>true,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ]);
        /*end account*/
        if ($account_detail){
            return response()->json(['success'=>'deleted.']);
        }
        return response()->json(['error'=>'errors']);
    }
    /*account payable return create*/
    public function ap_return_create($id){
        $sender = Account::whereIn('type',['account-payable'])->get();
        $receiver= Account::whereIn('type',['income'])->get();
        $account_payable = AccountDetail::findOrFail($id);
        return view('ap-ar.ap-return',compact(['account_payable','sender','receiver']));
    }
    /*account receivable return create*/
    public function ar_return_create($id){
        $sender = Account::whereIn('type',['account-receivable'])->get();
        $receiver= Account::whereIn('type',['income'])->get();
        $account_receivable = AccountDetail::findOrFail($id);
        return view('ap-ar.ar-return',compact(['account_receivable','sender','receiver']));
    }
    /*account payable return store*/
    public function ap_return_store(Request $request,$id){
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
            $sender_detail = AccountDetail::findOrFail($id);
            $sender_detail->balance -=$input['balance'];
            $sender_detail->save();
            AccountDetail::insert([
                'account_id'=>$sender->id,
                'invoice_id'=>0,
                'desc'=>'សងបំណុល',
                'balance'=>$input['balance'],
                'memo'=>'សងបំណុល',
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['balance'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'ទទួលសំណង',
                'balance'=>$input['balance'],
                'memo'=>'ទទួលសំណង',
                'is_receiver'=>true,
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
    /*account receivable return store*/
    public function ar_return_store(Request $request,$id){
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
            $sender_detail = AccountDetail::findOrFail($id);
            $sender_detail->balance -=$input['balance'];
            $sender_detail->save();
            AccountDetail::insert([
                'account_id'=>$sender->id,
                'invoice_id'=>0,
                'desc'=>'សងបំណុល',
                'balance'=>$input['balance'],
                'memo'=>'សងបំណុល',
                'is_sender'=>true,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['balance'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'ទទួលសំណង',
                'balance'=>$input['balance'],
                'memo'=>'ទទួលសំណង',
                'is_receiver'=>true,
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
