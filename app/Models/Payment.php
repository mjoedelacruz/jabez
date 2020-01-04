<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Payment
 * 
 * @property int $id
 * @property int $salesId
 * @property string $name
 * @property int $type
 * @property string $transactionNo
 * @property float $amount
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class Payment extends Eloquent
{
	protected $casts = [
		'salesId' => 'int',
		'type' => 'int',
		'amount' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'salesId',
		'name',
		'type',
		'transactionNo',
		'amount',
		'userId'
	];
}
