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
use App\Models\Oslist;

use Auth;
use DB;  

class SalesController extends Controller
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

     $osCount = count(Oslist::all())+1;
     $categories = MenuCategory::all();
     $menuItems = MenuItem::where("status",1)
     ->leftjoin("discounts as d","d.id","=","menu_items.discountId")
     ->select([
        "menu_items.id",
        "menu_items.name",
        "menu_items.menuCategoryId",
        DB::raw("if(menu_items.discountId != 0, concat('(',d.name,')'), '') as discountName"),
    ])
     ->get();

     $salesCount = count(Sale::all())+1;
     $bps = BusinessPartner::where('type',1)->get();
     $tables = Table::all();
     $users = User::where('type',2)->get();


     return view('sales.index',compact('categories','menuItems','osCount','tables','users','salesCount','bps'));
 }

 public function orderList(){

    $tables = Table::all();
    $users = User::where('type',2)->get();
    $bps = BusinessPartner::all();
    $discounts = Discount::all();

    $menuItems = MenuItem::where("status",1)
    ->join('inventorymasterlists as im','im.code','=','menu_items.code')
    ->join('menucategories as cat','cat.id','=','menu_items.menuCategoryId')
    ->select([
        'im.id',
        DB::raw("concat(menu_items.name,' - ',cat.name) as name")
    ])->get();

    return view('sales.orderlist',compact('tables','users','bps','menuItems','discounts'));
}

