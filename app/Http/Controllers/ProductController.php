<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use App\Product;
use App\Stock;
use App\StockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /*list*/
    public function list()
    {
        $stock_product = StockDetail::with('product')->selectRaw('*,sum(stock_qty) as totalStock')->groupBy('product_id')->latest('created_at')->get();
        return DataTables::of($stock_product)
            ->addColumn('action', function ($action) {
                return $action->qty == $action->stock_qty ? '<div class="dropdown">
											<a href="#" class="list-icons-item" data-toggle="dropdown">
												<i class="icon-menu9"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-right">
												<a href="#" id="product-destroy" data-id="' . $action->id . '" class="dropdown-item text-warning"><i class="icon-database-remove"></i> លុប</a>
											</div>
										</div>' : '';
            })
            ->editColumn('product.desc',function ($product_desc){
                return '<a href="#" class="text-input" data-name="desc" data-type="text" data-pk="'.$product_desc->product->id.'" data-url="'.route('product._update').'" data-title="បញ្ចូលឈ្មោះ">'.$product_desc->product->desc.'</a>';
            })
            ->editColumn('stock_qty',function ($stock_qty){
                return $stock_qty->totalStock;
            })

            ->addColumn('DT_RowClass',function ($row_class){
                return $row_class->totalStock<=0?'bg-warning':'';
            })
            ->rawColumns(['action','product.desc'])
            ->make(true);
    }

    /*index*/
    public function index()
    {
        return view('product.index');
    }

    /*create single*/
    public function create()
    {
        $sender = Account::whereIn('type', ['income', 'owner-equity'])->get();
        $receiver = Account::whereIn('type', ['assets'])->get();
        return view('product.create', compact(['sender', 'receiver']));
    }
    /*create single*/
    public function stock_create()
    {
        $sender = Account::whereIn('type', ['income', 'owner-equity'])->get();
        $receiver = Account::whereIn('type', ['assets'])->get();
        return view('product.stock-create', compact(['sender', 'receiver']));
    }

    /*create multiple*/
    public function create_group()
    {
        $sender = Account::whereIn('type', ['income', 'owner-equity'])->get();
        $receiver = Account::whereIn('type', ['assets'])->get();
        return view('product.create-group', compact(['sender', 'receiver']));
    }
    /*store single*/
    /*store*/
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product.*.*' => 'required'
        ]);
        if ($validator->passes()) {
            $totalQty = 0;
            $totalPur = 0;
            $totalSell = 0;
            $stock_data = [];
            foreach ($input['product'] as $val) {
                /*stock value*/
                $totalQty += $val['qty'];
                $totalPur += $val['qty'] * $val['pur_price'];
                $totalSell += $val['qty'] * $val['sell_price'];
            }
            $stock = Stock::create([
                'pur_value' => $totalPur,
                'sale_value' => $totalSell,
                'qty' => $totalQty
            ]);
            foreach ($input['product'] as $val) {
                /*product value*/
                $product = new Product();
                $product->desc = $val['desc'];
                $product->save();
                $stock_data[] = [
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'qty' => $val['qty'],
                    'stock_qty' => $val['qty'],
                    'pur_value' => $val['pur_price'],
                    'sale_value' => $val['sell_price'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            /*account*/
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $totalPur;
            $sender->save();
            //sender detail
            AccountDetail::insert([
                'account_id' => $sender->id,
                'invoice_id' => $stock->id,
                'desc' => 'ទិញទំនិញ',
                'memo' => 'ទិញទំនិញ',
                'is_sender' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'balance' => $totalPur,
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $totalPur;
            $receiver->other_balance += $totalSell;
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id' => $receiver->id,
                'invoice_id' => $stock->id,
                'desc' => 'តម្លៃទំនិញ',
                'memo' => 'តម្លៃទំនិញ',
                'is_receiver' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'balance' => $totalPur,
            ]);
            /*end account*/
            if (StockDetail::insert($stock_data)) {
                return response()->json(['success' => 'Added new records.']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*store*/
    public function stock_store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product.*.*' => 'required'
        ]);
        if ($validator->passes()) {
            $totalQty = 0;
            $totalPur = 0;
            $totalSell = 0;
            $stock_data = [];
            foreach ($input['product'] as $val) {
                /*stock value*/
                $totalQty += $val['qty'];
                $totalPur += $val['qty'] * $val['pur_price'];
                $totalSell += $val['qty'] * $val['sell_price'];
            }
            $stock = Stock::create([
                'pur_value' => $totalPur,
                'sale_value' => $totalSell,
                'qty' => $totalQty
            ]);
            foreach ($input['product'] as $val) {
                /*product value*/
                $stock_data[] = [
                    'stock_id' => $stock->id,
                    'product_id' => $val['p_id'],
                    'qty' => $val['qty'],
                    'stock_qty' => $val['qty'],
                    'pur_value' => $val['pur_price'],
                    'sale_value' => $val['sell_price'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            /*account*/
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $totalPur;
            $sender->save();
            //sender detail
            AccountDetail::insert([
                'account_id' => $sender->id,
                'invoice_id' => $stock->id,
                'desc' => 'បន្ថែមស្តុក',
                'memo' => 'បន្ថែមស្តុក',
                'is_sender' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'balance' => $totalPur,
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $totalPur;
            $receiver->other_balance += $totalSell;
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id' => $receiver->id,
                'invoice_id' => $stock->id,
                'desc' => 'តម្លៃទំនិញបន្ថែមស្តុក',
                'memo' => 'តម្លៃទំនិញបន្ថែមស្តុក',
                'is_receiver' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'balance' => $totalPur,
            ]);
            /*end account*/
            if (StockDetail::insert($stock_data)) {
                return response()->json(['success' => 'Added new records.']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*store group*/
    public function store_group(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'product.*.*' => 'required',
            'desc' => 'required',
            'qty' => 'required',
            'pur_price' => 'required',
            'sell_price' => 'required'
        ]);
        if ($validator->passes()) {
            $stock_data = [];
            $stock = Stock::create([
                'pur_value' => $input['pur_price'] * $input['qty'],
                'sale_value' => $input['sell_price'] * $input['qty'],
                'qty' => $input['qty']
            ]);
            /*account*/
            //sender
            $sender = Account::findOrFail($input['sender']);
            $sender->balance -= $input['pur_price'] * $input['qty'];
            $sender->save();
            //sender detail
            AccountDetail::create([
                'account_id' => $sender->id,
                'invoice_id' => $stock->id,
                'desc' => 'ទិញទំនិញដំុ',
                'balance' => $input['pur_price'] * $input['qty']
            ]);
            //receiver
            $receiver = Account::findOrFail($input['receiver']);
            $receiver->balance += $input['sell_price'] * $input['qty'];
            $receiver->other_balance += $input['pur_price'] * $input['qty'];
            $receiver->save();
            //receiver detail
            AccountDetail::create([
                'account_id' => $receiver->id,
                'invoice_id' => $stock->id,
                'desc' => 'តម្លៃទំនិញដំុ',
                'balance' => $input['pur_price'] * $input['qty']
            ]);
            /*end account*/
            /*product value*/
            $product = new Product();
            $product->desc = $input['desc'];
            $product->save();
            /*main stock detail*/
            $stockDetail = StockDetail::create([
                'stock_id' => $stock->id,
                'product_id' => $product->id,
                'qty' => $input['qty'],
                'stock_qty' => $input['qty'],
                'pur_value' => $input['pur_price'],
                'sale_value' => $input['sell_price'],
                'is_group' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            foreach ($input['product'] as $val) {
                /*product value*/
                $product = new Product();
                $product->desc = $val['desc'];
                $product->save();
                $stock_data[] = [
                    'stock_id' => $stock->id,
                    'product_id' => $product->id,
                    'qty' => $val['qty'] * $input['qty'],
                    'stock_qty' => $val['qty'] * $input['qty'],
                    'pur_value' => $val['pur_price'],
                    'sale_value' => $val['sell_price'],
                    'is_group' => false,
                    'group_of' => $stockDetail->id,
                    'group_qty' => $val['qty'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            if (StockDetail::insert($stock_data)) {
                return response()->json(['success' => 'Added new records.']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*edit*/
    public function edit($id)
    {
        $stock = StockDetail::findOrFail($id);
        return view('product.edit', compact('stock'));
    }
    /*update*/
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'desc' => 'required',
            'qty' => 'required',
            'pur_price' => 'required',
            'sell_price' => 'required'
        ]);
        if ($validator->passes()) {
            $product = Product::findorFail(StockDetail::findOrFail($id)->product_id);
            $product->desc = $input['desc'];
            $product->save();
            $stock = StockDetail::findOrFail($id);
            if ($stock->qty === $stock->stock_qty) {
                $stock->qty = $input['qty'];
                $stock->pur_value = $input['pur_price'];
                $stock->sale_value = $input['sell_price'];
                $stock->stock_qty = $input['qty'];
                $stock->save();
                $stock = true;
            }
            if ($stock) {
                return response()->json(['success' => 'Added new records.']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*updateProduct*/
    public function updateProduct(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'value' => 'required'
        ]);
        if ($validator->passes()) {
            $product = Product::findorFail($request->pk)->update([$request->name=>$request->value]);
            if ($product){
                return response()->json(['success' => 'Added new records.']);
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*destroy*/
    public function destroy(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            '_token' => 'required',
        ]);
        if ($validator->passes()) {
            $stock = StockDetail::findOrFail($id);
            if ($stock->is_group == 1) {
                $group_product = StockDetail::where('group_of', $stock->id)->get();
                foreach ($group_product as $value) {
                    $stock_group = StockDetail::findOrFail($value->id);
                    $stock_group->status = 0;
                    $stock_group->save();
                }
                $stock->status = 0;
                $stock->save();
                /*sender receiver*/
                $sender = Account::whereIn('type', ['assets'])->get();
                $receiver = Account::whereIn('type', ['owner-equity'])->get();
                $sender_id = $sender[0]->id;
                $receiver_id = $receiver[0]->id;
                /*account*/
                //sender
                $_sender = Account::findOrFail($sender_id);
                $_sender->balance -= $stock->pur_value*$stock->qty;
                $_sender->other_balance -= $stock->sale_value*$stock->qty;
                $_sender->save();
                //sender detail
                AccountDetail::insert([
                    'account_id' => $_sender->id,
                    'invoice_id' => 0,
                    'desc' => 'លុបទំនិញទិញ',
                    'memo' => 'លុបទំនិញទិញ',
                    'balance' => $stock->pur_value*$stock->qty,
                    'is_sender' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                //receiver
                $_receiver = Account::findOrFail($receiver_id);
                $_receiver->balance += $stock->pur_value*$stock->qty;
                $_receiver->save();
                //receiver detail
                AccountDetail::insert([
                    'account_id' => $_receiver->id,
                    'invoice_id' => 0,
                    'desc' => 'ទទួលពីការលុបទំនិញទិញ',
                    'memo' => 'ទទួលពីការលុបទំនិញទិញ',
                    'is_receiver' => true,
                    'balance' => $stock->pur_value*$stock->qty,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                /*end account*/
                return response()->json(['success' => 'record deleted.']);
            }
            if ($stock->group_of > 0) {
                $stock_group = StockDetail::findOrFail($stock->group_of);
                $stock_group->status = 0;
                $stock_group->save();
                /*relative delete*/
                $group_product = StockDetail::where('group_of', $stock_group->id)->get();
                foreach ($group_product as $value) {
                    $stock_group = StockDetail::findOrFail($value->id);
                    $stock_group->status = 0;
                    $stock_group->save();
                }
                /*sender receiver*/
                $sender = Account::whereIn('type', ['assets'])->get();
                $receiver = Account::whereIn('type', ['owner-equity'])->get();
                $sender_id = $sender[0]->id;
                $receiver_id = $receiver[0]->id;
                /*account*/
                //sender
                $_sender = Account::findOrFail($sender_id);
                $_sender->balance -= $stock_group->pur_value*$stock_group->qty;
                $_sender->other_balance -= $stock_group->sale_value*$stock_group->qty;
                $_sender->save();
                //sender detail
                AccountDetail::insert([
                    'account_id' => $_sender->id,
                    'invoice_id' => 0,
                    'desc' => 'លុបទំនិញទិញ',
                    'memo' => 'លុបទំនិញទិញ',
                    'balance' => $stock_group->pur_value*$stock_group->qty,
                    'is_sender' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                //receiver
                $_receiver = Account::findOrFail($receiver_id);
                $_receiver->balance += $stock_group->pur_value*$stock_group->qty;
                $_receiver->save();
                //receiver detail
                AccountDetail::insert([
                    'account_id' => $_receiver->id,
                    'invoice_id' => 0,
                    'desc' => 'ទទួលពីការលុបទំនិញទិញ',
                    'memo' => 'ទទួលពីការលុបទំនិញទិញ',
                    'is_receiver' => true,
                    'balance' => $stock_group->pur_value*$stock_group->qty,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                /*end account*/
                return response()->json(['success' => 'record deleted.']);
            }
            $stock->status = 0;
            $stock->save();
            /*sender receiver*/
            $sender = Account::whereIn('type', ['assets'])->get();
            $receiver = Account::whereIn('type', ['owner-equity'])->get();
            $sender_id = $sender[0]->id;
            $receiver_id = $receiver[0]->id;
            /*account*/
            //sender
            $_sender = Account::findOrFail($sender_id);
            $_sender->balance -= $stock->pur_value*$stock->qty;
            $_sender->other_balance -= $stock->sale_value*$stock->qty;
            $_sender->save();
            //sender detail
            AccountDetail::insert([
                'account_id' => $_sender->id,
                'invoice_id' => 0,
                'desc' => 'លុបទំនិញទិញ',
                'memo' => 'លុបទំនិញទិញ',
                'balance' => $stock->pur_value*$stock->qty,
                'is_sender' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            //receiver
            $_receiver = Account::findOrFail($receiver_id);
            $_receiver->balance += $stock->pur_value*$stock->qty;
            $_receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id' => $_receiver->id,
                'invoice_id' => 0,
                'desc' => 'ទទួលពីការលុបទំនិញទិញ',
                'memo' => 'ទទួលពីការលុបទំនិញទិញ',
                'is_receiver' => true,
                'balance' => $stock->pur_value*$stock->qty,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            /*end account*/
            return response()->json(['success' => 'record deleted.']);
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    public function check_out_stock(){
        return StockDetail::with('product')
            ->selectRaw('*,sum(stock_qty) as totalStock')
            ->groupBy('product_id')
            ->havingRaw('sum(stock_qty)<=?',[0])
            ->count();
    }
}
