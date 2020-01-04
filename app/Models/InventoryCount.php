<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class InventoryCount
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class InventoryCount extends Eloquent
{
	protected $table = 'inventory_count';

	protected $casts = [
		'userId' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'userId'
	];
}