public function getSetSales(Request $req){
    $inputs = $req->all();

    if($inputs["action"] == 1){
        $menuItem = MenuItem::join("inventorymasterlists as im","im.code","=","menu_items.code")
        ->leftJoin("discounts as d","d.id","=","menu_items.discountId")
        ->where("im.type",2)
        ->where("menu_items.status",1)
        ->where("menu_items.id",$inputs["miID"])
        ->select([
            "im.id",
            "menu_items.name",
            DB::raw("if(menu_items.discountId != 0, menu_items.sellingPrice-(menu_items.sellingPrice*d.discountValue),menu_items.sellingPrice) as sellingPrice"),
            "im.code",
        ])
        ->first();


        return $menuItem;
    }
    elseif($inputs["action"] == 2){

          //  $salesCount = count(Sale::all())+1;
        $itemList = '$items = array(';

        $orderItems = '$orders = array(';

        $sales = new Sale;
        $sales->code= $inputs["orderNo"];
        $sales->os_no = $inputs["osNo"] ? $inputs["osNo"] : 0;
        $sales->bpId = $inputs["bp"];
        $sales->tableId = $inputs["table"];
        $sales->discountId = 0;
        $sales->entryDate = date("Y-m-d");
        $sales->remarks = $inputs["remarks"];
        $sales->noOfGuests = 1;
        $sales->totalDiscounts = 0;
        $sales->totalReceivables = $inputs["total"];

        $priceAfterVat = $inputs["total"]/1.12;

        $sales->priceAfterDiscount = $inputs["total"];
        $sales->priceAfterVat = $inputs["total"]/1.12;
        $sales->tax = $inputs["total"] - $priceAfterVat;

        $sales->userId = Auth::user()->id;
        $sales->waiterId = $inputs["waiter"];
        $sales->status = 1;
        $sales->paidThru = 1;
        $sales->save();

        $masterlistIDs = $inputs["masterlistIDs"];
        $qties = $inputs["qty"];
        $prices = $inputs["price"];
        $codes = $inputs["codes"];

        $os = new Oslist;
        $os->code = $inputs["osNo"] ? $inputs["osNo"] : 0;
        $os->salesId = $sales->id;
        $os->userId = Auth::user()->id;
        $os->save();
        

        for ($i = 0; $i < sizeof($masterlistIDs); $i++) {


            $sd = new SalesDetail;
            $sd->salesId = $sales->id;
            $sd->inventoryMasterListId = $codes[$i];
            $sd->qty = $qties[$i];
            $sd->discountId = 0;
            $sd->entryDate = date("Y-m-d");
            $sd->discounts = 0;
            $sd->status = 0;
            $sd->osId = $os->id;
            $sd->price = $prices[$i];

            $itemName = Inventorymasterlist::join('menu_items as mi','mi.code','=','inventorymasterlists.code')
            ->where("inventorymasterlists.id",$masterlistIDs[$i])
            ->select([
                "mi.name"
            ])
            ->first();
            $sd->orderName = $itemName->name;
            $sd->userId = Auth::user()->id;
            $sd->save();

            $itemList.='new item("'.$itemName->name.'","'.$sd->qty.'","'.number_format($sd->price,2).'","'.number_format($sd->qty*$sd->price,2).'"),';

            $orderItems.='new item("","'.$sd->qty.'","","'.$itemName->name.'"),';


        }

        $itemList.=");";
        $orderItems.=");";


        $discount = Discount::find($sales->discountId);

        $discountName = "-";
        if($discount)
            $discountName = $discount->name;



        $tableList = Table::find($sales->tableId);
        $server = User::find($sales->waiterId);
        $customer = BusinessPartner::find($sales->bpId);

        $receiptFile = fopen("bills/bill-".$sales->code.".php","w");

        $txt = '<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        

        '.$itemList.'

        date_default_timezone_set("Asia/Singapore");

        $date = date("l jS \of F Y\nh:i:s A");


        /* Start the printer */
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);


        /* Name of shop */
        $logo = EscposImage::load("resources/qlogo.png", false);


        $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);

        $printer -> text("Q Citipark Hotel\n");

        $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
        $printer -> text("General Santos City\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("BILLING STATEMENT\n");
        $printer -> setEmphasis(false);
        $printer -> feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $invColon = str_pad(":",4," ",STR_PAD_LEFT);
        $invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
        $printer -> text("Bill No".$invColon.$invNo."\n");

        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("'.$server->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("'.$customer->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("'.$tableList->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        $numGuestColon = str_pad(":",4," ",STR_PAD_LEFT);
        $numGuestsNum = str_pad("'.$sales->noOfGuests.'",3," ",STR_PAD_LEFT);
        $printer -> text("Guest/s".$numGuestColon.$numGuestsNum."\n");

        $printer->feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        
        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $totalAmount = str_pad("'.number_format($sales->totalReceivables,2).'",32," ",STR_PAD_LEFT);

        $printer->feed();
        $printer -> text("Subtotal".$totalAmount."\n");

        $printer -> text("DISCOUNT:\n");
        $discount = "'.$discountName.'";
        $discountCols = 40 - strlen($discount);
        $discountAmount = str_pad("'.number_format($sales->totalDiscounts,2).'",$discountCols," ",STR_PAD_LEFT);
        $printer->text($discount.$discountAmount."\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $totalAmount = str_pad("'.number_format($sales->priceAfterDiscount,2).'",29," ",STR_PAD_LEFT);

        $printer -> text("GRAND TOTAL".$totalAmount."\n");
        $printer -> setEmphasis(false);

        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Thank you for dining at Q Citipark Hotel\nCome Again!\n");

        // $printer -> feed(2);
        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();

        /* A wrapper to do organise item names & prices into columns */
        class item
        {
            private $name;
            private $price;
            private $dollarSign;

            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
                $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
                return "$left\n$right$price$tp\n";
            }
        }';

        fwrite($receiptFile, $txt);
        fclose($receiptFile);

        $orderFile = fopen("orderslips/os-".$os->code.".php","w");
        
        $txt2 = '<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        '.$orderItems.'

        date_default_timezone_set("Asia/Singapore");

        $date = date("l jS \of F Y\nh:i:s A");


        /* Start the printer */
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);


        /* Name of shop */
        $logo = EscposImage::load("resources/qlogo.png", false);


        $printer -> text("Q Citipark Hotel\n");

        $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
        $printer -> text("General Santos City\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("ORDER SLIP\n");
        $printer -> setEmphasis(false);
        $printer -> setEmphasis(true);
        $printer -> text("'.$os->code.'\n");
        $printer -> setEmphasis(false);
        $printer -> feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $invColon = str_pad(":",4," ",STR_PAD_LEFT);
        $invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
        $printer -> text("Bill No".$invColon.$invNo."\n");


        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("'.$server->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("'.$customer->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("'.$tableList->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        $printer->feed();

        $qtyText = str_pad("QTY",6," ",STR_PAD_LEFT);
        $itemText = str_pad("ITEM",25," ",STR_PAD_LEFT);
        $printer->text($qtyText.$itemText);

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        foreach ($orders as $item) {
            $printer -> text($item);
        }
        



        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> feed(2);
        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();

        /* A wrapper to do organise item names & prices into columns */
        class item
        {
            private $name;
            private $price;
            private $dollarSign;

            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
                $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
                return "$left\n$right$price$tp\n";
            }
        }

        /* A wrapper to do organise item names & prices into columns */
        class order
        {
            private $name;
            private $price;
            private $dollarSign;

            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_RIGHT);
                $price = str_pad($this->price, 10," ",STR_PAD_RIGHT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_RIGHT);
                return "$right$price$tp\n";
            }
        }

        ';

        fwrite($orderFile, $txt2);
        fclose($orderFile);

        $bpInfo =  BusinessPartner::find($inputs["bp"]);

        $newSaleCount = count(Sale::all())+1;

        return collect(["bpName"=>$bpInfo->name,"saleCount"=>$newSaleCount,"waiterId"=>Auth::user()->id]);

    }
    elseif($inputs["action"] == 3){
        $sales = Sale::where("sales.id",$inputs["orderID"])
        ->leftjoin("discounts as d","d.id","=","sales.discountId")
        ->leftjoin("users as u", "u.id","=","sales.userId")
        ->select([
            "sales.id",
            "code",
            "os_no",
            "bpId",
            "tableId",
            "discountId",
            "waiterId",
            DB::raw("DATE_FORMAT(entryDate,'%b. %d, %Y') as date"),
            "totalReceivables",
            "sales.status",
            'sales.totalDiscounts',
            'sales.tax',
            'sales.noOfGuests',
            'sales.noOfSpecial',
            'sales.priceAfterDiscount',
            'sales.remarks',
            'priceAfterVAT',
            'd.name as discountName',
            'change',
            'paidThru',
            'cardNo',
            "u.name as userName",
            'cardTransactionNo',
            'sales.zeroRated',
            DB::raw('ROUND(cashAmount,2) as cashAmount'),
        ])
        ->first();



        $payments = Payment::where("salesId",$inputs["orderID"])
        ->select([
            "id",
            "name",
            "type",
            DB::raw("if(transactionNo is not null, transactionNo, '') as transactionNo"),

            "amount",
        ])
        ->get();

        $tempMasterlists = [];

        $salesOrders = SalesDetail::where("sales_details.salesId","=",$inputs["orderID"])
        ->join("inventorymasterlists as im","im.code","=","sales_details.inventoryMasterListId")
        ->join("oslist as os","os.id","=","sales_details.osId")
        ->select([
            'sales_details.orderName',
            'sales_details.qty',
            'sales_details.price',
            'sales_details.status',
            'sales_details.id',
            'sales_details.free',
            "os.code as osCode",
            "os.id as osId",
            'im.id as inventoryMasterListId',
            "im.code as imCode",
        ])
        ->orderby("osCode","asc")
        ->get();

        foreach($salesOrders as $so){
            array_push($tempMasterlists, $so->inventoryMasterListId);
        }

        $menuItems = MenuItem::where("status",1)
        ->join('inventorymasterlists as im','im.code','=','menu_items.code')
        ->join('menucategories as cat','cat.id','=','menu_items.menuCategoryId')
        ->whereNotIn("im.id",$tempMasterlists)
        ->select([
            'im.id',
            DB::raw("concat(menu_items.name,' - ',cat.name) as name")
        ])->get();

        $accountType = Auth::user()->type;

        

        $orderlists = Oslist::where("salesId","=",$inputs["orderID"])
        ->select([
            'id',
            'code'
        ])
        ->get();

        return collect(["sales"=>$sales,"salesOrders"=>$salesOrders,"orderlists"=>$orderlists,"accountType"=>$accountType, "menuItems"=>$menuItems,"tempMasterlists"=>$tempMasterlists,"payments"=>$payments]);


    }
    elseif($inputs["action"] == 4){
        $isVoid = $inputs["sdIsVoid"];
        $sd = SalesDetail::find($inputs["sdId"]);
        if($isVoid){
            $sd->status = 0;
            $sd->save();
            return collect(["message"=>"Removed Item Void","check"=>0,"id"=>$inputs["sdId"]]);
        }
        else{
            $sd->status = 1;
            $sd->save();
            return collect(["message"=>"Item Voided","check"=>1,"id"=>$inputs["sdId"]]);
        }

    }
    elseif($inputs["action"] == 13){
        $isFree = $inputs["sdIsFree"];
        $sd = SalesDetail::find($inputs["sdId"]);

        if($isFree){
            $sd->free = 0;
            $sd->save();
            return collect(["message"=>"Removed Item FOC","check"=>0,"id"=>$inputs["sdId"]]);
        }
        else{
            $sd->free = 1;
            $sd->save();
            return collect(["message"=>"Item FOC","check"=>1,"id"=>$inputs["sdId"]]);
        }

    }
    elseif($inputs["action"] == 5){

        $menuItem = MenuItem::join("inventorymasterlists as im","im.code","=","menu_items.code")
        ->where("im.type",2)
        ->where("menu_items.status",1)
        ->where("im.id",$inputs["miID"])
        ->select([
            "im.id",
            "menu_items.code",
            "menu_items.name",
            "menu_items.id as menuItemId",
            "menu_items.sellingPrice"
        ])
        ->first();

        $osCount = count(Oslist::all())+1;
        $menuItems = MenuItem::where("status",1)
        ->join('inventorymasterlists as im','im.code','=','menu_items.code')
        ->join('menucategories as cat','cat.id','=','menu_items.menuCategoryId')
        ->whereNotIn("menu_items.code",$inputs["masterlistIDs"])
        ->where("im.id","!=",$inputs["miID"])
        ->select([
            'im.id',
            DB::raw("concat(menu_items.name,' - ',cat.name) as name")
        ])->get();

        $discount= Discount::find($inputs["discountId"]);

        return collect(["menuItems"=>$menuItems,"menuItem"=>$menuItem,"discount"=>$discount,"osCount"=>$osCount]);
    }
    elseif($inputs["action"] == 6){
        $discount = Discount::find($inputs["dId"]);

        return $discount;
    }
    elseif($inputs["action"] == 7){

        $sales = Sale::find($inputs["salesId"]);
        $sales->code= $inputs["orderNo"];
     //   $sales->os_no = $inputs["osNo"] ? $inputs["osNo"] : 0;
        $sales->bpId = $inputs["bp"] ? $inputs["bp"] : 0;
        $sales->tableId = $inputs["table"] ? $inputs["table"] : 0;
        $sales->waiterId = $inputs["waiter"] ? $inputs["waiter"] : 0;
        $sales->status = $inputs["status"] ? $inputs["status"] : 1;
        $sales->priceAfterVAT = $inputs["subtotal"] ? $inputs["subtotal"] : 0;
        $sales->tax = $inputs["tax"] ? $inputs["tax"] : 0;
        $sales->totalReceivables = $inputs["priceAfterVat"] ? $inputs["priceAfterVat"] : 0;
        $sales->totalDiscounts = $inputs["discountAmount"] ? $inputs["discountAmount"] : 0;
        $sales->discountId = $inputs["discountId"] ? $inputs["discountId"] : 0;
        $sales->priceAfterDiscount = $inputs["priceAfterDiscount"] ? $inputs["priceAfterDiscount"] : 0;
        $sales->change = $inputs["change"] ? $inputs["change"] : 0;
        $sales->noOfGuests = $inputs["noOfGuests"] ? $inputs["noOfGuests"] : 1;
        $sales->noOfSpecial = $inputs["noOfSpecial"] ? $inputs["noOfSpecial"] : 0;
        $sales->cashAmount = $inputs["cashAmount"] ? $inputs["cashAmount"] : 0;
        $sales->remarks = $inputs["remarks"] ? $inputs["remarks"] : "";
        $sales->zeroRated = $inputs["zeroRated"] ? $inputs["zeroRated"] : 0;
        $sales->save();

        $masterlistId = $inputs["masterlistId"];
        $soId = $inputs["soId"];
        $qties = $inputs["qty"];
        $prices = $inputs["price"];
        $osId = $inputs["osId"];

        $itemList = '$items = array(';

        for ($i = 0; $i < sizeof($soId); $i++) {

            if($soId[$i] == 0){

                $sd = new SalesDetail;
                $sd->entryDate = date("Y-m-d");
                $sd->discounts = 0;
            }
            else
                $sd = SalesDetail::find($soId[$i]);

            $os = Oslist::where("code",$osId[$i])->first();

            if(!$os){
                $os= new Oslist;
                $os->code = $osId[$i] ? $osId[$i] : 0;
                $os->salesId = $sales->id;
                $os->userId = Auth::user()->id;
                $os->save();
            }

            $sd->osId = $os->id;
            $sd->salesId = $sales->id;
            $sd->inventoryMasterListId = $masterlistId[$i];
            $sd->qty = $qties[$i];
            $sd->discountId = 0;

            $sd->price = $prices[$i];

            $itemName = Inventorymasterlist::join('menu_items as mi','mi.code','=','inventorymasterlists.code')
            ->where("inventorymasterlists.code",$masterlistId[$i])
            ->select([
                "mi.name"
            ])
            ->first();
            $sd->orderName = $itemName->name;
            $sd->userId = Auth::user()->id;
            $sd->save();

            if($sd->status  == 0){
                if($sd->free == 1){
                    $itemList.='new item("'.$sd->orderName.'","","","FREE"),';
                }
                else{
                    $itemList.='new item("'.$sd->orderName.'","'.$sd->qty.'","'.number_format($sd->price,2).'","'.number_format($sd->qty*$sd->price,2).'"),';
                }
                
            }
            
        }

        $itemList.=");";

        $discount = Discount::find($sales->discountId);

        $discountName = "-";
        if($discount)
            $discountName = $discount->name;



        $paymentIds = $inputs["paymentIds"];
        $paymentTypes = $inputs["paymentTypes"];
        $paymentTransactionNo = $inputs["paymentTransactionNo"];
        $paymentAmount = $inputs["paymentAmount"];



        if(sizeof($paymentIds) >= 2){
            for ($j = 1; $j < sizeof($paymentIds); $j++) {

                if($paymentIds[$j] == 0)
                    $pt = new Payment;
                else
                    $pt = Payment::find($paymentIds[$j]);

                $pt->salesId = $sales->id;
                if($paymentTypes[$j] ==1){
                    $pt->name = "CASH";
                    $pt->type = $paymentTypes[$j];
                }
                else if($paymentTypes[$j] == 2){
                    $pt->name = "CARD";
                    $pt->type = $paymentTypes[$j];

                }
                $pt->transactionNo = $paymentTransactionNo[$j];
                $pt->amount = $paymentAmount[$j];
                $pt->userId = Auth::user()->id;
                $pt->save();
                

            }
        }

        $tableList = Table::find($sales->tableId);
        $server = User::find($sales->waiterId);
        $customer = BusinessPartner::find($sales->bpId);

        $zeroRated = "";
        $grandTotal = number_format($sales->priceAfterDiscount,2);

        if($sales->zeroRated == 1){
            $zeroRated = "ZERO RATED SALES";
            $grandTotal = number_format($sales->priceAfterVAT,2);
        }

        

        $orderslips = Oslist::where("salesId",$inputs["salesId"])
        ->select([
            "id",
            "code"
        ])
        ->get();

        $billString = "";

        $billTempCount = 1;
        foreach($orderslips as $ords){


            $billString.=$ords->code.", ";




            $billTempCount++;
        }

        $receiptFile = fopen("bills/bill-".$sales->code.".php","w");

        $txt = '<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        

        '.$itemList.'

        date_default_timezone_set("Asia/Singapore");

        $date = date("l jS \of F Y\nh:i:s A");


        /* Start the printer */
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);


        /* Name of shop */
        $logo = EscposImage::load("resources/qlogo.png", false);


        $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);

        $printer -> text("Q Citipark Hotel\n");

        $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
        $printer -> text("General Santos City\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("BILLING STATEMENT\n");
        $printer -> setEmphasis(false);
        $printer -> feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $invColon = str_pad(":",4," ",STR_PAD_LEFT);
        $invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
        $printer -> text("Bill No".$invColon.$invNo."\n");

        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("'.$server->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("'.$customer->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("'.$tableList->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        $numGuestColon = str_pad(":",4," ",STR_PAD_LEFT);
        $numGuestsNum = str_pad("'.$sales->noOfGuests.'",3," ",STR_PAD_LEFT);
        $printer -> text("Guest/s".$numGuestColon.$numGuestsNum."\n");

        $printer->feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        
        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $totalAmount = str_pad("'.number_format($sales->totalReceivables,2).'",32," ",STR_PAD_LEFT);

        $printer->feed();
        $printer -> text("Subtotal".$totalAmount."\n");

        $printer -> text("DISCOUNT:\n");
        $discount = "'.$discountName.'";
        $discountCols = 40 - strlen($discount);
        $discountAmount = str_pad("'.number_format($sales->totalDiscounts,2).'",$discountCols," ",STR_PAD_LEFT);
        $printer->text($discount.$discountAmount."\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $totalAmount = str_pad("'.$grandTotal.'",29," ",STR_PAD_LEFT);

        $printer->text("'.$zeroRated.'\n");
        $printer -> text("GRAND TOTAL".$totalAmount."\n");
        $printer -> setEmphasis(false);

        $printer->feed();

        $sigColon = str_pad(":",5," ",STR_PAD_LEFT);
        $sigName = str_pad("'.$billString.'",3," ",STR_PAD_LEFT);
        $printer->text("List of OS Nos.".$sigColon.$sigName."\n");

        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Thank you for dining at Q Citipark Hotel\nCome Again!\n");

        // $printer -> feed(2);
        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();

        /* A wrapper to do organise item names & prices into columns */
        class item
        {
            private $name;
            private $price;
            private $dollarSign;
            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
                $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
                return "$left\n$right$price$tp\n";
            }
        }';

        fwrite($receiptFile, $txt);
        fclose($receiptFile);


        
        foreach($orderslips as $os){

           $sales_details = SalesDetail::where("osId",$os->id)
           ->select([
            "qty",
            "orderName",
        ])
           ->get();
           $orderItems = '$orders = array(';

           foreach($sales_details as $sd){
            $orderItems.='new item("","'.$sd->qty.'","","'.$sd->orderName.'"),';
        } 

        $orderItems.=');';

        $orderFile = fopen("orderslips/os-".$os->code.".php","w");

        $txt2 = '<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        '.$orderItems.'

        date_default_timezone_set("Asia/Singapore");

        $date = date("l jS \of F Y\nh:i:s A");


        /* Start the printer */
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);


        /* Name of shop */
        $logo = EscposImage::load("resources/qlogo.png", false);


        $printer -> text("Q Citipark Hotel\n");

        $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
        $printer -> text("General Santos City\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("ORDER SLIP\n");
        $printer -> setEmphasis(false);
        $printer -> setEmphasis(true);
        $printer -> text("'.$os->code.'\n");
        $printer -> setEmphasis(false);
        $printer -> feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $invColon = str_pad(":",4," ",STR_PAD_LEFT);
        $invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
        $printer -> text("Bill No".$invColon.$invNo."\n");


        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("'.$server->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("'.$customer->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("'.$tableList->name.'",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        $printer->feed();

        $qtyText = str_pad("QTY",6," ",STR_PAD_LEFT);
        $itemText = str_pad("ITEM",25," ",STR_PAD_LEFT);
        $printer->text($qtyText.$itemText);

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        foreach ($orders as $item) {
            $printer -> text($item);
        }
        



        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> feed(2);
        $printer -> text($date . "\n");

        /* Cut the receipt and open the cash drawer */
        $printer -> cut();
        $printer -> pulse();

        $printer -> close();

        /* A wrapper to do organise item names & prices into columns */
        class item
        {
            private $name;
            private $price;
            private $dollarSign;

            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
                $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
                return "$left\n$right$price$tp\n";
            }
        }

        /* A wrapper to do organise item names & prices into columns */
        class order
        {
            private $name;
            private $price;
            private $dollarSign;

            public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> qty = $qty;
                $this -> price = $price;
                $this -> totalprice = $totalprice;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 8;
                $leftCols = 20;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_RIGHT);
                $price = str_pad($this->price, 10," ",STR_PAD_RIGHT);
                $tp = str_pad($this->totalprice,22," ",STR_PAD_RIGHT);
                return "$right$price$tp\n";
            }
        }

        ';

        fwrite($orderFile, $txt2);
        fclose($orderFile);
    }


    $salesOrders = SalesDetail::where("sales_details.salesId","=",$sales->id)
    ->join("inventorymasterlists as im","im.code","=","sales_details.inventoryMasterListId")
    ->join("oslist as os","os.id","=","sales_details.osId")
    ->select([
        'sales_details.orderName',
        'sales_details.qty',
        'sales_details.price',
        'sales_details.status',
        'sales_details.id',
        'sales_details.free',
        "os.code as osCode",
        "os.id as osId",
        'im.id as inventoryMasterListId',
        "im.code as imCode",
    ])
    ->orderby("osCode","asc")
    ->get();

    $orderlists = Oslist::where("salesId","=",$sales->id)
    ->select([
        'id',
        'code'
    ])
    ->get();

    $accountType = Auth::user()->type;

    return collect(["sales"=>$sales,"salesOrders"=>$salesOrders,"orderlists"=>$orderlists,"accountType"=>$accountType]);

}
elseif($inputs["action"] == 8){


    $payment = Payment::find($inputs["pId"]);
    $salesId = $payment->salesId;

    $payment->delete();

    $payments = Payment::where("salesId",$salesId)
    ->select([
        "id",
        "name",
        "type",
        DB::raw("if(transactionNo is not null, transactionNo, '') as transactionNo"),
        "amount",
    ])
    ->get();

    return collect(["payments"=>$payments]);
}
elseif($inputs["action"] == 9){

    $sales = Sale::leftjoin("users as u","u.id","=","sales.waiterId")
    ->leftjoin("business_partners as bpCustomer","bpCustomer.id","=","sales.bpId")
    ->leftjoin("discounts as d","d.id","=","sales.discountId")
    ->where("sales.id","=",$inputs["salesId"])
    ->select([
        "sales.code",
        "u.name as waiterName",
        "bpCustomer.name as customerName",
        "sales.totalReceivables",
        "sales.status",
        'sales.totalDiscounts',
        'sales.tax',
        'sales.noOfGuests',
        'sales.noOfSpecial',
        'sales.discountId',
        'd.name as discountName',
        'sales.priceAfterDiscount',
        'sales.priceAfterVAT',
        'sales.waiterId',
        'sales.bpId',
        'sales.change',
        'sales.cashAmount',
        'sales.tableId',
        'sales.priceAfterVAT',
        'sales.zeroRated',

    ])
    ->first();    

    $table= Table::find($sales->tableId);

    $salesD = SalesDetail::join("menu_items as mi","mi.code","=","sales_details.inventoryMasterListId")
    ->where("salesId",$inputs["salesId"])
    ->select([
        "sales_details.inventoryMasterlistId",
        "mi.name as itemName",
        "sales_details.qty",
        "sales_details.price",
        "sales_details.orderName",
    ])->get();


    $itemList = '$items = array(';


    foreach($salesD as $sd){
        if($sd->status  == 0){
            if($sd->free == 1){
                $itemList.='new item("'.$sd->orderName.'","","","FREE"),';
            }
            else{
                $itemList.='new item("'.$sd->orderName.'","'.$sd->qty.'","'.number_format($sd->price,2).'","'.number_format($sd->qty*$sd->price,2).'"),';
            }

        }
    }

    foreach($salesD as $sd2){
        $invSd = InvMi::where("menuItemId","=",$sd2->inventoryMasterlistId)
        ->select([
            "inv_mi.invId",
            "inv_mi.qty"
        ])
        ->get();


        foreach($invSd as $invS){

            $invTemp = Inventory::where("code","=",$invS->invId)
            ->select([
                "qty",
            ])
            ->first();

            Inventory::where("code","=",$invS->invId)
            ->update(["qty" => $invTemp->qty - ($invS->qty * $sd2->qty)]);



        }
    }


    $itemList.=");";





    $paymentList = '$paymentList = array(';

    $payments = Payment::where("salesId",$inputs["salesId"])
    ->select([
        "name",
        "transactionNo",
        "amount",
    ]
)
    ->get();

    $tempPaymentCount = 1;

    foreach($payments as $pt){

       
        $paymentList.='new item("'.$tempPaymentCount.' - '.$pt->name.'","'.$pt->transactionNo.' - '.number_format($pt->amount,2).'","",""),';
        $tempPaymentCount++;
    }

    $paymentList.=");";

    $tableList = Table::find($sales->tableId);
    $server = User::find($sales->waiterId);
    $customer = BusinessPartner::find($sales->bpId);

    $zeroRated = "";
    $grandTotal = number_format($sales->priceAfterDiscount,2);
    $taxAmount = number_format($sales->tax,2);
    $vatSalesAmount = number_format($sales->priceAfterVAT,2);
    $zeroRatedAmount = "0.00";


    if($sales->zeroRated == 1){
        $zeroRated = "ZERO RATED SALES";
        $grandTotal = number_format($sales->priceAfterVAT,2);

        $taxAmount = "0.00";
        $vatSalesAmount = "0.00";
        $zeroRatedAmount = number_format($sales->priceAfterVAT,2);
    }



    $orderslips = Oslist::where("salesId",$inputs["salesId"])
    ->select([
        "id",
        "code"
    ])
    ->get();

    $billString = "";

    $billTempCount = 1;
    foreach($orderslips as $ords){


        $billString.=$ords->code.", ";




        $billTempCount++;
    }


    $receiptFile = fopen("receipts/receipt-".$sales->code.".php","w");

    $txt = '<?php
    require __DIR__ . "/mike42/escpos-php/autoload.php";
    use Mike42\Escpos\Printer;
    use Mike42\Escpos\EscposImage;
    use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


    /* Fill in your own connector here */
    $connector = new NetworkPrintConnector("10.10.1.252", 9100);

    /* Information for the receipt */
    '.$itemList.'

    date_default_timezone_set("Asia/Singapore");

    $date = date("l jS \of F Y\nh:i:s A");


    /* Start the printer */
    $printer = new Printer($connector);

    /* Print top logo */
    $printer -> setJustification(Printer::JUSTIFY_CENTER);


    /* Name of shop */
    $logo = EscposImage::load("resources/qlogo.png", false);


    $printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);

    $printer -> text("Q Citipark Hotel\n");

    $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
    $printer -> text("General Santos City\n");
    $printer -> feed();

    /* Title of receipt */
    $printer -> setEmphasis(true);
    $printer -> text("THIS IS NOT AN OFFICIAL RECEIPT\n");
    $printer -> setEmphasis(false);
    $printer -> feed();
    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $invColon = str_pad(":",4," ",STR_PAD_LEFT);
    $invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
    $printer -> text("Bill No".$invColon.$invNo."\n");

    $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
    $serverName = str_pad("'.$server->name.'",3," ",STR_PAD_LEFT);
    $printer -> text("Server".$serverColon.$serverName."\n");

    $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
    $customerName = str_pad("'.$customer->name.'",3," ",STR_PAD_LEFT);
    $printer -> text("Customer".$customerColon.$customerName."\n");

    $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
    $tableName = str_pad("'.$tableList->name.'",3," ",STR_PAD_LEFT);
    $printer -> text("Table".$tableColon.$tableName."\n");

    $numGuestColon = str_pad(":",4," ",STR_PAD_LEFT);
    $numGuestsNum = str_pad("'.$sales->noOfGuests.'",3," ",STR_PAD_LEFT);
    $printer -> text("Guest/s".$numGuestColon.$numGuestsNum."\n");

    $statusColon = str_pad(":",5," ",STR_PAD_LEFT);
    $statusName = str_pad("Settled",3," ",STR_PAD_LEFT);
    $printer->text("Status".$statusColon.$statusName."\n");

    /* Items */

    $printer -> feed();

    $printer -> setEmphasis(false);
    foreach ($items as $item) {
        $printer -> text($item);
    }

    $printer -> setJustification(Printer::JUSTIFY_LEFT);

    $totalAmount = str_pad("'.number_format($sales->totalReceivables,2).'",32," ",STR_PAD_LEFT);

    $printer->feed();
    $printer -> text("Subtotal".$totalAmount."\n");


    $printer -> text("DISCOUNT:\n");
    $discount = "'.$sales->discountName.'";
    $discountCols = 40 - strlen($discount);
    $discountAmount = str_pad("'.number_format($sales->totalDiscounts,2).'",$discountCols," ",STR_PAD_LEFT);
    $printer->text($discount.$discountAmount."\n");

    $printer -> setJustification(Printer::JUSTIFY_LEFT);
    $printer -> setEmphasis(true);
    $totalAmount = str_pad("'.$grandTotal.'",29," ",STR_PAD_LEFT);

    $printer->text("'.$zeroRated.'\n");
    $printer -> text("GRAND TOTAL".$totalAmount."\n");
    $printer -> setEmphasis(false);

    $printer->feed();

    $sigColon = str_pad(":",5," ",STR_PAD_LEFT);
    $sigName = str_pad("'.$billString.'",3," ",STR_PAD_LEFT);
    $printer->text("List of OS Nos.".$sigColon.$sigName."\n");


    $printer->feed();
    $printer -> text("Payment Type(s):\n");

    '.$paymentList.'

    foreach ($paymentList as $pi){
        $printer->text($pi);
    }

    $totalAmount = str_pad("'.number_format($sales->cashAmount,2).'",26," ",STR_PAD_LEFT);
    $printer -> text("TOTAL PAYMENTS".$totalAmount."\n");
    $totalAmount = str_pad("'.number_format($sales->change,2).'",34," ",STR_PAD_LEFT);
    $printer -> text("CHANGE".$totalAmount."\n");
    $printer -> feed(2);

    $colonVatSales = str_pad(":",11," ",STR_PAD_LEFT);
    $vatSalesAmount = str_pad("'.$vatSalesAmount.'",8," ",STR_PAD_LEFT);
    $printer -> text("VAT Sales".$colonVatSales.$vatSalesAmount."\n");

    $colonVatAmount = str_pad(":",6," ",STR_PAD_LEFT);
    $vatAmount = str_pad("'.$taxAmount.'",8," ",STR_PAD_LEFT);
    $printer -> text("VAT Amount 12%".$colonVatAmount.$vatAmount."\n");

    $colonVatExemptSales = str_pad(":",4," ",STR_PAD_LEFT);
    $vatExemptSalesAmount = str_pad("0.00",8," ",STR_PAD_LEFT);
    $printer -> text("VAT Exempt Sales".$colonVatExemptSales.$vatExemptSalesAmount."\n");

    $zeroRatedSalesColon = str_pad(":",4," ",STR_PAD_LEFT);
    $zeroRatedSalesAmount = str_pad("'.$zeroRatedAmount.'",8," ",STR_PAD_LEFT);
    $printer -> text("Zero Rated Sales".$zeroRatedSalesColon.$zeroRatedSalesAmount."\n");

    $printer->feed();

    $statusColon = str_pad(":",7," ",STR_PAD_LEFT);
    $statusName = str_pad("__________________",3," ",STR_PAD_LEFT);
    $printer->text("Room No.".$statusColon.$statusName."\n");

    $guestColon = str_pad(":",9," ",STR_PAD_LEFT);
    $guestName = str_pad("__________________",3," ",STR_PAD_LEFT);
    $printer->text("Guest.".$guestColon.$guestName."\n");

    $sigColon = str_pad(":",5," ",STR_PAD_LEFT);
    $sigName = str_pad("__________________",3," ",STR_PAD_LEFT);
    $printer->text("Signature.".$sigColon.$sigName."\n");

    /* Footer */
    $printer -> feed(2);
    $printer -> setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("Thank you for dining at Q Citipark Hotel\nCome Again!\n");

        // $printer -> feed(2);
    $printer -> text($date . "\n");

    /* Cut the receipt and open the cash drawer */
    $printer -> cut();
    $printer -> pulse();

    $printer -> close();

    /* A wrapper to do organise item names & prices into columns */
    class item
    {
        private $name;
        private $price;
        private $dollarSign;

        public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
        {
            $this -> name = $name;
            $this -> qty = $qty;
            $this -> price = $price;
            $this -> totalprice = $totalprice;
            $this -> dollarSign = $dollarSign;
        }

        public function __toString()
        {
            $rightCols = 8;
            $leftCols = 20;
            if ($this -> dollarSign) {
                $leftCols = $leftCols / 2 - $rightCols / 2;
            }
            $left = str_pad($this -> name, $leftCols) ;

            $sign = ($this -> dollarSign ? "$ " : "");
            $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
            $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
            $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
            return "$left\n$right$price$tp\n";
        }
    }';

    fwrite($receiptFile, $txt);
    fclose($receiptFile);

    $salesTwo = Sale::find($inputs["salesId"]);
    $salesTwo->status = 2;
    $salesTwo->save();
    return $salesTwo;
}
elseif($inputs["action"] == 10){
   $sales = Sale::leftjoin("users as u","u.id","=","sales.waiterId")
   ->leftjoin("business_partners as bpCustomer","bpCustomer.id","=","sales.bpId")
   ->leftjoin("discounts as d","d.id","=","sales.discountId")
   ->where("sales.id","=",$inputs["salesId"])
   ->select([
    "sales.code",
    "u.name as waiterName",
    "bpCustomer.name as customerName",
    "sales.totalReceivables",
    "sales.status",
    'sales.totalDiscounts',
    'sales.tax',
    'sales.noOfGuests',
    'sales.noOfSpecial',
    'sales.discountId',
    'd.name as discountName',
    'sales.priceAfterDiscount',
    'sales.priceAfterVAT',
    'sales.change',
    'sales.cashAmount',
    'sales.priceAfterVAT',
    'sales.tableId',

])
   ->first();    

   $table = Table::find($sales->tableId);

   $salesD = SalesDetail::join("menu_items as mi","mi.code","=","sales_details.inventoryMasterListId")
   ->where("salesId",$inputs["salesId"])
   ->select([
    "sales_details.inventoryMasterlistId",
    "mi.name as itemName",
    "sales_details.qty",
    "sales_details.price",
    "sales_details.orderName",
])->get();


   $itemList = '$items = array(';


   foreach($salesD as $sd){
    if($sd->status  == 0){
        if($sd->free == 1){
            $itemList.='new item("'.$sd->orderName.'","","","FREE"),';
        }
        else{
            $itemList.='new item("'.$sd->orderName.'","'.$sd->qty.'","'.number_format($sd->price,2).'","'.number_format($sd->qty*$sd->price,2).'"),';
        }

    }
}

        // foreach($salesD as $sd2){
        //     $invSd = InvMi::where("menuItemId","=",$sd2->inventoryMasterlistId)
        //                 ->select([
        //                     "inv_mi.invId",
        //                     "inv_mi.qty"
        //                 ])
        //                 ->get();


        //     foreach($invSd as $invS){

        //         $invTemp = Inventory::where("code","=",$invS->invId)
        //                             ->select([
        //                                 "qty",
        //                             ])
        //                             ->first();

        //         Inventory::where("code","=",$invS->invId)
        //                             ->update(["qty" => $invTemp->qty + ($invS->qty * $sd2->qty)]);



        //     }
        // }


$itemList.=");";





$paymentList = '$paymentList = array(';

$payments = Payment::where("salesId",$inputs["salesId"])
->select([
    "name",
    "transactionNo",
    "amount",
]
)
->get();

$tempPaymentCount = 1;

foreach($payments as $pt){

    $paymentList.='new item("'.$tempPaymentCount.'" - "'.$pt->name.'","'.$pt->transactionNo.'" - "'.number_format($pt->amount,2).'","",""),';
    $tempPaymentCount++;
}

$paymentList.=");";


$receiptFile = fopen("receipts/receipt-".$sales->code.".php","w");

$txt = '<?php
require __DIR__ . "/mike42/escpos-php/autoload.php";
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


/* Fill in your own connector here */
$connector = new NetworkPrintConnector("10.10.1.252", 9100);

/* Information for the receipt */
'.$itemList.'

date_default_timezone_set("Asia/Singapore");

$date = date("l jS \of F Y\nh:i:s A");


/* Start the printer */
$printer = new Printer($connector);

/* Print top logo */
$printer -> setJustification(Printer::JUSTIFY_CENTER);


/* Name of shop */
$logo = EscposImage::load("resources/qlogo.png", false);


$printer -> bitImageColumnFormat($logo, Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT);

$printer -> text("Q Citipark Hotel\n");

$printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
$printer -> text("General Santos City\n");
$printer -> feed();

/* Title of receipt */
$printer -> setEmphasis(true);
$printer -> text("THIS IS NOT AN OFFICIAL RECEIPT\n");
$printer -> setEmphasis(false);
$printer -> feed();

$printer -> setJustification(Printer::JUSTIFY_LEFT);
$invColon = str_pad(":",4," ",STR_PAD_LEFT);
$invNo = str_pad("'.$sales->code.'",3," ",STR_PAD_LEFT);
$printer -> text("Inv. No".$invColon.$invNo."\n");

$serverColon = str_pad(":",5," ",STR_PAD_LEFT);
$serverName = str_pad("'.$sales->waiterName.'",3," ",STR_PAD_LEFT);
$printer -> text("Server".$serverColon.$serverName."\n");

$customerColon = str_pad(":",3," ",STR_PAD_LEFT);
$customerName = str_pad("'.$sales->customerName.'",3," ",STR_PAD_LEFT);
$printer -> text("Customer".$customerColon.$customerName."\n");

$statusColon = str_pad(":",5," ",STR_PAD_LEFT);
$statusName = str_pad("Void",3," ",STR_PAD_LEFT);
$printer->text("Status".$statusColon.$statusName."\n");

$tableColon = str_pad(":",6," ",STR_PAD_LEFT);
$tableName = str_pad("'.$table->name.'",3," ",STR_PAD_LEFT);
$printer -> text("Table".$tableColon.$tableName."\n");

/* Items */

$printer -> feed();

$printer -> setEmphasis(false);
foreach ($items as $item) {
    $printer -> text($item);
}

$printer -> setJustification(Printer::JUSTIFY_LEFT);

$totalAmount = str_pad("'.number_format($sales->totalReceivables,2).'",32," ",STR_PAD_LEFT);

$printer->feed();
$printer -> text("Subtotal".$totalAmount."\n");


$printer -> text("DISCOUNT:\n");
$discount = "'.$sales->discountName.'";
$discountCols = 40 - strlen($discount);
$discountAmount = str_pad("'.number_format($sales->totalDiscounts,2).'",$discountCols," ",STR_PAD_LEFT);
$printer->text($discount.$discountAmount."\n");

$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);
$totalAmount = str_pad("'.number_format($sales->priceAfterDiscount,2).'",29," ",STR_PAD_LEFT);

