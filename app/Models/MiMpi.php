<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MiMpi
 * 
 * @property int $id
 * @property int $menuItemId
 * @property int $mpiId
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class MiMpi extends Eloquent
{
	protected $table = 'mi_mpi';

	protected $casts = [
		'menuItemId' => 'int',
		'mpiId' => 'int',
		'userId' => 'int'
	];

	protected $fillable = [
		'menuItemId',
		'mpiId',
		'userId'
	];
}
