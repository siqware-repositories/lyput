<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use App\Product;
use App\Stock;
use App\StockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ExcelImportController extends Controller
{
    /*index*/
    public function index(){
        return view('excel-import.index');
    }
    /*store*/
    public function store(Request $request){
        $path = $request->file('import_file')->getRealPath();
        $collections = (new FastExcel)->import($path);
        /*stock*/
        $totalQty = 0;
        $totalPur = 0;
        $totalSell = 0;
        $stock_data = [];
        foreach ($collections as $key=>$val) {
            if ($key!==$collections->count()-1){
                /*stock value*/
                $totalQty += $val['qty'];
                $totalPur += $val['qty'] * $val['pur_price'];
                $totalSell += $val['qty'] * $val['sale_price'];
            }
        }
        $stock = Stock::create([
            'pur_value' => $totalPur,
            'sale_value' => $totalSell,
            'qty' => $totalQty
        ]);
        foreach ($collections as $key=>$val) {
            if ($key!==$collections->count()-1) {
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
                    'sale_value' => $val['sale_price'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
        }
        /*account*/
        //sender
        $sender = Account::findOrFail(7);
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
        $receiver = Account::findOrFail(8);
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
        return response()->json(['error' => 'Error add new records.']);
    }
}