$printer -> text("GRAND TOTAL".$totalAmount."\n");
$printer -> setEmphasis(false);
$printer->feed();
$printer -> text("Payment Type(s):\n");

'.$paymentList.'

foreach ($paymentItems as $pi){
    $printer->text($pi);
}

$totalAmount = str_pad("'.number_format($sales->cashAmount,2).'",26," ",STR_PAD_LEFT);
$printer -> text("TOTAL PAYMENTS".$totalAmount."\n");
$totalAmount = str_pad("'.number_format($sales->change,2).'",34," ",STR_PAD_LEFT);
$printer -> text("CHANGE".$totalAmount."\n");
$printer -> feed(2);

$colonVatSales = str_pad(":",11," ",STR_PAD_LEFT);
$vatSalesAmount = str_pad("'.number_format($sales->priceAfterVAT,2).'",8," ",STR_PAD_LEFT);
$printer -> text("VAT Sales".$colonVatSales.$vatSalesAmount."\n");

$colonVatAmount = str_pad(":",6," ",STR_PAD_LEFT);
$vatAmount = str_pad("'.number_format($sales->tax,2).'",8," ",STR_PAD_LEFT);
$printer -> text("VAT Amount 12%".$colonVatAmount.$vatAmount."\n");

$colonVatExemptSales = str_pad(":",4," ",STR_PAD_LEFT);
$vatExemptSalesAmount = str_pad("0.00",8," ",STR_PAD_LEFT);
$printer -> text("VAT Exempt Sales".$colonVatExemptSales.$vatExemptSalesAmount."\n");

