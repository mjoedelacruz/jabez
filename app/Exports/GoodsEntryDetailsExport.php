<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Sales;
use App\Models\GoodsEntry;
use App\Models\GoodsEntryDetail;
use App\Models\Inventory;
use App\Models\SalesDetails;
use App\Models\BusinessPartners;
use App\Models\Uom;
use DB;

class GoodsEntryDetailsExport implements FromQuery, Responsable, WithHeadings
{
	
    use Exportable;
    //private $date = date('Y-m-d');
    private $fileName = 'Goods_Entry_Details.xlsx';

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

        return  $ged = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
        ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
        ->join('users','users.id','=','ge.userId')
        ->whereBetween(DB::raw("DATE_FORMAT(goods_entry_details.updated_at,'%Y-%m-%d')"), array($this->dateStart, $this->dateEnd))
        ->select([
            'goods_entry_details.goodsEntryId as goodsId',
            'goods_entry_details.itemName',
            'goods_entry_details.qty',
            'goods_entry_details.price',
            'goods_entry_details.uom',
            DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
            'bp.name as bp',
            'users.name as uname',
            (DB::raw("DATE_FORMAT(goods_entry_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),

        ]); 
    }
    else
    {
      return  $ged = GoodsEntryDetail::join('goods_entry as ge','ge.id','=','goods_entry_details.goodsEntryId')
        ->join('business_partners as bp','bp.bpCode','=','ge.bpId')
        ->join('users','users.id','=','ge.userId')
        ->whereBetween(DB::raw("DATE_FORMAT(goods_entry_details.updated_at,'%Y-%m-%d')"), array($this->dateStart, $this->dateEnd))
        ->where('bp.bpCode','=',$this->bp)
        ->select([
            'goods_entry_details.goodsEntryId as goodsId',
            'bp.name as bp',
            'users.name as uname',
            'goods_entry_details.itemName',
            'goods_entry_details.qty',
            'goods_entry_details.uom',
            'goods_entry_details.price',
             DB::raw('format(goods_entry_details.qty*goods_entry_details.price,2) as pay'),
            (DB::raw("DATE_FORMAT(goods_entry_details.created_at,'%Y-%m-%d | %H:%i:%S') as Date")),

        ]);   
    }



}

public function headings(): array
{

    return [
        'ID',
        'SUPPLIER',
        'USER',
        'ITEM',
        'QTY',
        'UOM ',
        'PRICE',
        'TOTAL',
        'DATE',

    ];

}
}