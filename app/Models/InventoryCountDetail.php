<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class InventoryCountDetail
 * 
 * @property int $id
 * @property int $invCountCode
 * @property string $invCode
 * @property float $invQty
 * @property float $newInvQty
 * @property float $qtyDiscrepancy
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class InventoryCountDetail extends Eloquent
{
	protected $casts = [
		'invCountCode' => 'int',
		'invQty' => 'float',
		'newInvQty' => 'float',
		'qtyDiscrepancy' => 'float',
		'userId' => 'int'
	];

	protected $fillable = [
		'invCountCode',
		'invCode',
		'invQty',
		'newInvQty',
		'qtyDiscrepancy',
		'userId'
	];
}
