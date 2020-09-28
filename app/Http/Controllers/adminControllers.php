<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Auth;

class adminControllers extends Controller
{
    //
    public function getproducts(){
        if(Auth::user()->positions_id == 1){
            $query = Product::get();
            if(sizeof($query) > 0){
                return response()->json([
                    'response'      => true,
                    'message'       => "Success",
                    'data'          => $query
                ], 200);
            }else{
                return response()->json([
                    'response'      => false,
                    'message'       => "Failed",
                    'data'          => []
                ], 200);
            }
        }
        return response()->json([
            'response'      => false,
            'message'       => "False",
            'data'          => []
        ], 200);
    }

    public function addproducts($productid, $companyid){
        if(Auth::user()->positions_id == 1){
            $data = explode(',',$productid);
            $doesproductexists = Product::whereIn('id', $data)->get();
            if($doesproductexists){
                // proceed to adding product to this company
                $countarray = count(explode(',',$productid));
                $updatethiscompany = Company::find($companyid)
                ->update([
                    'products_id'   => $data
                ]);
                if($updatethiscompany){
                    return response()->json([
                        'response'  => true,
                        'message'   => "Success",
                        'data'      => Company::find($companyid)->get()
                    ], 200);
                }
            }
            return response()->json([
                'response'  => false,
                'message'   => "Product Does not exists on our record"
            ], 200);
        }
    }
}
