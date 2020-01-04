<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Uomconversion
 * 
 * @property int $id
 * @property int $uomId
 * @property float $conversionFactor
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Uomconversion extends Eloquent
{
	protected $casts = [
		'uomId' => 'int',
		'conversionFactor' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'uomId',
		'conversionFactor',
		'userId'
	];
}