$zeroRatedSalesColon = str_pad(":",4," ",STR_PAD_LEFT);
$zeroRatedSalesAmount = str_pad("0.00",8," ",STR_PAD_LEFT);
$printer -> text("Zero Rated Sales".$zeroRatedSalesColon.$zeroRatedSalesAmount."\n");

$printer->feed();

$statusColon = str_pad(":",7," ",STR_PAD_LEFT);
$statusName = str_pad("__________________",3," ",STR_PAD_LEFT);
$printer->text("Room No.".$statusColon.$statusName."\n");

$guestColon = str_pad(":",9," ",STR_PAD_LEFT);
$guestName = str_pad("__________________",3," ",STR_PAD_LEFT);
$printer->text("Guest.".$guestColon.$guestName."\n");

$sigColon = str_pad(":",5," ",STR_PAD_LEFT);
$sigName = str_pad("__________________",3," ",STR_PAD_LEFT);
$printer->text("Signature.".$sigColon.$sigName."\n");

/* Footer */
$printer -> feed(2);
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> text("Thank you for dining at Q Citipark Hotel\nCome Again!\n");
$printer -> text("THIS IS NOT AN OFFICIAL RECEIPT\n");
        // $printer -> feed(2);
$printer -> text($date . "\n");

/* Cut the receipt and open the cash drawer */
$printer -> cut();
$printer -> pulse();

