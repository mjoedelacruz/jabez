<?php

namespace App\Http\Controllers;

use App\Models\GoodsEntry;
use App\Models\GoodsEntryDetail;
use App\Models\GoodsReturn;
use App\Models\GoodsReturnDetail;
use App\Models\Inventory;
use App\Models\BusinessPartner;
use App\Models\Inventorymasterlist;
use App\Models\User;
use Illuminate\Http\Request;


use Auth;
use DB;

class GoodsEntryController extends Controller
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
        $date_1 = date('Y-m-d');
        $date2 = date('Y-m-d', strtotime($date_1. ' + 1 days'));
        $date3 = date('Y-m-d', strtotime($date_1. ' + 2 days'));

        $dateString = date("ydmis");
        $inv = Inventory::where('inventory.status','=','1')->get();
        $bp =  BusinessPartner::where('status','=','1')
        ->where('type','=','2')
        ->get();
        
        $storage = GoodsEntryDetail::select(DB::raw('(count(id)) AS id'))
        ->first();

       

        return view('goodsentry.index',compact('inv','bp','storage'));
        //return view('goodsentry.index');
    }

    public function staleItems()
    {
        $storage = GoodsEntryDetail::select(DB::raw('(count(id)) AS id'))
        ->first();

        $gedStale = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
        ->join('inventory as inv','inv.code','=','goods_entry_details.invId')
       // ->join('goods_return as gr','gr.gdsEntryCode','=','ge.id')
       // ->join('goods_return_details as grd','grd.gdsReturnCode','=','gr.id')
        ->where('goods_entry_details.qty','!=',0)
        ->where('inv.storageDays','!=',0)
        ->orderBy('goods_entry_details.storeDays','ASC')
        ->select([
            'ge.id',
            'goods_entry_details.invId',
            'goods_entry_details.itemName',
            'goods_entry_details.qty',
            'goods_entry_details.storeDays as sd',
            'goods_entry_details.storeDaysFrom as sdf',
           // 'gr.gdsEntryCode as grc',
        ])
        ->get();

        return collect(['data'=>$gedStale]);
    }

    public function selectItemCode($id)
    {
        $itemList =  Inventory::join('uoms','uoms.id','=','inventory.uomId')
        ->join('invcategories','invcategories.id','=','inventory.invCategoryId')
        ->where('inventory.code','=',$id)
        ->where('inventory.status','=','1')
        ->select([
            "inventory.id as itemId",
            "inventory.code as icode",
            "inventory.name as itemName",
            "uoms.name as uom",
            "inventory.storageDays as sd",
        ])
        ->first();


        
        return collect(["itemCodeList"=>$itemList]);  
    }

    public function showGoodsEntry()
    {
        $date = date("m-d-y");

        $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
        ->select([
            'goods_entry.id as id',
            'goods_entry.status as status',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d') as Date"),
            'bp.name as bp',
            'users.name as user',
        ])
        ->get();
        return collect(["data"=>$goodsList]);  
    }

    public function showGoodsReturn()
    {
        $gr = GoodsReturn::join('users as u','u.id','=','goods_return.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_return.bpCode')
        ->select([
            'goods_return.id',
            'goods_return.gdsEntryCode as geId',
            'goods_return.status',
            'u.name as user',
            'bp.name as bp',
            DB::raw('format(goods_return.totalAmount,2) as amt'),
            DB::raw("DATE_FORMAT(goods_return.created_at,'%Y-%m-%d') as Date"),
        ])
        ->get();

        return collect(["data"=>$gr]);
    }

    public function gReturnDetailList($id)
    {
       $grd = GoodsReturnDetail::join('goods_return as ge','ge.id','=','goods_return_details.gdsReturnCode')
        ->join('business_partners as bp','bp.bpCode','=','ge.bpCode')
        ->join('inventory as inv','inv.code','=','goods_return_details.invCode')
        ->where('goods_return_details.gdsReturnCode','=',$id)
        ->select([
            'goods_return_details.invCode as iCode',
            'goods_return_details.invName',
            'goods_return_details.qty',
            'inv.storageDays as sd',
            'goods_return_details.price',
            DB::raw('format(goods_return_details.qty*goods_return_details.price,2) as amt'),
            'bp.bpCode as bp',
        ])
        ->get(); 

        $gr = GoodsReturn::join('users as u','u.id','=','goods_return.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_return.bpCode')
        ->where('goods_return.id','=',$id)
        ->select([
            'goods_return.id',
            'goods_return.gdsEntryCode as geId',
            'goods_return.status',
            'u.name as user',
            'bp.name as bp',
            DB::raw('format(goods_return.totalAmount,2) as amt'),
            DB::raw("DATE_FORMAT(goods_return.created_at,'%m-%d-%y') as Date"),
        ])
        ->first();

        return collect(["data"=>$grd,"gr"=>$gr]);
    }

    

    public function gEntryDetailList($id)
    {
        $ged = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
        ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
        ->join('inventory as inv','inv.code','=','goods_entry_details.invId')
        ->where('goods_entry_details.goodsEntryId','=',$id)
        ->where('goods_entry_details.qty','!=',0)
        ->select([
            'inv.storageDays as sd',
            'goods_entry_details.invId as iCode',
            'goods_entry_details.itemName',
            'goods_entry_details.qty',
            'goods_entry_details.price',
            DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
            'bp.bpCode as bp',

        ])
        ->get();

        $ged2 = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
        ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
        ->join('inventory as inv','inv.code','=','goods_entry_details.invId')
        ->where('goods_entry_details.goodsEntryId','=',$id)
        ->where('goods_entry_details.qty','!=',0)
        ->select([
            'inv.storageDays as sd',
            'goods_entry_details.invId as iCode',
            'goods_entry_details.itemName',
            'goods_entry_details.uom',
            'goods_entry_details.qty',
            'goods_entry_details.price',
            DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
            'bp.bpCode as bp',

        ])
        ->get();

        $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
        ->where('goods_entry.id','=',$id)
        ->select([
            'goods_entry.id as id',

            'goods_entry.status as status',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
            'bp.name as bp1',
            'bp.bpCode',
            'users.name as user',
        ])
        ->first();

        return collect(["data"=>$ged,"goodsList"=>$goodsList,"GEDetails"=>$ged2]);  
        
    }
    
    public function create()
    {
        //
    }

    

    public function store(Request $request)
    {
        $inputs = $request->all();

        if($inputs['action'] == 1)
        {
            $ge = new GoodsEntry;
            
            $user_id = Auth::user()->id;
            $Date = date('Y-m-d');
            $date2 = date('Y-m-d', strtotime($Date. ' + 1 days'));
            $date3 = date('Y-m-d', strtotime($Date. ' + 2 days'));
            $itemCode = $inputs['itemCode'];
            $itemQty =$inputs['itemQty'];
            $itemPrice = $inputs['itemPrice'];
            $itemUom = $inputs['itemUom'];
            $itemName = $inputs['itemName'];

            $ge->bpId = $inputs['bpCode'];
            $ge->userId = $user_id;
            $ge->status = 1;
            $ge->save();
            $totalPay = 0;
            //$totalQty = 0;

            for ($i = 0; $i < sizeof($itemCode); $i++)
            {
                if($itemCode[$i] == 0)
                    $ged = new GoodsEntryDetail;
                else
                    $uomConvDetails = GoodsEntryDetail::findOrFail($itemCode[$i]);

                $ged->goodsEntryId = $ge->id;
                $ged->invId = $itemCode[$i];
                $ged->qty = $itemQty[$i];
                $ged->price = $itemPrice[$i];
                $ged->uom = $itemUom[$i];
                $ged->itemName = $itemName[$i];
                $ged->userId = $user_id;
                $ged->save();

                $totalPay+=$itemQty[$i]*$itemPrice[$i];
                $ge->totalPayables = $totalPay;
                $ge->save();

                $invCode = Inventory::where('code','=',$itemCode[$i])->first();
                $invAdd = Inventory::where('code','=',$itemCode[$i])->first();


                    //$invQty = $invCode->qty;
                $invAdd->qty = $invCode->qty + $itemQty[$i];
                $invAdd->save();

                $ged->storeDays = date('Y-m-d', strtotime($Date. ' + '.$invCode->storageDays.' days'));
                 $ged->storeDaysFrom = date('Y-m-d');
                $ged->save();



            } 



        }

        if($inputs['action'] == 3)
        {
           $ge = GoodsEntry::find($inputs['goodsEntryId']);
           $user_id = Auth::user()->id;

           $ge->status = 2;
           $ge->userId = $user_id;
           $ge->save();
       }

       if($inputs['action'] == 4)
       {    
        $gr = new GoodsReturn;

        $user_id = Auth::user()->id;

        $itemCode = $inputs['itemCode'];
        $itemQty =$inputs['itemQty'];
        $itemPrice = $inputs['itemPrice'];
        $itemUom = $inputs['itemUom'];
        $itemName = $inputs['itemName'];

        $gr->bpCode = $inputs['bpCode'];
        $gr->userId = $user_id;
        $gr->gdsEntryCode = $inputs['goodsEntryId'];
        $gr->status = 1;
        $gr->save();
        $totalPay = 0;
            //$totalQty = 0;
        for ($i = 0; $i < sizeof($itemCode); $i++)
        {
            if($itemCode[$i] == 0)
            {
                $grd = new GoodsReturnDetail;
            }
            else
            {
                $grd = GoodsReturnDetail::findOrFail($itemCode[$i]);
            }

            $ged = GoodsEntryDetail::where('goodsEntryId','=',$inputs['goodsEntryId'])
            ->where('invId','=',$itemCode[$i])
            ->first();
            $ged2 = GoodsEntryDetail::where('goodsEntryId','=',$inputs['goodsEntryId'])
            ->where('invId','=',$itemCode[$i])
            ->first();
            

            $grd->gdsReturnCode = $gr->id;
            $grd->invCode = $itemCode[$i];
            $grd->qty = $itemQty[$i];
            $grd->price = $itemPrice[$i];
            $grd->uom = $itemUom[$i];
            $grd->invName = $itemName[$i];
            $grd->userId = $user_id;
            $grd->save();

            $totalPay+=$itemQty[$i]*$itemPrice[$i];
            $gr->totalAmount = $totalPay;
            $gr->save();

            $invCode = Inventory::where('code','=',$itemCode[$i])->first();
            $invAdd = Inventory::where('code','=',$itemCode[$i])->first();
            
            
                    //$invQty = $invCode->qty;
            $ged2->qty = $ged->qty - $itemQty[$i];
            $ged2->save();
            
            $invAdd->qty = $invCode->qty - $itemQty[$i];
            $invAdd->save();
            } 

            $ge = GoodsEntry::where('id','=',$inputs['goodsEntryId'])
            ->first();
            $ge2 = GoodsEntry::where('id','=',$inputs['goodsEntryId'])
            ->first();
            $ge2->totalPayables = $ge->totalPayables - $gr->totalAmount;
            $ge2->save();
       }
   }

    /**
     * Display the specified resource.
     *
     * @param  \App\GoodsEntry  $goodsEntry
     * @return \Illuminate\Http\Response
     */
    public function show(GoodsEntry $goodsEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GoodsEntry  $goodsEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(GoodsEntry $goodsEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GoodsEntry  $goodsEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GoodsEntry $goodsEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GoodsEntry  $goodsEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoodsEntry $goodsEntry)
    {
        //
    }
}
