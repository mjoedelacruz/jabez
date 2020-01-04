<?php
        require __DIR__ . "/mike42/escpos-php/autoload.php";
        use Mike42\Escpos\Printer;
        use Mike42\Escpos\EscposImage;
        use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;


        /* Fill in your own connector here */
        $connector = new NetworkPrintConnector("192.168.192.168", 9100);

        /* Information for the receipt */
        $items = array(new item("FOOD 1","2"),);
        $subtotal = new item("Subtotal", "90.00");
      

        $date = date("l jS \of F Y h:i:s A");


        /* Start the printer */
        $printer = new Printer($connector);

        /* Print top logo */
        $printer -> setJustification(Printer::JUSTIFY_CENTER);


        /* Name of shop */
       
        $printer -> text("Q Citipark Hotel\n");
      
        $printer -> text("Roxas Avenue Corner J.P Laurel\nBrgy. East\n");
        $printer -> text("General Santos City\n");
        $printer -> feed();

        /* Title of receipt */
        $printer -> setEmphasis(true);
        $printer -> text("BILLING STATEMENT\n");
        $printer -> setEmphasis(false);

        /* Items */
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> setEmphasis(true);

        $printer -> setEmphasis(false);
        foreach ($items as $item) {
            $printer -> text($item);
        }
        $printer -> setEmphasis(true);
        $printer -> text($subtotal);
        $printer -> setEmphasis(false);
        $printer -> feed();

    

        /* Footer */
        $printer -> feed(2);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("Thank you for dining at Q Citipark Hotel.\nCome Again!\n");
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

            public function __construct($name = "", $price = "", $dollarSign = false)
            {
                $this -> name = $name;
                $this -> price = $price;
                $this -> dollarSign = $dollarSign;
            }

            public function __toString()
            {
                $rightCols = 10;
                $leftCols = 38;
                if ($this -> dollarSign) {
                    $leftCols = $leftCols / 2 - $rightCols / 2;
                }
                $left = str_pad($this -> name, $leftCols) ;

                $sign = ($this -> dollarSign ? "$ " : "");
                $right = str_pad($sign . $this -> price, $rightCols, " ", STR_PAD_LEFT);
                return "$left$right\n";
            }
        }