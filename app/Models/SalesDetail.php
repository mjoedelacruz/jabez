<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class SalesDetail
 * 
 * @property int $id
 * @property int $salesId
 * @property string $inventoryMasterlistId
 * @property float $qty
 * @property int $discountId
 * @property \Carbon\Carbon $entryDate
 * @property float $discounts
 * @property float $price
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $status
 * @property string $orderName
 * @property int $free
 * @property int $osId
 *
 * @package App\Models
 */
class SalesDetail extends Eloquent
{
	protected $casts = [
		'salesId' => 'int',
		'qty' => 'float',
		'discountId' => 'int',
		'discounts' => 'float',
		'price' => 'float',
		'userId' => 'int',
		'status' => 'int',
		'free' => 'int',
		'osId' => 'int'
	];

	protected $dates = [
		'entryDate'
	];

	protected $fillable = [
		'salesId',
		'inventoryMasterlistId',
		'qty',
		'discountId',
		'entryDate',
		'discounts',
		'price',
		'userId',
		'status',
		'orderName',
		'free',
		'osId'
	];
}
