<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GoodsReturn
 * 
 * @property int $id
 * @property string $gdsEntryCode
 * @property string $remarks
 * @property string $bpCode
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class GoodsReturn extends Eloquent
{
	protected $table = 'goods_return';

	protected $casts = [
		'userId' => 'int'
	];

	protected $fillable = [
		'gdsEntryCode',
		'remarks',
		'bpCode',
		'userId'
	];
}
