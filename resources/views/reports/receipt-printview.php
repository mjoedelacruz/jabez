<?php
/* Change to the correct path if you copy this example! */

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

/* Most printers are open on port 9100, so you just need to know the IP 
 * address of your receipt printer, and then fsockopen() it on that port.
 */
try {
    $connector = new NetworkPrintConnector("192.168.192.168", 9100);
    
    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    $printer -> text($sales->name);
    $printer -> cut();
    
    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
}