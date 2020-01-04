<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GoodsEntryDetail
 * 
 * @property int $id
 * @property string $goodsEntryId
 * @property string $invId
 * @property float $qty
 * @property float $price
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $uom
 * @property string $itemName
 *
 * @package App\Models
 */
class GoodsEntryDetail extends Eloquent
{
	protected $casts = [
		'qty' => 'float',
		'price' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'goodsEntryId',
		'invId',
		'qty',
		'price',
		'userId',
		'uom',
		'itemName'
	];
}
