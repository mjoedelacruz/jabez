<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\Inventory;
use App\Models\Inventorymasterlist;
use App\Models\InvMi;
use App\Models\Discount;

use Auth;
use DB;

class MenuItemsController extends Controller
{
    public function __construct()
    {

    } 
    //
    public function index() 
    { 
       

        $menuCategories = MenuCategory::all();
        $invList = Inventory::all();
        $discounts = Discount::whereNotIn("id",array(1,2))->get();

        return view('sales.foodmenu',compact('menuCategories','invList','discounts'));
    }

    public function getSetMenuItems(Request $req)
    {
    	$inputs = $req->all(); 

    	//GET INVENTORY DETAILS
    	if($inputs["action"] == 1){
    		$inv = Inventory::where("inventory.id",$inputs["invID"])
            ->join('uoms','uoms.id','=','inventory.uomId')
            ->select([
               "inventory.id",
               "inventory.name",
               "inventory.qty",
               "uoms.name as uomName"
           ])
            ->first();

            return $inv;

        }
        elseif($inputs["action"] == 2){

            $newCat = null;
            $menuItemCount = count(MenuItem::all())+1;

            if($inputs["save"] ==1){
                $menuItem = new MenuItem;
                $menuItem->code = "MI-".$menuItemCount;
            }
            else if($inputs["save"] == 2)

                $menuItem = MenuItem::find($inputs["miID"]);

                $menuItem->name = $inputs["name"];

            if($inputs["categoryText"] != ""){
                $newCat = new MenuCategory;
                $newCat->name = $inputs["categoryText"];
                $newCat->userId = Auth::user()->id;
                $newCat->save();

                $menuItem->menuCategoryId = $newCat->id;
            }
            else{
                $menuItem->menuCategoryId = $inputs["category"];
            }

            $menuItem->sellingPrice = $inputs["sellingPrice"];
            $menuItem->status = $inputs["status"];
            $menuItem->description = $inputs["description"];
            $menuItem->userId = Auth::user()->id;
            $menuItem->discountId = $inputs["discountId"];
            $menuItem->save();



            $invMiIDs = $inputs["invMiIDs"];
            $itemQtys = $inputs["itemQtys"];
            $invIDs = $inputs["invIDs"];



            if(sizeof($invMiIDs) >= 2){
                for ($i = 1; $i < sizeof($invMiIDs); $i++) {

                    if ($invMiIDs[$i] == 0) {
                        $invMi = new InvMi;

                    } else {
                        $invMi = InvMi::findOrFail($invMiIDs[$i]);

                    }

                    $invMi->menuItemId = $menuItem->code;
                    $inv = Inventory::find($invIDs[$i]);

                    $invMi->invId = $inv->code;
                    $invMi->qty = ($itemQtys[$i] ? $itemQtys[$i] : 0);
                    $invMi->userId = Auth::user()->id;
                    $invMi->save();

                }
            }


            if($inputs["save"] == 1){
                $invMasterList = new Inventorymasterlist;
                $invMasterList->code = $menuItem->code;
                $invMasterList->type = 2;
                $invMasterList->userId = Auth::user()->id;
                $invMasterList->save();
            }


           // return $image; 

            $categories = MenuCategory::all();
            $invList = Inventory::all();

            return collect(["save"=>$inputs["save"], "name" => $menuItem->name,"categories"=>$categories,"invList"=>$invList]);
        }
        else if($inputs["action"] == 3)
        {
            $menuItem = MenuItem::where("code",$inputs["miID"])
            ->select([
                "name",
                "description",
                "menuCategoryId",
                "status",
                "sellingPrice",
                "code",
                "discountId",
                "id",
            ])
            ->first();

            $miList = InvMi::where("inv_mi.menuItemId","=",$inputs["miID"])
            ->leftjoin('inventory as i','i.code','=','inv_mi.invId')
            ->leftjoin('uoms as um','um.id','=','i.uomId')
            ->select([
                "i.name as invName",
                "um.name as uomName",
                "i.id as invId",
                "inv_mi.qty",
                "inv_mi.id"
            ])
            ->get();

            $tempInvList = [];

            foreach($miList as $mi){
                array_push($tempInvList, $mi->invId);
            }

            $invList = Inventory::whereNotIn("id",$tempInvList)
            ->select([
                "id",
                "name",
            ])
            ->get();


            return collect(["menuItem"=>$menuItem,"miList"=>$miList,"invList"=>$invList]);
        }
        else if($inputs["action"] == 4){
            $invmi = InvMi::find($inputs["id"]);
            $inv = Inventory::find($invmi->invId);
            $invmi->delete();
            
            if($inv){


                return $inv;

            }

        }
    }

    public function dataTablesMenuItemsList()
    {
        $menuItems = MenuItem::join('menucategories as mc','mc.id','=','menu_items.menuCategoryId')
        ->orderby('menu_items.id')
        ->select([
            'menu_items.id',
            'menu_items.name',
            'menu_items.code',
            'mc.name as catName',
            'menu_items.description',
            DB::raw('
                case menu_items.status
                when "1" then "Active"
                when "2" then "Inactive"
                end as statusName'),
            'menu_items.sellingPrice'
        ])
        ->get();

        return collect(['data' => $menuItems]);
    }
}
