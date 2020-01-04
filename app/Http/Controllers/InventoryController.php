<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Inventorymasterlist;
use App\Models\Invcategory;
use App\Models\Uom;
use App\Models\InventoryCount;
use App\Models\InventoryCountDetail;
//use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class InventoryController extends Controller
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


      $dateString = date("ydmis");
      $invcat = Invcategory::all();
      $uoms = Uom::all();
      $inv = Inventory::all();

        //
      return view('inventory.index',compact('dateString','invcat','uoms','inv'));
    }

    public function lastInvData()
    {
        //$bpList = BusinessPartner::all();
      $lastInv = Inventory::orderBy('id','DESC')->first();
      return collect(["invData"=>$lastInv]);
    }

    public function inventoryData()
    {
      $itemList =  Inventory::join('uoms','uoms.id','=','inventory.uomId')
      ->join('invcategories','invcategories.id','=','inventory.invCategoryId')
        //->where('inventory.id','=',$id)
      ->select([
        "inventory.code as code",
        "inventory.id as id",
        "inventory.name as itemName",
        "inventory.qty as qty",
        'inventory.storageDays as sd',
        "inventory.sellingPrice as price",
        "inventory.status as status",
        "uoms.name as uom",
        "invcategories.name as invcatname",
      ])
      ->get();


      return collect(["data"=>$itemList]);
    }

    public function saveSettleInvQty(Request $request)
    {
      $inputs = $request->all();

      if($inputs['action'] == 1)
      {
       $invCount = new InventoryCount;

       $user = Auth::user()->id;
       $itemCode = $inputs['itemCode'];
       $itemQty = $inputs['itemQty'];
       $newItemQty = $inputs['newItemQty'];

       //$invCount->code = $invCount->id;
       $invCount->userId = $user;
       $invCount->name = $inputs['settleRemarks'];
       $invCount->save();

       for ($i = 0; $i < sizeof($itemCode); $i++)
       {
        if($itemCode[$i] == 0)
          $icd = new InventoryCountDetail;
        else
          $icd2 = InventoryCountDetail::findOrFail($itemCode[$i]);

        $icd->invCountCode = $invCount->id;
        $icd->invCode = $itemCode[$i];
        $icd->invQty = $itemQty[$i];
        $icd->newInvQty = $newItemQty[$i];
        $icd->qtyDiscrepancy = $newItemQty[$i] - $itemQty[$i];
        $icd->save();

        $invCode = Inventory::where('code','=',$itemCode[$i])->first();
        $invAdd = Inventory::where('code','=',$itemCode[$i])->first();

                    //$invQty = $invCode->qty;
        $invAdd->qty = $newItemQty[$i];
        $invAdd->save();


      }

    }
  }

  public function store(Request $request)
  {
    $inputs = $request->all();

    if($inputs["action"] == 1)
    {
      $iName = Inventory::where('name','=', $inputs["itemName"])
      ->first();
      $iCode = Inventory::where('code','=', $inputs["itemCode"])
      ->first();
      if($iName || $iCode)
      {
        return 1;
      }
      else
      {
        $user_id = Auth::user()->id;
        $inv = new Inventory;
        $masList = new Inventorymasterlist;
              //$invDetails = new InventoriesDetails; 
        $inv->name = $inputs["itemName"];
        $inv->code = $inputs["itemCode"];
        $inv->status = $inputs["itemStatus"];
        $inv->storageDays = $inputs["itemStore"];
           // $inv->sellingPrice = $inputs["itemSellingPrice"];
        $inv->userId = $user_id;
        if($inputs["itemCategoryName"] != ""){
          $newCat = new Invcategory;
          $newCat->name = $inputs["itemCategoryName"];
          $newCat->userId = Auth::user()->id;
          $newCat->save();

          $inv->invCategoryId = $newCat->id;
        }
        else{
          $inv->invCategoryId = $inputs["itemCategory"];
        }
           // $inv->invCategoryId = $inputs["itemCategory"];
        if($inputs["uomText"] != ""){
          $newUom = new Uom;
          $newUom->name = $inputs["uomText"];
          $newUom->userId = Auth::user()->id;
          $newUom->save();

          $inv->uomId = $newUom->id;
        }
        else{
          $inv->uomId = $inputs["itemUom"];
        }
            //$inv->uomId = $inputs["itemUom"];
        $inv->save();

        $masList->code = $inputs["itemCode"];
        $masList->userId = $user_id;
        $masList->type = '1';
        $masList->save();





      }


    }

    if($inputs["action"] == 2)
    {
      $user_id = Auth::user()->id;
      $inv = Inventory::find($inputs["itemId"]);
            //$invDetails = InventoriesDetails::find($inputs["itemId"]);
      $masList = Inventorymasterlist::where('code','=', $inputs["itemCode"])
      ->first();

      $inv->name = $inputs["itemName"];
      $inv->code = $inputs["itemCode"];
      $inv->storageDays = $inputs["itemStore"];
      if($inputs["itemCategoryName"] != ""){
        $newCat = new Invcategory;
        $newCat->name = $inputs["itemCategoryName"];
        $newCat->userId = Auth::user()->id;
        $newCat->save();

        $inv->invCategoryId = $newCat->id;
      }
      else{
        $inv->invCategoryId = $inputs["itemCategory"];
      }
           // $inv->invCategoryId = $inputs["itemCategory"];
      if($inputs["uomText"] != ""){
        $newUom = new Uom;
        $newUom->name = $inputs["uomText"];
        $newUom->userId = Auth::user()->id;
        $newUom->save();

        $inv->uomId = $newUom->id;
      }
      else{
        $inv->uomId = $inputs["itemUom"];
      }
      $inv->status = $inputs["itemStatus"];
            //$inv->sellingPrice = $inputs["itemSellingPrice"];
      $inv->userId = $user_id;
      $inv->save();

      $masList->code = $inputs["itemCode"];
      $masList->userId = $user_id;
      $masList->type = '1';
      $masList->save();


    }
    $categories = Invcategory::all();
    $uoms = Uom::all();

    return collect(["categories"=>$categories,"uoms"=>$uoms]);
  }

  public function showInvData($id)
  {
   $itemList =  Inventory::join('uoms','uoms.id','=','inventory.uomId')
   ->join('invcategories','invcategories.id','=','inventory.invCategoryId')
   ->where('inventory.id','=',$id)
   ->select([
    "inventory.id as itemId",
    "inventory.code as icode",
    "inventory.id as id",
    "inventory.name as itemName",
    "inventory.qty as qty",
    "inventory.sellingPrice as price",
    "inventory.status as status",
    'inventory.uomId as uomId',
    'inventory.invCategoryId as invCategoryId',
    'inventory.storageDays as sd',
    "uoms.name as uom",
    "invcategories.name as invcatname",
  ])
   ->first();


   return collect(["itemList"=>$itemList]);     
 }

  public function invCountIndex()
  {
  return view('inventory.invCount');
  }

  public function settleInvCount()
  {
   $inv = Inventory::all();
   return view('inventory.settleInvCount',compact('inv'));
  }

  public function invCountItem($id)
  {
   $inv = Inventory::where('code','=',$id)
   ->join('invcategories as ic','ic.id','=','inventory.invCategoryId')
   ->join('uoms as u','u.id','=','inventory.uomId')
   ->select([
    'inventory.id as invId',
    'inventory.code as invCode',
    'inventory.name as invName',
    'ic.name as icName',
    'u.name as uName',
    'inventory.qty as invQty',
  ])
   ->first();
   return collect(['inv'=>$inv]);
  }

  public function invCountList()
  {
      $icList = InventoryCount::join('users as u','u.id','=','inventory_count.userId')
      ->select([
          'inventory_count.id',
          'inventory_count.name as rem',
          'inventory_count.id as icId',
          'u.name as uName',
          DB::raw("DATE_FORMAT(inventory_count.created_at,'%Y-%m-%d | %H:%i:%S') as Date"),
      ])
      ->get();

      return collect(['data'=>$icList]);
  }

  public function invCountDetails($id)
  {
      $icList = InventoryCount::join('users as u','u.id','=','inventory_count.userId')
      ->join('inventory_count_details as icd','icd.invCountCode','=','inventory_count.id')
      ->join('inventory as inv','inv.code','=','icd.invCode')
      ->where('icd.invCountCode','=',$id)
      ->select([
          
          'inventory_count.id as icId',
          'inv.code as invCode',
          'inv.name as invName',
          'icd.invQty',
          'icd.newInvQty',
          'icd.qtyDiscrepancy as qD',
          'u.name as uName',
          DB::raw("DATE_FORMAT(inventory_count.created_at,'%Y-%m-%d | %H:%i:%S') as Date"),
      ])
      ->get();

      return collect(['icList'=>$icList]);
  }
}
