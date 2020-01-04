<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Sales;
use App\Models\Inventory;
use App\Models\SalesDetails;
use App\Models\BusinessPartners;
use App\Models\Uom;
use DB;

class InventoryExport implements FromQuery, Responsable, WithHeadings
{
	
    use Exportable;
    //private $date = date('Y-m-d');
    private $fileName = 'Inventory.xlsx';

    public function __construct($dateStart,$dateEnd)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }
      

    public function query()
    {
        return Inventory::join('invcategories as cat','cat.id','=','inventory.invCategoryId')
        ->join('uoms as uom','uom.id','=','inventory.uomId')
        ->orderBy('inventory.updated_at', 'asc')
        ->whereBetween(DB::raw("DATE_FORMAT(inventory.updated_at,'%Y-%m-%d')"), array( $this->dateStart, $this->dateEnd))
        ->select([
            'inventory.code',
            'inventory.name',
            'cat.name as category',
            DB::raw("format(inventory.qty,2) as Quantity"),
            'uom.name as uname',
            (DB::raw("DATE_FORMAT(inventory.updated_at,'%Y-%m-%d') as Date")),
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'NAME',
            'CATEGORY',
            'QTY',
            'UNIT',
            'DATE',
            
        ];
    }
}