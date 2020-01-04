<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Oslist
 * 
 * @property int $id
 * @property string $code
 * @property int $salesId
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Oslist extends Eloquent
{
	protected $table = 'oslist';

	protected $casts = [
		'salesId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'code',
		'salesId',
		'userId'
	];
}
