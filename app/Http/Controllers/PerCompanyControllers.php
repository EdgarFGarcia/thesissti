<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Product;
use Validator;
use DB;

class PerCompanyControllers extends Controller
{
    //
    public function addproducts(Request $request, $companyId){
        // query db || orm
        if(Auth::user()->positions_id == 2){

            $checkifproductexists = Product::where('name', $request->name)->where('updated_by', Auth::user()->id)->whereNull('deleted_at')->get();

            if(sizeof($checkifproductexists) > 0){
                return response()->json([
                    'response'      => false,
                    'message'       => "The product name is already been inserted"
                ], 200);
            }

            $validation = Validator::make($request->all(), [
                'name'              => 'required|string',
            ]);
    
            if($validation->fails()){
                $error = $validation->messages()->first();
                return response()->json([
                    'response'  => false,
                    'message'   => $error
                ]);
            }

            $insertproductrecord = Product::create([
                'name'          => $request->name,
                'updated_by'    => Auth::user()->id
            ]);

            if($insertproductrecord){
                return response()->json([
                    'response'      => true,
                    'message'       => "Success"
                ], 200);
            }

            return response()->json([
                'response'      => false,
                'message'       => "Failed"
            ], 200);
        }
        
    }
}
