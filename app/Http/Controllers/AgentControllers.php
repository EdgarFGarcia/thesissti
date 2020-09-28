<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\AgentLookup;
use App\Models\Product;
use App\Models\Sale;
use DB;

class AgentControllers extends Controller
{
    //
    public function selectcompany(){
        $getcompanies = AgentLookup::where('users_id', Auth::user()->id)->get();
        if(sizeof($getcompanies) > 0){
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'data'          => $getcompanies
            ]. 200);
        }

        return response()->json([
            'response'      => false,
            'message'       => "Failed",
            'data'          => []
        ], 200);
    }

    public function selectthiscompany($id){
        $getproducts = Product::where('updated_by', $id)->get();
        if(sizeof($getproducts) > 0){
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'data'          => $getproducts
            ], 200);
        }

        return response()->json([
            'response'      => false,
            'message'       => "Failed",
            'data'          => []
        ], 200);
    }

    public function addsales($id, $companyid){
        $explodevalues = explode(',', $id);

        $randonvalues = rand(5, 15);

        $companyId = (int)$companyid;

        $tocount = count($explodevalues);

        $data = [];
        $now = date("Y-m-d H:i:s");
        for($i = 0; $i < $tocount; $i++){
            array_push($data, [
                'companies_id'      => $companyId,
                'users_id'          => Auth::user()->id,
                'products_id'       => $explodevalues[$i],
                'amount'            => $randonvalues,
                'created_at'        => $now,
                'updated_at'        => $now,
                'updated_by'        => Auth::user()->id
            ]);
        }
        // return $data;
        $insertsales = DB::connection('mysql')
        ->table('sales')
        ->insert($data);

        if($insertsales){
            return response()->json([
                'response'      => true,
                'message'       => "Success"
            ], 200);
        }

        return response()->json([
            'response'      => false,
            'message'       => "failed"
        ], 200);

    }

    public function getmysales($companyid){
        // mas mabilis ang inner join kesa sa left join more than 3 seconds na query result (mabagal yon)
        $getsalesrecord = Sale::where('users_id', Auth::user()->id)->where('companies_id', $companyid)->get();

        $data = [];

        foreach($getsalesrecord as $out){
            $data[] = [
                'company'       => DB::connection('mysql')
                                ->table('companies')
                                ->where('id', $out->companies_id)
                                ->get(),
                'product'       => DB::connection('mysql')
                                ->table('products')
                                ->where('id', $out->products_id)
                                ->get(),
                'agent'         => DB::connection('mysql')
                                ->table('users')
                                ->where('id', Auth::user()->id)
                                ->get()
            ];
        }

        if(sizeof($data) > 0){
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'data'          => $data
            ], 200);
        }

        return response()->json([
            'response'      => false,
            'message'       => "Empty query set",
            'data'          => []
        ], 200);

    }
}
