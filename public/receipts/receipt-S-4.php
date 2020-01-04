<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        $items = array(new item("CALAMARI","3","210.00","630.00"),new item("EBI TEMPURA","1","230.00","230.00"),);

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
        $invNo = str_pad("S-4",3," ",STR_PAD_LEFT);
        $printer -> text("Inv. No".$invColon.$invNo."\n");

        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("WALK-IN",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $statusColon = str_pad(":",5," ",STR_PAD_LEFT);
        $statusName = str_pad("Void",3," ",STR_PAD_LEFT);
        $printer->text("Status".$statusColon.$statusName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("C-1",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        /* Items */
        
        $printer -> feed();

        $printer -> setEmphasis(false);
        foreach ($items as $item) {
            $printer -> text($item);
        }

        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $totalAmount = str_pad("0.00",32," ",STR_PAD_LEFT);

        $printer->feed();
        $printer -> text("Subtotal".$totalAmount."\n");


        $printer -> text("DISCOUNT:\n");
        $discount = "Senior Citizen 20%";
        $discountCols = 40 - strlen($discount);
        $discountAmount = str_pad("0.00",$discountCols," ",STR_PAD_LEFT);
        $printer->text($discount.$discountAmount."\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $totalAmount = str_pad("0.00",29," ",STR_PAD_LEFT);

        $printer -> text("GRAND TOTAL".$totalAmount."\n");
        $printer -> setEmphasis(false);
        $printer->feed();
        $printer -> text("Payment Type(s):\n");

        $paymentList = array(new item("1" - "CASH","" - "350.00","",""),);

        foreach ($paymentItems as $pi){
            $printer->text($pi);
        }

        $totalAmount = str_pad("350.00",26," ",STR_PAD_LEFT);
        $printer -> text("TOTAL PAYMENTS".$totalAmount."\n");
        $totalAmount = str_pad("350.00",34," ",STR_PAD_LEFT);
        $printer -> text("CHANGE".$totalAmount."\n");
        $printer -> feed(2);

        $colonVatSales = str_pad(":",11," ",STR_PAD_LEFT);
        $vatSalesAmount = str_pad("0.00",8," ",STR_PAD_LEFT);
        $printer -> text("VAT Sales".$colonVatSales.$vatSalesAmount."\n");

        $colonVatAmount = str_pad(":",6," ",STR_PAD_LEFT);
        $vatAmount = str_pad("0.00",8," ",STR_PAD_LEFT);
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
        }