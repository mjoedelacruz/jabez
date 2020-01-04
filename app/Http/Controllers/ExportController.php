<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportInvData($dateStart,$dateEnd) 
	{
		return new \App\Exports\InventoryExport($dateStart,$dateEnd);
	}
	public function exportGoodsEntry($dateStart,$dateEnd,$bp) 
	{
		return new \App\Exports\GoodsEntryExport($dateStart,$dateEnd,$bp);
	}
	public function exportGoodsEntryDetails($dateStart,$dateEnd,$bp) 
	{
		return new \App\Exports\GoodsEntryDetailsExport($dateStart,$dateEnd,$bp);
	}
	public function exportSalesData($dateStart,$dateEnd,$bp) 
	{
		return new \App\Exports\SalesDataExport($dateStart,$dateEnd,$bp);
	}
}
