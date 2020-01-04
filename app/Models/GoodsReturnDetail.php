<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GoodsReturnDetail
 * 
 * @property int $id
 * @property string $gdsReturnCode
 * @property string $invCode
 * @property string $invName
 * @property \Carbon\Carbon $storeDays
 * @property float $qty
 * @property string $uom
 * @property float $price
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class GoodsReturnDetail extends Eloquent
{
	protected $casts = [
		'qty' => 'float',
		'price' => 'float',
		'userId' => 'int'
	];

	protected $dates = [
		'storeDays'
	];

	protected $fillable = [
		'gdsReturnCode',
		'invCode',
		'invName',
		'storeDays',
		'qty',
		'uom',
		'price',
		'userId'
	];
}
