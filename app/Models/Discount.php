<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Discount
 * 
 * @property int $id
 * @property string $name
 * @property int $type
 * @property float $discountValue
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Discount extends Eloquent
{
	protected $casts = [
		'type' => 'int',
		'discountValue' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'name',
		'type',
		'discountValue',
		'userId'
	];
}
