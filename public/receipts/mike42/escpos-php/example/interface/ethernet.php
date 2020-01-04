<?php
/* Change to the correct path if you copy this example! */


$myfile = fopen("newfile.php", "w") or die("Unable to open file!");
$txt = '<?php require __DIR__ . "/../../autoload.php";
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
}';
fwrite($myfile, $txt);

fclose($myfile);

/* Most printers are open on port 9100, so you just need to know the IP 
 * address of your receipt printer, and then fsockopen() it on that port.
 */

  