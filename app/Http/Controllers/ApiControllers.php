<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use Hash;
use DB;
use Validator;
use Auth;

class ApiControllers extends Controller
{
    //

    public function getUser(){
        $positionsId = Auth::user()->positions_id;
        switch($positionsId){
            case 1:
                return "super admin";
            case 2:
                return $this->getSalesOfAgents(Auth::user()->id);
            case 3:
                return $this->getMySales(Auth::user()->id);
            default:
                return "who you";
        }
    }

    public function registerUser(Request $request){
        // return $request->all();
        // difference ng orm and db query
        $validation = Validator::make($request->all(), [
            'fname'     => 'required|string',
            'lname'     => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string'
        ]);

        if($validation->fails()){
            $error = $validation->messages()->first();
            return response()->json([
                'response'  => false,
                'message'   => $error
            ]);
        }

        $insertUser = User::create([
            'fname'         => $request->fname,
            'lname'         => $request->lname,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'positions_id'  => 3
        ]);

        if($insertUser){
            return response()->json([
                'response'      => true,
                'message'       => "Success"
            ], 200);
        }else{
            return response()->json([
                'response'      => false,
                'message'       => "There's something wrong"
            ], 200);
        }
    }

    public function login(Request $request){
        $login = Auth::attempt([
            'email' => $request->email,
            'password'  => $request->password
        ]);
        
        if($login){
            $accesstoken = Auth::user()->createToken('authToken')->accessToken;
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'token'         => $accesstoken,
                'user'          => Auth::user()
            ], 200);
        }else{
            return response()->json([
                'response'      => false,
                'message'       => "Failed",
                'token'         => "",
                'user'          => [] 
            ], 200);
        }
    }

    public function companyregistration(Request $request){

        $validation = Validator::make($request->all(), [
            'companyname'       => 'required|string',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|string'
        ]);

        if($validation->fails()){
            $error = $validation->messages()->first();
            return response()->json([
                'response'  => false,
                'message'   => $error
            ]);
        }

        $insertUserAsCompany = DB::connection('mysql')
        ->table('users')
        ->insertGetId([
            'password'  => Hash::make($request->password),
            'email'     => $request->email,
            'positions_id'   => 2
        ]);

        if($insertUserAsCompany){
            $insertpercompanytable = DB::connection('mysql')
            ->table('companies')
            ->insert([
               'company_name'   => $request->companyname,
               'users_id'       => $insertUserAsCompany,
               'created_at'     => date("Y-m-d H:i:s")
            ]);
            if($insertpercompanytable){
                return response()->json([
                    'response'      => true,
                    'message'       => "Success"
                ], 200);
            }
        }else{
            return response()->json([
                'response'      => false,
                'message'       => "Failed"
            ], 200);
        }

    }

    public function iscompanyadmin(){
        if(Auth::user()->positions_id == 2){
            return $this->listagents();
        }else{
            return response()->json([
                'response'      => false,
                'message'       => "You are not a company admin"
            ], 200);
        }
    }

    public function listagents(){
        $query = DB::connection('mysql')
        ->table('agents_lookups')
        ->get();

        if(sizeof($query) > 0){
            $dataids = [];
            foreach($query as $out){
                $dataids[] = $out->users_id;
            }
            $getagentsnotinlookup = DB::connection('mysql')
            ->table('users')
            ->whereNotIn('id', $dataids)
            ->where('positions_id', 3)
            ->get();
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'data'          => $getagent
            ], 200);
        }else{
            $getagents = DB::connection('mysql')
            ->table('users')
            ->where('positions_id', 3)
            ->get();
            return response()->json([
                'response'      => true,
                'message'       => "Success",
                'data'          => $getagents
            ], 200);
        }

    }

    public function addagents($id){
        if(Auth::user()->positions_id == 2){

            $checkifuserexists = DB::connection('mysql')
            ->table('agents_lookups')
            ->whereIn('users_id', explode(',', $id))
            ->where('companies_id', Auth::user()->id)
            ->get();

            if(sizeof($checkifuserexists) > 0){
                return response()->json([
                    'response'  => false,
                    'message'   => "One agent or more exist on your record"
                ], 200);
            }

            $countarray = explode(',', $id);

            $data = [];

            for($i = 0; $i < count($countarray); $i++){
                array_push($data, [
                    'users_id'      => $countarray[$i],
                    'companies_id'  => Auth::user()->id
                ]);
            }

            $insert = DB::connection('mysql')
            ->table('agents_lookups')
            ->insert($data);
            if($insert){
                return response()->json([
                    'response'      => true,
                    'message'       => "Success"
                ], 200);
            }

        }
    }

    public function getcompanyproducts($companyid){
        if(Auth::user()->positions_id == 2){
            $query = DB::connection('mysql')
            ->table('products')
            ->where('updated_by', Auth::user()->id)
            ->get();
            if(sizeof($query) > 0){
                return response()->json([
                    'response'  => true,
                    'message'   => "Success",
                    'data'      => $query
                ], 200);
            }
        }
    }

    public function getMySales($id){

        return $getSales = DB::connection('mysql')
        ->table('sales')
        ->where('users_id', $id)
        ->get();

    }
}
