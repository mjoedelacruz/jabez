<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use DB;
use Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('checkuser'); 
    }
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showUserData()
    {
        $uData = User::all();

         return collect(["data"=>$uData]);
    }

    public function showUserRowData($id)
    {
         $uData = User::find($id);

         return collect(["uData"=>$uData]);
    }
    public function store(Request $request)
    {
       $inputs = $request->all();

           if($inputs['action'] == 1)
           {
              $uCheck = User::where('email','=',$inputs['uName'])
              ->first();
              if($uCheck)
              {
                return 'error';
            }
            else
            {
                $u = new User;
                //Hash::make
                $u->name = $inputs['fullName'];
                $u->email = $inputs['uName'];
                $u->password = Hash::make($inputs['pWord']);
                $u->type = $inputs['uType'];
                $u->status = $inputs['uStatus'];
                $u->save();
            }
           }

           if($inputs['action'] == 2)
           {
            $u = User::find($inputs['userId']);

                $u->name = $inputs['fullName'];
                $u->email = $inputs['uName'];
                if($inputs['pWord'] == '')
                {

                }
                else
                {
                    $u->password = Hash::make($inputs['pWord']);
                }
               
                $u->type = $inputs['uType'];
                $u->status = $inputs['uStatus'];
                $u->save();
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
