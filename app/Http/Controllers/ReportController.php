<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceDetail;
use App\Stock;
use App\StockDetail;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function index(){
        return view('report.index');
    }
    public function stock_inv(){
        $stock = Stock::all();
        return DataTables::of($stock)
            ->addColumn('invoice',function ($invoice){
                return '<a href="#" data-id="'.$invoice->id.'" data-toggle="modal" id="report-stock-invoice-show" data-target="#modal_action">បង្ហាញ</a>';
            })
            ->rawColumns(['invoice'])
            ->make(true);
    }
    public function report_inv(){
        $stock = Invoice::all();
        return DataTables::of($stock)
            ->addColumn('invoice',function ($invoice){
                return '<a href="#" data-id="'.$invoice->id.'" data-toggle="modal" id="report-invoice-show" data-target="#modal_action">បង្ហាញ</a>';
            })
            ->rawColumns(['invoice'])
            ->make(true);
    }
    /*stock invoice show*/
    public function stock_invoice_show($id){
        $stock_detail = StockDetail::where('stock_id',$id)->get();
        return view('report.report-stock-show',compact('stock_detail'));
    }
    /*stock invoice show*/
    public function report_invoice_show($id){
        $invoice_detail = InvoiceDetail::where('invoice_id',$id)->get();
        return view('report.report-invoice-show',compact('invoice_detail'));
    }

}
