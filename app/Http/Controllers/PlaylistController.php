<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\PlaylistDetail;
use App\StockDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaylistController extends Controller
{
    /*search stock product*/
    public function search_stock(Request $request)
    {
        $inputTerm = $request->_term;
        StockDetail::get_search($inputTerm);
        $results = StockDetail::with('product_stock_search')
            ->whereHas('product_stock_search')
            ->where('status','<>',0)
            ->where('stock_qty', '>=', 1)
            ->get();
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result['id'],
                'text' => $result['product_stock_search']['desc'].' ស្តុក '.$result['stock_qty'].' តម្លៃលក់ '.$result['sale_value'],
            ];
        }
        return response()->json(['results' => $data]);
    }
    public function search_stock_product(Request $request)
    {
        $inputTerm = $request->_term;
        StockDetail::get_search($inputTerm);
        $results = StockDetail::with('product_stock_search')
            ->whereHas('product_stock_search')
            ->where('status','<>',0)
            ->orderBy('stock_qty','asc')
            ->get();
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result['product_stock_search']['id'],
                'text' => $result['product_stock_search']['desc'].' ស្តុក '.$result['stock_qty'].' តម្លៃលក់ '.$result['sale_value'],
            ];
        }
        return response()->json(['results' => $data]);
    }
    /*Create*/
    public function create(){
        return view('playlist.create');
    }
    /*store*/
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'desc' => 'required',
            'qty' => 'required',
            'purchase' => 'required',
            'sale' => 'required',
            'validate' => 'required',
            'product.*.*' => 'required'
        ]);
        if ($validator->passes()) {
            $playlist = new Playlist();
            $playlist->desc = $input['desc'];
            $playlist->qty = $input['qty'];
            $playlist->purchase = $input['purchase'];
            $playlist->sale = $input['sale'];
            $playlist->save();
            if ($playlist){
                $playlist_data = [];
                foreach ($input['product'] as $value){
                    $playlist_data []=[
                        'playlist_id'=>$playlist->id,
                        'stock_detail_id'=>$value['id'],
                        'unit_per_group'=>$value['per_group'],
                        'qty'=>$value['qty'],
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now()
                    ];
                }
                $playlist_detail = PlaylistDetail::insert($playlist_data);
                if ($playlist_detail){
                    return response()->json(['success' => 'Added new records.']);
                }
            }
        }
        return response()->json(['error' => $validator->errors()->all()]);
    }
    /*create item*/
    public function item_create($id){
        $stock_detail =  StockDetail::findOrFail($id);
        return view('playlist.item-create',compact('stock_detail'));
    }
}
