<?php

namespace App\Http\Controllers;

use App\Dashboard;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\InvMi;
use App\Models\Inventorymasterlist;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       
      return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardData()
    {
       $date = date('Y-m-d');
       $totalSales = Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($date, $date))
       ->select(DB::raw('format(sum(sales.priceAfterDiscount),2) AS sales'))
       ->first();

       $totalInvoices =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($date, $date))
        ->select(DB::raw('(count(sales.id)) AS invoice'))
        ->first();

       return collect(['totalSales'=>$totalSales,'totalInvoices'=>$totalInvoices]);
    }

    public function showSales()
    {
         $date = date('Y-m-d');
        $salesData = Sale::join('business_partners as bp','bp.id','=','sales.bpId')
        ->leftJoin('discounts as disc','disc.id','=','sales.discountId')
        ->join('users as u','u.id','=','sales.userId')
        ->whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($date, $date))
        ->select([
            'sales.id as sId',
            'bp.name as bp',
            'sales.code',
            'sales.os_no',
            DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d') as date"),
            'disc.name as disc',
            DB::raw('format(sales.totalReceivables,2) as tr'),
            DB::raw('format(sales.totalDiscounts,2) as td'),
            'u.name as uName',
            DB::raw('format(sales.priceAfterDiscount,2) as pad'),


        ])
        ->get();

        return collect(["data"=>$salesData]);
    }

    public function showSalesDetails($id)
    {
        $totalRec =  Sale::where('sales.id','=',$id)->select(DB::raw('FORMAT(priceAfterDiscount,2) as pad'))->first();

         $saleDetails = SalesDetail::join('inventorymasterlists as iml','iml.code','=','sales_details.inventorymasterlistId')
        ->join('sales as s','s.id','=','sales_details.salesId')
        ->leftJoin('discounts as disc','disc.id','=','s.discountId')
        ->join('menu_items as mi','mi.code','=','iml.code')
        ->where('sales_details.salesId','=',$id)
        ->where('sales_details.status','=',0)
        ->select([
            
             DB::raw('format(if(disc.discountValue=null,0,disc.discountValue*100),0) as dVal'),
            's.code as sCode',
            'mi.code as mCode',
            'mi.name as mName',
            'sales_details.qty',
            'sales_details.price',
            DB::raw('format((sales_details.price*sales_details.qty)*(1-disc.discountValue),2) as amount'),

        ])
        ->get();

        $saleDetails2 = SalesDetail::join('inventorymasterlists as iml','iml.code','=','sales_details.inventorymasterlistId')
        ->join('sales as s','s.id','=','sales_details.salesId')
        ->join('business_partners as bp','bp.id','=','s.bpId')
        ->join('menu_items as mi','mi.code','=','iml.code')
        ->where('sales_details.salesId','=',$id)
        ->where('sales_details.status','=',0)
        ->select([
            'bp.name as bp',
            's.code as sCode',
            'mi.code as mCode',
            'mi.name as mName',
            'sales_details.qty',
            'sales_details.price',
            DB::raw('format(sales_details.price*sales_details.qty,2) as amount'),

        ])
        ->first();

        return collect(["data"=>$saleDetails,'totalRec'=>$totalRec,"saleList"=>$saleDetails2]);

    }

    public function invMiDetails($id)
    {
        $menu = InvMi::where('menuItemId','=',$id)
        ->get();

        return collect(['data'=>$menu]);
    }

    public function invDataDB()
    {
        $invDB = Inventorymasterlist::join('sales_details as sd','sd.inventorymasterlistId','=','inventorymasterlists.id')
        ->join('menu_items as mi','mi.code','=','inventorymasterlists.code')
        ->leftjoin('inv_mi as im','im.menuItemid','=','inventorymasterlists.code')
        ->select([
            'mi.id as mi',
            'inventorymasterlists.id',
            'inventorymasterlists.code',
            'sd.qty as sdQty',
            'sd.price as sdPrice',
        ])
        ->get();

        $menu = Inventorymasterlist::leftjoin('sales_details as sd','sd.inventorymasterlistId','=','Inventorymasterlists.code')
        ->leftjoin('inventory as inv','inv.code','=','inventorymasterlists.code')
        ->leftjoin('inv_mi as im','im.menuItemId','=','inventorymasterlists.code')
        ->leftjoin('inv_mi as im2','im2.invId','=','inventorymasterlists.code')
        ->leftjoin('menu_items as mi','mi.code','=','inventorymasterlists.code')
        ->select([
            'inv.name as invName',
            'mi.name as imName',

            'im.qty as imQty',
            'sd.qty as sdQty',

            'inv.qty as invQty',

            
        ])
        ->get();       

         $invDB2 = Inventory::leftjoin('inv_mi as im','im.invId','=','inventory.code')
         ->select([
            'inventory.id as invId',
            'inventory.code as invCode',
            'inventory.name as invName',
            'inventory.qty as invQty',
        ])
        ->get();

       // $invDB3 = DB::table('Inventory as Inv')
       //  ->select(DB::raw('(Inv.qty) - (inv_mi.qty) as total'))
       //  ->join('inv_mi as im', DB::raw('Inv.id'), '=', DB::raw('im.invId'))
       //  ->get();"data2"=>$invDB,,"data"=>$invDB2,'data'=>$invDB2

        return collect(['menu'=>$menu,'data'=>$invDB2]);
    }

    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        //
    }
}
