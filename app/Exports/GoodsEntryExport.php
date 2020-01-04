<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Sales;
use App\Models\GoodsEntry;
use App\Models\Inventory;
use App\Models\SalesDetails;
use App\Models\BusinessPartners;
use App\Models\Uom;
use DB;

class GoodsEntryExport implements FromQuery, Responsable, WithHeadings
{
	
    use Exportable;
    //private $date = date('Y-m-d');
    private $fileName = 'Goods_Entry.xlsx';

    public function __construct($dateStart,$dateEnd,$bp)
    {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
        $this->bp = $bp;
    }


    public function query()
    {
     if($this->bp == 'ALL')
     {

        return  $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
        ->whereBetween(DB::raw("DATE_FORMAT(goods_entry.updated_at,'%Y-%m-%d')"), array($this->dateStart, $this->dateEnd))
        //->where('goods_entry.bpId','=',$bp)
        ->select([
            'goods_entry.id as id',
            'bp.name as bp',
            'users.name as user',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
            
            
        ]); 
    }
    else
    {
      return  $goodsList = GoodsEntry::join('users','users.id','=','goods_entry.userId')
        ->join('business_partners as bp','bp.bpCode','=','goods_entry.bpId')
        ->whereBetween(DB::raw("DATE_FORMAT(goods_entry.updated_at,'%Y-%m-%d')"), array($this->dateStart, $this->dateEnd))
        ->where('goods_entry.bpId','=',$this->bp)
        ->select([
            'goods_entry.id as id',
            'bp.name as bp',
            'users.name as user',
            DB::raw('format(goods_entry.totalPayables,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),
            
            
        ]);   
    }



}

public function headings(): array
{

    return [
        'ID',
        'SUPPLIER',
        'USER',
        'AMOUNT',
        'DATE',

    ];

}
}