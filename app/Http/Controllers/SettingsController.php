<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Models\Inventory;
use App\Models\Inventorymasterlist;
use App\Models\InvMi;
use App\Models\Table;
use App\Models\User;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\BusinessPartner;
use App\Models\Discount; 
use App\Models\Payment; 

use Auth;
use DB; 

class SettingsController extends Controller
{
    //
	public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('checkuser'); 
    }

	public function index()
	{


		return view('settings');
	}

	public function dataTablesDiscounts(){

		$discounts = Discount::select([
			'id',
			'name',
			'discountValue',
		])
		->get();

		return collect(['data' => $discounts]);
	}

	public function getSetSettings(Request $req){
		$inputs = $req->all();

		if($inputs["action"] == 1){
			$discountId = $inputs["discountId"];

			$save = $inputs["save"];

			if($save == 1)
				$discount = new Discount;
			else
				$discount = Discount::find($discountId);

			$discount->name = $inputs["discountName"];
			$discount->type = 1;
			$discount->userId = Auth::user()->id;
			$discount->discountValue = $inputs["discountValue"];
			$discount->save();

		}

	} 
}
