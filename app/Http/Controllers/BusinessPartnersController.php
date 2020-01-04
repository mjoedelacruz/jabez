<?php

namespace App\Http\Controllers;

use App\Models\BusinessPartner;
use Auth;
use DB;
use Illuminate\Http\Request;

class BusinessPartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('checkuser'); 
    }
    
    public function index()
    {
        return view('businesspartners.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bpData()
    {
        $bpList = BusinessPartner::all();
        //$lastBp = BusinessPartner::last();
         return collect(["data"=>$bpList]);
    }

    public function lastBpData()
    {
        //$bpList = BusinessPartner::all();
        $lastBp = BusinessPartner::orderBy('id','DESC')->first();
         return collect(["bpData"=>$lastBp]);
    }

     public function showBpData($id)
    {
        $bpData = BusinessPartner::find($id);
         return collect(["bpData"=>$bpData]);
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        if($inputs['action'] == 1)
        {
            $bpCode = BusinessPartner::where('bpCode','=',$inputs['bpCode'])->first();
            $bpName = BusinessPartner::where('name','=',$inputs['bpName'])->first(); 

            if( $bpCode || $bpName ) 
            {
                return 1;
            }
            else
            {
                $user_id = Auth::user()->id;
                $bp = new BusinessPartner;

                $bp->bpCode = $inputs['bpCode'];
                $bp->name = $inputs['bpName'];
                $bp->contactPerson = $inputs['bpPerson'];
                $bp->contactNo = $inputs['bpContact'];
                $bp->address = $inputs['bpAddress'];
                $bp->email = $inputs['bpEmail'];
                $bp->status = $inputs['bpStatus'];
                $bp->type = $inputs['bpType'];
                $bp->userId = $user_id;
                $bp->save();
            }
        }

         if($inputs['action'] == 2)
         {
                $user_id = Auth::user()->id;
                $bp = BusinessPartner::find($inputs['bpId']);

                $bp->bpCode = $inputs['bpCode'];
                $bp->name = $inputs['bpName'];
                $bp->contactPerson = $inputs['bpPerson'];
                $bp->contactNo = $inputs['bpContact'];
                $bp->address = $inputs['bpAddress'];
                $bp->email = $inputs['bpEmail'];
                $bp->status = $inputs['bpStatus'];
                $bp->type = $inputs['bpType'];
                $bp->userId = $user_id;
                $bp->save();
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BusinessPartners  $businessPartners
     * @return \Illuminate\Http\Response
     */
    public function show(BusinessPartners $businessPartners)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BusinessPartners  $businessPartners
     * @return \Illuminate\Http\Response
     */
    public function edit(BusinessPartners $businessPartners)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BusinessPartners  $businessPartners
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BusinessPartners $businessPartners)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BusinessPartners  $businessPartners
     * @return \Illuminate\Http\Response
     */
    public function destroy(BusinessPartners $businessPartners)
    {
        //
    }
}
