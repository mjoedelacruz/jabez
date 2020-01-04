<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MenuPackagedItem
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $status
 * @property float $sellingPrice
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class MenuPackagedItem extends Eloquent
{
	protected $casts = [
		'status' => 'int',
		'sellingPrice' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'status',
		'sellingPrice',
		'userId'
	];
}
