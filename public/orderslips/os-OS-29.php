<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("10.10.1.252", 9100);

        /* Information for the receipt */
        $orders = array(new item("","1","","CALAMARI"),new item("","1","","PEANUTS"),);

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
        $printer -> text("OS-29\n");
        $printer -> setEmphasis(false);
        $printer -> feed();

        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $invColon = str_pad(":",4," ",STR_PAD_LEFT);
        $invNo = str_pad("S-2",3," ",STR_PAD_LEFT);
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

        