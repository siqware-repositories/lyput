<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountDetail;
use App\Invoice;
use App\InvoiceDetail;
use App\Playlist;
use App\StockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class InvoiceController extends Controller
{
    /*list*/
    public function list()
    {
        $stock_product = StockDetail::with('product')
            ->where('status',1)
            ->where('stock_qty','>',0)
            ->where('is_group',0)
            ->get();
        return DataTables::of($stock_product)
            ->addColumn('DT_RowId',function ($row_id){
                return $row_id->id;
            })
            ->make(true);
    }
    /*list​ group*/
    public function list_group()
    {
        $stock_product = StockDetail::with('product')
            ->where('status',1)
            ->where('stock_qty','>',0)
            ->where('is_group',1)
            ->get();
        return DataTables::of($stock_product)
            ->addColumn('DT_RowId',function ($row_id){
                return $row_id->id;
            })
            ->make(true);
    }
    /*index*/
    public function index(){
        $sender= Account::whereIn('type',['assets'])->get();
        $receiver= Account::whereIn('type',['income'])->get();
        return view('invoice.index',compact(['sender','receiver']));
    }
    /*get item data*/
    public function item($id){
        $stock = StockDetail::findOrFail($id);
        return view('invoice.item',compact('stock'));
    }
    /*get item data group*/
    public function item_playlist($id){
        $playlist = Playlist::findOrFail($id);
        return view('invoice.item-playlist',compact('playlist'));
    }
    /*store*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'invoice_validate'=>'required'
        ]);
        if ($validator->passes()) {
            /*playlist*/
            if (isset($input['validate_playlist'])){
                $totalQty = 0;
                $totalSale = 0;
                $totalPur = 0;
                foreach ($input['playlist'] as $value){
                    $totalQty +=$value['qty'];
                    $totalSale +=$value['qty']*$value['sale'];
                    $totalPur +=$value['qty']*$value['purchase'];
                }
                $invoice = new Invoice();
                $invoice->amount = $totalSale;
                $invoice->qty = $totalQty;
                $invoice->save();
                /*account*/
                $stockTotalSale = 0;
                $stockTotalPur = 0;
                foreach ($input['playlist'] as $value){
                    $playlists = Playlist::findOrFail($value['id'])->playlist_detail;
                    foreach ($playlists as $playlist){
                        $stock_detail = StockDetail::findOrFail($playlist['stock_detail_id']);
                        $stockTotalSale+=$playlist['qty']*$stock_detail['sale_value'];
                        $stockTotalPur+=$playlist['qty']*$stock_detail['pur_value'];
                    }
                }
                //sender
                $sender = Account::findOrFail($input['sender']);
                $sender->balance -= $stockTotalPur;
                $sender->other_balance -= $stockTotalSale;
                $sender->save();
                //sender detail
                AccountDetail::insert([
                    'account_id'=>$sender->id,
                    'invoice_id'=>$invoice->id,
                    'desc'=>'លក់ទំនិញចេញដំុ',
                    'memo'=>'លក់ទំនិញចេញដំុ',
                    'is_sender'=>true,
                    'balance'=>$stockTotalSale,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                //receiver
                $receiver = Account::findOrFail($input['receiver']);
                $receiver->balance += $totalSale;
                $receiver->other_balance += $totalPur;
                $receiver->save();
                //receiver detail
                AccountDetail::insert([
                    'account_id'=>$receiver->id,
                    'invoice_id'=>$invoice->id,
                    'desc'=>'តម្លៃទំនិញដំុ',
                    'memo'=>'តម្លៃទំនិញដំុ',
                    'is_receiver'=>true,
                    'balance'=>$totalSale,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                /*end account*/
                /*invoice detail*/
                $invoice_data =[];
                foreach ($input['playlist'] as $value){
                    $invoice_data[]=[
                        'invoice_id'=>$invoice->id,
                        'stock_detail_id'=>$value['id'],
                        'amount'=>$value['sale'],
                        'qty'=>$value['qty'],
                        'is_salary'=>false,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }
                InvoiceDetail::insert($invoice_data);
                /*reduce stock*/
                foreach ($input['playlist'] as $value){
                    $playlists = Playlist::findOrFail($value['id'])->playlist_detail;
                    foreach ($playlists as $playlist){
                        $stock_detail = StockDetail::findOrFail($playlist['stock_detail_id']);
                        $stock_detail->stock_qty-=$playlist['qty'];
                        $stock_detail->save();
                    }
                }
            }
            /*general stock*/
            if (isset($input['stock'])){
                $totalQty = 0;
                $totalSale = 0;
                $totalPur = 0;
                foreach ($input['stock'] as $value){
                    $totalQty +=$value['qty'];
                    $totalSale +=$value['qty']*$value['sale'];
                    $totalPur +=$value['qty']*$value['purchase'];
                }
                $invoice = new Invoice();
                $invoice->amount = $totalSale;
                $invoice->qty = $totalQty;
                $invoice->save();
                /*account*/
                //sender
                $sender = Account::findOrFail($input['sender']);
                $sender->balance -= $totalPur;
                $sender->other_balance -= $totalSale;
                $sender->save();
                //sender detail
                AccountDetail::insert([
                    'account_id'=>$sender->id,
                    'invoice_id'=>$invoice->id,
                    'desc'=>'លក់ទំនិញចេញរាយ',
                    'memo'=>'លក់ទំនិញចេញរាយ',
                    'is_sender'=>true,
                    'balance'=>$totalPur,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                //receiver
                $receiver = Account::findOrFail($input['receiver']);
                $receiver->balance += $totalSale;
                $receiver->other_balance += $totalPur;
                $receiver->save();
                //receiver detail
                AccountDetail::insert([
                    'account_id'=>$receiver->id,
                    'invoice_id'=>$invoice->id,
                    'desc'=>'តម្លៃទំនិញរាយ',
                    'memo'=>'តម្លៃទំនិញរាយ',
                    'is_receiver'=>true,
                    'balance'=>$totalSale,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                /*end account*/
                /*invoice detail*/
                $invoice_data =[];
                foreach ($input['stock'] as $value){
                    $invoice_data[]=[
                        'invoice_id'=>$invoice->id,
                        'stock_detail_id'=>$value['id'],
                        'amount'=>$value['sale'],
                        'qty'=>$value['qty'],
                        'is_salary'=>false,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }
                InvoiceDetail::insert($invoice_data);
                /*reduce stock*/
                foreach ($input['stock'] as $value){
                    $stock_detail = StockDetail::findOrFail($value['id']);
                    $stock_detail->stock_qty-=$value['qty'];
                    $stock_detail->save();
                }
            }
            //receive service
            //receiver
            $receiver = Account::findOrFail(3);
            $receiver->balance += $input['service'];
            $receiver->save();
            //receiver detail
            AccountDetail::insert([
                'account_id'=>$receiver->id,
                'invoice_id'=>0,
                'desc'=>'តម្លៃថ្លៃឈ្នួល',
                'memo'=>'តម្លៃថ្លៃឈ្នួល',
                'is_receiver'=>true,
                'balance'=>$input['service'],
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            return response()->json(['success'=>'Added new records.']);
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
    /*selling report*/
    public function selling_report(Request $request){
        $input = $request->all();
        $invoice_details = InvoiceDetail::whereBetween('created_at', [$input['start'], $input['end']])->get();
        $totalPur = 0;
        $totalSale = 0;
        foreach ($invoice_details as $invoice_detail){
            $totalPur += $invoice_detail->stock_detail->pur_value*$invoice_detail->qty;
            $totalSale += $invoice_detail->amount*$invoice_detail->qty;
        }
        return response()->json(['totalSale'=>$totalSale,'totalPur'=>$totalPur,'remain'=>$totalSale-$totalPur]);
    }

}
