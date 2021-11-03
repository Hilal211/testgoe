<?php


namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Statistics;
use Carbon\Carbon;
use Functions;
use Validator;
use Datatables;

class StatisticsController extends Controller
{

    public function index()
    {
        $states = Statistics::all();
        $data = [
            'data' => $states
        ];
        return view('admin.statistics', $data);
    }
    public function create(Request $request)
    {
        $statistics = new Statistics();
        $statistics -> fill($request -> all());
        $statistics -> save();
    }
    public function getStatistics(Request $request)
    {
        $data = Statistics::leftJoin('users', 'statistics.user_id', '=', 'users.id');
        return Datatables::of($data)
         
            ->removeColumn('id')
            ->make();
    }

}

?>
