<?php

namespace App\Http\Controllers\Admin;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\OrderStoreDetails;
use App\StoreDetails;
use Carbon\Carbon;
use Functions;
use Validator;
use Datatables;



class OrderStoreDetailsController extends Controller
{
    public function index()
    {
        $store = StoreDetails::all();
        $data = [
            'data' => $store
        ];
        return view('admin.statStoreOrder', $data);
    }

    public function statOrderstore(Request $request)
    {
        $sd = $request->start_date;
        $ed = $request->end_date;
        $sto = $request->store;
        $ssd = date($sd);
        $eed = date($ed);
        if ($sto == "all") {
            $ann = OrderStoreDetails::whereBetween('created_at', [$ssd, $eed])
                ->groupBy('store_id')
                ->select('storename', 'store_id', DB::raw('count(*) as total'))
                ->get();
            $count = count($ann);
            return response()->json(array(
                "status" => "success",
                "result" => $ann,
                "count" => null,
                "pro" => $sto

            ));
        } else {
            $productName=StoreDetails::find($sto);
            $ann = OrderStoreDetails::where('store_id', $sto)
                ->whereBetween('created_at', [$ssd, $eed])->get();
            $count = count($ann);
            return response()->json(array(
                "result" => $ann,
                "status" => "success",
                "count" => $count,
                "store" => $productName

            ));
        }
    }
   
}
