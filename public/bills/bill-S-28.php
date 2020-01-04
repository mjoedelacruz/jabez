<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        

        $items = array(new item("CLUB SANDWICH","1","198.00","198.00"),new item("BACON, LETTUCE & TOMATO","2","136.00","272.00"),new item("OREO MILK SHAKE","1","115.00","115.00"),new item("JAVA CHIP","1","150.00","150.00"),new item("CALAMARI","1","210.00","210.00"),new item("EBI TEMPURA","1","230.00","230.00"),);

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
        $invNo = str_pad("S-28",3," ",STR_PAD_LEFT);
        $printer -> text("Bill No".$invColon.$invNo."\n");

        $serverColon = str_pad(":",5," ",STR_PAD_LEFT);
        $serverName = str_pad("Johnmark Cañete",3," ",STR_PAD_LEFT);
        $printer -> text("Server".$serverColon.$serverName."\n");

        $customerColon = str_pad(":",3," ",STR_PAD_LEFT);
        $customerName = str_pad("WALK-IN",3," ",STR_PAD_LEFT);
        $printer -> text("Customer".$customerColon.$customerName."\n");

        $tableColon = str_pad(":",6," ",STR_PAD_LEFT);
        $tableName = str_pad("C-1",3," ",STR_PAD_LEFT);
        $printer -> text("Table".$tableColon.$tableName."\n");

        $numGuestColon = str_pad(":",4," ",STR_PAD_LEFT);
        $numGuestsNum = str_pad("5",3," ",STR_PAD_LEFT);
        $printer -> text("Guest/s".$numGuestColon.$numGuestsNum."\n");

        $printer->feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        
        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $totalAmount = str_pad("1,175.00",32," ",STR_PAD_LEFT);

        $printer->feed();
        $printer -> text("Subtotal".$totalAmount."\n");

        $printer -> text("DISCOUNT:\n");
        $discount = "Senior Citizen 20%";
        $discountCols = 40 - strlen($discount);
        $discountAmount = str_pad("125.89",$discountCols," ",STR_PAD_LEFT);
        $printer->text($discount.$discountAmount."\n");

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);
        $totalAmount = str_pad("1,049.11",29," ",STR_PAD_LEFT);

        $printer->text("\n");
        $printer -> text("GRAND TOTAL".$totalAmount."\n");
        $printer -> setEmphasis(false);

        $printer->feed();

        $sigColon = str_pad(":",5," ",STR_PAD_LEFT);
        $sigName = str_pad("OS-24, OS-25, ",3," ",STR_PAD_LEFT);
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
        }