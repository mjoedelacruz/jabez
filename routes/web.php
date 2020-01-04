<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/logout', function () {
	return view('auth.login');
});

Auth::routes();
 

Route::resource('/dashboard', 'DashboardController');

Route::resource('/businesspartners', 'BusinessPartnersController');
Route::resource('/inventory', 'InventoryController');
Route::resource('/goodsentry', 'GoodsEntryController');
Route::resource('/reports', 'ReportsController');
Route::resource('/users', 'UserController');
// Route::get('/dashboard', 'DashboardController@index');
// Route::get('/sales', 'SalesController@index');

//DASHBOARD

Route::get('/invMiDetails/{id}',['uses'=>'DashboardController@invMiDetails','as'=>'invMiDetails']);
Route::get('/invDataDB',['uses'=>'DashboardController@invDataDB','as'=>'invDataDB']);
Route::get('/dashboardData',['uses'=>'DashboardController@dashboardData','as'=>'dashboardData']);
Route::get('/showSalesDetails/{id}',['uses'=>'DashboardController@showSalesDetails','as'=>'showSalesDetails']);
Route::get('/showSales',['uses'=>'DashboardController@showSales','as'=>'showSales']);

//SALES
Route::get('sales',['uses'=>'SalesController@index','as'=>'sales.index']);
Route::post('/getSetSales',['uses'=>'SalesController@getSetSales','as'=>'sales.getSetSales']);
Route::get('/print-bill/{id}',['uses'=>'SalesController@printBillView','as'=>'sales.printBillView']);
Route::get('/print-os/{id}',['uses'=>'SalesController@printOSView','as'=>'sales.printOSView']);
Route::get('/print-receipt/{id}',['uses'=>'SalesController@printReceiptView','as'=>'sales.printReceiptView']);

//ORDER LIST

Route::get('/orderList',['uses'=>'SalesController@orderList','as'=>'sales.orderlist']);

Route::get('/dataTablesOrderList/{dateStart}/{dateEnd}',['uses'=>'SalesController@dataTablesOrderList','as'=>'dataTablesOrderList']);


//MENU ITEMS 
Route::get('menuItems',['uses'=>'MenuItemsController@index','as'=>'menuItems.index']);
Route::post('/getSetMenuItems',['uses'=>'MenuItemsController@getSetMenuItems','as'=>'menuItems.getSetMenuItems']);
Route::get('/dataTablesMenuItemsList',['uses'=>'MenuItemsController@dataTablesMenuItemsList','as'=>'dataTablesMenuItemsList']);

//SETTINGS

Route::get('/settings',['uses'=>'SettingsController@index','as','settings.index']);
Route::get('/dataTablesDiscounts',['uses'=>'SettingsController@dataTablesDiscounts','as'=>'dataTablesDiscounts']);
Route::post('/getSetSettings',['uses'=>'SettingsController@getSetSettings','as'=>'settings.getSetSettings']);

//INVENTORY

Route::get('/invCountDetails/{id}',['uses'=>'InventoryController@invCountDetails','as'=>'invCountDetails']);
Route::get('/invCountList',['uses'=>'InventoryController@invCountList','as'=>'invCountList']);
Route::post('/saveSettleInvQty',['uses'=>'InventoryController@saveSettleInvQty','as'=>'inventory.saveSettleInvQty']);
Route::get('/invCountItem/{id}',['uses'=>'InventoryController@invCountItem','as'=>'invCountItem']);
Route::get('/settleInvCount',['uses'=>'InventoryController@settleInvCount','as'=>'settleInvCount']);
Route::get('/invCountIndex',['uses'=>'InventoryController@invCountIndex','as'=>'invCountIndex']);
Route::get('/invData',['uses'=>'InventoryController@inventoryData','as'=>'invData']);
Route::get('/lastInvData',['uses'=>'InventoryController@lastInvData','as'=>'lastInvData']);
Route::get('/showInvData/{id}',['uses'=>'InventoryController@showInvData','as'=>'showInvData']);


//BP
Route::get('/lastBpData',['uses'=>'BusinessPartnersController@lastBpData','as'=>'lastBpData']);
Route::get('/bpData',['uses'=>'BusinessPartnersController@bpData','as'=>'bpData']);
Route::get('/showBpData/{id}',['uses'=>'BusinessPartnersController@showBpData','as'=>'showBpData']);

//Goods Entry

Route::get('/staleItems',['uses'=>'GoodsEntryController@staleItems','as'=>'staleItems']);
Route::get('/showGoodsReturn',['uses'=>'GoodsEntryController@showGoodsReturn','as'=>'showGoodsReturn']);
Route::get('/showGoodsEntry',['uses'=>'GoodsEntryController@showGoodsEntry','as'=>'showGoodsEntry']);
Route::get('/selectItemCode/{id}',['uses'=>'GoodsEntryController@selectItemCode','as'=>'selectItemCode']);
Route::get('/gEntryDetailList/{id}',['uses'=>'GoodsEntryController@gEntryDetailList','as'=>'gEntryDetailList']);
Route::get('/gReturnDetailList/{id}',['uses'=>'GoodsEntryController@gReturnDetailList','as'=>'gReturnDetailList']);

//Reports

Route::get('/showSalesDetails/{dateStart}/{dateEnd}/{bp}',['uses'=>'ReportsController@showSalesDetails','as'=>'showSalesDetails']);
Route::get('/showSalesData/{dateStart}/{dateEnd}/{bp}',['uses'=>'ReportsController@showSalesData','as'=>'showSalesData']);
Route::get('/GEDetailsReport/{dateStart}/{dateEnd}/{bp}',['uses'=>'ReportsController@GEDetailsReport','as'=>'GEDetailsReport']);
Route::get('/GoodsEntryData/{dateStart}/{dateEnd}/{bp}',['uses'=>'ReportsController@GoodsEntryData','as'=>'GoodsEntryData']);
Route::get('/inventoryReportData/{dateStart}/{dateEnd}',['uses'=>'ReportsController@inventoryReportData','as'=>'inventoryReportData']);

//Export Controller

Route::get('/exportSalesData/{dateStart}/{dateEnd}/{bp}',['uses'=>'ExportController@exportSalesData','as'=>'exportSalesData']);
Route::get('/exportGoodsEntryDetails/{dateStart}/{dateEnd}/{bp}',['uses'=>'ExportController@exportGoodsEntryDetails','as'=>'exportGoodsEntryDetails']);
Route::get('/exportGoodsEntry/{dateStart}/{dateEnd}/{bp}',['uses'=>'ExportController@exportGoodsEntry','as'=>'exportGoodsEntry']);
Route::get('/exportInvData/{dateStart}/{dateEnd}',['uses'=>'ExportController@exportInvData','as'=>'exportInvData']);



//USERS

Route::get('/showUserRowData/{id}',['uses'=>'UserController@showUserRowData','as'=>'showUserRowData']);
Route::get('/showUserData',['uses'=>'UserController@showUserData','as'=>'showUserData']);