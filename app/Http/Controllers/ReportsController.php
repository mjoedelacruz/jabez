<?php

namespace App\Http\Controllers;
use App\Models\Inventory;
use App\Models\Sale;
use App\Models\SalesDetail;

use App\Models\GoodsEntry;
use App\Models\GoodsEntryDetail;
use App\Models\BusinessPartner;
use Illuminate\Http\Request;
use DB;
use Auth;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('checkuser'); 
    }
    public function index()
    {


        $inv = Inventory::where('inventory.status','=','1')->get();
        $bp =  BusinessPartner::where('status','=','1')
        ->where('type','=','2')
        ->get();
        $cus =  BusinessPartner::where('status','=','1')
        ->where('type','=','1')
        ->get();
        //$uoms = Uom::all();

        //
        return view('reports.index',compact('inv','bp','cus'));
        
    }

    public function showSalesData($dateStart,$dateEnd,$bp)
    {
        if($bp=='ALL')
        {
            $totalSales =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
            ->select(DB::raw('format(sum(sales.priceAfterDiscount),2) AS rec'))
            ->first();

            $salesData = Sale::join('business_partners as bp','bp.id','=','sales.bpId')
            ->leftJoin('discounts as disc','disc.id','=','sales.discountId')
            ->join('users as u','u.id','=','sales.waiterId')
            ->whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
            ->select([
                'sales.id as sId',
                'bp.name as bp',
                'sales.code',
                'sales.os_no',
                'disc.name as disc',
                DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d') as date"),
                DB::raw('format(sales.totalReceivables,2) as tr'),
                DB::raw('format(sales.totalDiscounts,2) as td'),
                'u.name as uName',
                DB::raw('format(sales.priceAfterDiscount,2) as pad'),


            ])
            ->get();
        }
        else
        {
            $totalSales =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
            ->where('sales.bpId','=',$bp)
            ->select(DB::raw('format(sum(sales.priceAfterDiscount),2) AS rec'))
            ->first();

            $salesData = Sale::join('business_partners as bp','bp.id','=','sales.bpId')
            ->leftJoin('discounts as disc','disc.id','=','sales.discountId')
            ->join('users as u','u.id','=','sales.waiterId')
            ->whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
            ->where('sales.bpId','=',$bp)
            ->select([
                'sales.id as sId',
                'bp.name as bp',
                'sales.code',
                'sales.os_no',
                'disc.name as disc',
                DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d') as date"),
                DB::raw('format(sales.totalReceivables,2) as tr'),
                DB::raw('format(sales.totalDiscounts,2) as td'),
                'u.name as uName',
                DB::raw('format(sales.priceAfterDiscount,2) as pad'),


            ])
            ->get();
        }

        return collect(["data"=>$salesData,"totalSales"=>$totalSales]);
    }

    public function inventoryReportData($dateStart,$dateEnd)
    {
        $itemList =  Inventory::join('invcategories as cat','cat.id','=','inventory.invCategoryId')
        ->join('uoms as uom','uom.id','=','inventory.uomId')
        ->orderBy('inventory.updated_at', 'asc')
        ->whereBetween(DB::raw("DATE_FORMAT(inventory.updated_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
        ->select([
            'inventory.id as invId',
            'inventory.code',
            'inventory.name',
            'inventory.status',
            'cat.name as category',
            DB::raw("format(inventory.qty,2) as Quantity"),
            'uom.name as uname',
            (DB::raw("DATE_FORMAT(inventory.updated_at,'%Y-%m-%d') as Date")),
        ])
        ->get();


        return collect(["data"=>$itemList]);
    }

    public function GoodsEntryData($dateStart,$dateEnd,$bp)
    {
        if($bp=='ALL')
        {
         $totalPay =  GoodsEntry::whereBetween(DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
        //->where('goods_entry.bpId','=',$bp)
         ->select(DB::raw('format(sum(goods_entry.totalPayables),2) AS pay'))
         ->first();

         $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
         ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
         ->whereBetween(DB::raw("DATE_FORMAT(goods_entry.updated_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
         // ->where('goods_entry.bpId','=',$bp)
         ->select([
            'goods_entry.id as id',
            'goods_entry.status as status',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
            'bp.name as bp',
            'users.name as user',
        ])
         ->get();

     }
     else
     {
         $totalPay =  GoodsEntry::whereBetween(DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
         ->where('goods_entry.bpId','=',$bp)
         ->select(DB::raw('format(sum(goods_entry.totalPayables),2) AS pay'))
         ->first();

         $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
         ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
         ->whereBetween(DB::raw("DATE_FORMAT(goods_entry.updated_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
         ->where('goods_entry.bpId','=',$bp)
         ->select([
            'goods_entry.id as id',
            'goods_entry.status as status',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
            'bp.name as bp',
            'users.name as user',
        ])
         ->get(); 
     }

     return collect(["data"=>$goodsList,'totalPay'=>$totalPay]);    

 }

 public function GEDetailsReport($dateStart,$dateEnd,$bp)
 {
    if($bp == 'ALL')
    {
      $ged = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
      ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
      ->join('users','users.id','=','ge.userId')
      ->whereBetween(DB::raw("DATE_FORMAT(goods_entry_details.updated_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
      ->select([
        'goods_entry_details.goodsEntryId as goodsId',
        'goods_entry_details.itemName',
        'goods_entry_details.qty',
        'goods_entry_details.price',
        'goods_entry_details.uom',
        DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
        'bp.name as bp',
        'users.name as uname',
        (DB::raw("DATE_FORMAT(goods_entry_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),

    ])
      ->get();  
  }
  else
  {
    $ged = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
    ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
    ->whereBetween(DB::raw("DATE_FORMAT(goods_entry_details.updated_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
    ->join('users','users.id','=','ge.userId')
    ->where('ge.bpId','=',$bp)
    ->select([
        'goods_entry_details.goodsEntryId as goodsId',
        'goods_entry_details.itemName',
        'goods_entry_details.qty',
        'goods_entry_details.price',
        'goods_entry_details.uom',
        DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
        'bp.name as bp',
        'users.name as uname',
        (DB::raw("DATE_FORMAT(goods_entry_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),

    ])
    ->get();
}

return collect(["data"=>$ged]); 
}


public function showSalesDetails($dateStart,$dateEnd,$bp)
{
    if($bp == 'ALL')
    {
     $totalSales =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
     ->select(DB::raw('format(sum(sales.priceAfterDiscount),2) AS rec'))
     ->first();

     $totalVat =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
     ->select(DB::raw('format(sum(sales.tax),2) AS tax'))
     ->first();

     $sd = SalesDetail::join('sales as s','s.id','=','sales_details.salesId')
     ->join('business_partners as bp','bp.id','=','s.bpId')
     ->join('users as u','u.id','=','s.waiterId')
     ->join('inventorymasterlists as iml','iml.code','=','sales_details.inventoryMasterListId')
     ->join('menu_items as mi','mi.code','=','iml.code')
     ->leftJoin('discounts as d','d.id','=','s.discountId')
     ->whereBetween(DB::raw("DATE_FORMAT(sales_details.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
     ->select([
        'bp.name as bpName',
        'u.name as uName',
        'iml.code as imlCode',
        's.code as sCode',
        'mi.name as miName',
        'sales_details.qty',
        'sales_details.price',
        'sales_details.status',
        'd.name as dName',
        DB::raw('FORMAT(d.discountValue*100,0) as disc'),
        DB::raw('FORMAT(sales_details.qty*sales_details.price,2) as rec'),
        DB::raw('FORMAT((sales_details.qty*sales_details.price)*(1-d.discountValue),2) as dPrice'),
        DB::raw('FORMAT(((sales_details.qty*sales_details.price)/1.12),2) as svat'),
        DB::raw('FORMAT((sales_details.qty*sales_details.price)-((sales_details.qty*sales_details.price)/1.12),2) as vat'),
        (DB::raw("DATE_FORMAT(sales_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
    ])
     ->get();
 }
 else
 {
    $totalSales =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
    ->where('sales.bpId','=',$bp)
    ->select(DB::raw('format(sum(sales.priceAfterDiscount),2) AS rec'))
    ->first();

    $totalVat =  Sale::whereBetween(DB::raw("DATE_FORMAT(sales.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
    ->where('sales.bpId','=',$bp)
    ->select(DB::raw('format(sum(sales.tax),2) AS tax'))
    ->first();

    $sd = SalesDetail::join('sales as s','s.id','=','sales_details.salesId')
    ->join('business_partners as bp','bp.id','=','s.bpId')
    ->join('users as u','u.id','=','s.waiterId')
    ->join('inventorymasterlists as iml','iml.code','=','sales_details.inventoryMasterListId')
    ->join('menu_items as mi','mi.code','=','iml.code')
    ->leftJoin('discounts as d','d.id','=','s.discountId')
    ->whereBetween(DB::raw("DATE_FORMAT(sales_details.created_at,'%Y-%m-%d')"), array($dateStart, $dateEnd))
    ->where('s.bpId','=',$bp)
   ->select([
        'bp.name as bpName',
        'u.name as uName',
        'iml.code as imlCode',
        's.code as sCode',
        'mi.name as miName',
        'sales_details.qty',
        'sales_details.price',
        'sales_details.status',
        'd.name as dName',
        DB::raw('FORMAT(d.discountValue*100,0) as disc'),
        DB::raw('FORMAT(sales_details.qty*sales_details.price,2) as rec'),
        DB::raw('FORMAT((sales_details.qty*sales_details.price)*(1-d.discountValue),2) as dPrice'),
        DB::raw('FORMAT(((sales_details.qty*sales_details.price)/1.12),2) as svat'),
        DB::raw('FORMAT((sales_details.qty*sales_details.price)-((sales_details.qty*sales_details.price)/1.12),2) as vat'),
        (DB::raw("DATE_FORMAT(sales_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
    ])
    ->get();
}


return collect(['data'=>$sd,'totalSales'=>$totalSales,'totalVat'=>$totalVat]);
}

}
