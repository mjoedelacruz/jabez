<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MenuItem
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $menuCategoryId
 * @property int $status
 * @property float $sellingPrice
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $description
 * @property int $discountId
 *
 * @package App\Models
 */
class MenuItem extends Eloquent
{
	protected $casts = [
		'menuCategoryId' => 'int',
		'status' => 'int',
		'sellingPrice' => 'float',
		'userId' => 'int',
		'discountId' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'menuCategoryId',
		'status',
		'sellingPrice',
		'userId',
		'description',
		'discountId'
	];
}
