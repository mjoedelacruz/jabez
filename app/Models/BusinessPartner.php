<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class BusinessPartner
 * 
 * @property int $id
 * @property string $bpCode
 * @property string $name
 * @property int $type
 * @property string $contactNo
 * @property int $userId
 * @property int $discountId
 * @property int $status
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $contactPerson
 * @property string $address
 * @property string $email
 *
 * @package App\Models
 */
class BusinessPartner extends Eloquent
{
	protected $casts = [
		'type' => 'int',
		'userId' => 'int',
		'discountId' => 'int',
		'status' => 'int'
	];

	protected $hidden = [
		'remember_token'
	];

	protected $fillable = [
		'bpCode',
		'name',
		'type',
		'contactNo',
		'userId',
		'discountId',
		'status',
		'remember_token',
		'contactPerson',
		'address',
		'email'
	];
}