$printer -> close();

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = "", $qty = "", $price = "", $totalprice = "", $dollarSign = false)
    {
        $this -> name = $name;
        $this -> qty = $qty;
        $this -> price = $price;
        $this -> totalprice = $totalprice;
        $this -> dollarSign = $dollarSign;
    }

    public function __toString()
    {
        $rightCols = 8;
        $leftCols = 20;
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this -> name, $leftCols) ;

        $sign = ($this -> dollarSign ? "$ " : "");
        $right = str_pad($sign . $this -> qty, $rightCols, " ", STR_PAD_LEFT);
        $price = str_pad($this->price, 10," ",STR_PAD_LEFT);
        $tp = str_pad($this->totalprice,22," ",STR_PAD_LEFT);
        return "$left\n$right$price$tp\n";
    }
}';

fwrite($receiptFile, $txt);
fclose($receiptFile);

$salesTwo = Sale::find($inputs["salesId"]);
$salesTwo->status = 3;
$salesTwo->save();
return $salesTwo;
}

}

public function dataTablesOrderList($dateStart, $dateEnd)
{
    $sales = Sale::leftjoin('tables as t','t.id','=','sales.tableId')
    ->leftjoin('business_partners as bp','bp.id','=','sales.bpId')
    ->leftjoin('discounts as d','d.id','=','sales.discountId')
    ->whereDate('sales.entryDate','>=',$dateStart)
    ->whereDate('sales.entryDate','<=',$dateEnd)           
    ->select([
        'sales.id',
        DB::raw("DATE_FORMAT(sales.entryDate,'%d-%b-%y') as date"),
        'sales.code as orderNo',
        DB::raw('if(t.id is not null,concat(bp.name," / ",t.name),bp.name) as customer'),
        DB::raw('if(d.id is not null,concat(d.name," / ",d.discountValue * 100,"%"),"-") as discount'),
        DB::raw("format(sales.priceAfterDiscount,2) as totalPrice"),
        'sales.status',
        DB::raw('
            case sales.status
            when "1" then "Open"
            when "2" then "Settled"
            when "3" then "Void"
            end as statusName')
    ])
    ->get();

    return collect(['data' => $sales]);
}

public function printBillView($id){

    $sales = Sale::join("business_partners as bp","bp.id","=","sales.bpId")
    ->where("sales.id",$id)
    ->select([
        "bp.name",
        "sales.code",
    ])
    ->first();

    return redirect('/bills/bill-'.$sales->code.'.php');
}

public function printOSView($id){
    $os = Oslist::where("id",$id)
    ->select([
        "code",
    ])
    ->first();

    return redirect('/orderslips/os-'.$os->code.'.php');
}

public function printReceiptView($id){

    $sales = Sale::join("business_partners as bp","bp.id","=","sales.bpId")
    ->where("sales.id",$id)
    ->select([
        "bp.name",
        "sales.code",
    ])
    ->first();

    return redirect('/receipts/receipt-'.$sales->code.'.php');
}
}
