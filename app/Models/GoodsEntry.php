<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GoodsEntry
 * 
 * @property int $id
 * @property string $bpId
 * @property \Carbon\Carbon $entryDate
 * @property int $status
 * @property float $totalPayables
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class GoodsEntry extends Eloquent
{
	protected $table = 'goods_entry';

	protected $casts = [
		'status' => 'int',
		'totalPayables' => 'float',
		'userId' => 'int'
	];

	protected $dates = [
		'entryDate'
	];

	protected $fillable = [
		'bpId',
		'entryDate',
		'status',
		'totalPayables',
		'userId'
	];
}
