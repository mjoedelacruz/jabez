<?php require __DIR__ . "/../../autoload.php";
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;try {
    $connector = new NetworkPrintConnector("192.168.192.168", 9100);
    
    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    $printer -> text("Hello World!\n");
    $printer -> cut();
    
    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "Couldnt print to this printer: ";
}