<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Inventory
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $invCategoryId
 * @property int $uomId
 * @property float $qty
 * @property int $status
 * @property int $sellType
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property float $sellingPrice
 *
 * @package App\Models
 */
class Inventory extends Eloquent
{
	protected $table = 'inventory';

	protected $casts = [
		'invCategoryId' => 'int',
		'uomId' => 'int',
		'qty' => 'float',
		'status' => 'int',
		'sellType' => 'int',
		'userId' => 'int',
		'sellingPrice' => 'float'
	];

	protected $fillable = [
		'code',
		'name',
		'invCategoryId',
		'uomId',
		'qty',
		'status',
		'sellType',
		'userId',
		'sellingPrice'
	];
}
