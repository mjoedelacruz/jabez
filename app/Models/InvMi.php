<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class InvMi
 * 
 * @property int $id
 * @property string $menuItemId
 * @property string $invId
 * @property float $qty
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class InvMi extends Eloquent
{
	protected $table = 'inv_mi';

	protected $casts = [
		'qty' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'menuItemId',
		'invId',
		'qty',
		'userId'
	];
}
