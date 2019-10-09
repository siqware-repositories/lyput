<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\StockDetail;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    /*search playlist*/
    public function search_playlist(Request $request)
    {
        $inputTerm = $request->_term;
        $results = Playlist::where('desc','like',"%$inputTerm%")->orderBy('created_at','desc')->get();
        $data = [];
        foreach ($results as $result) {
            $data[] = [
                'id' => $result['id'],
                'text' => $result['desc'].' ចំនួន : '.$result['qty'].' តម្លៃ : '.$result['sale']*$result['qty'],
            ];
        }
        return response()->json(['results' => $data]);
    }
}
