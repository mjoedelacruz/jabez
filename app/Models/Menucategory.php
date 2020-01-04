<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Menucategory
 * 
 * @property int $id
 * @property string $name
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Menucategory extends Eloquent
{
	protected $casts = [
		'userId' => 'int'
	];

	protected $fillable = [
		'name',
		'userId'
	];
}
