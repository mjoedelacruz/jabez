<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Inventorymasterlist
 * 
 * @property int $id
 * @property string $code
 * @property int $type
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Inventorymasterlist extends Eloquent
{
	protected $casts = [
		'type' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'code',
		'type',
		'userId'
	];
}
