<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 12 Feb 2019 16:50:08 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Sale
 * 
 * @property int $id
 * @property string $code
 * @property string $os_no
 * @property int $bpId
 * @property int $tableId
 * @property int $discountId
 * @property \Carbon\Carbon $entryDate
 * @property float $totalDiscounts
 * @property float $totalReceivables
 * @property int $userId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property float $tax
 * @property int $noOfSpecial
 * @property int $noOfGuests
 * @property float $priceAfterDiscount
 * @property float $priceAfterVAT
 * @property float $change
 * @property int $waiterId
 * @property int $status
 * @property int $paidThru
 * @property string $cardNo
 * @property string $cardTransactionNo
 * @property float $cashAmount
 * @property int $billReprint
 * @property int $receiptReprint
 * @property string $remarks
 * @property int $zeroRated
 *
 * @package App\Models
 */
class Sale extends Eloquent
{
	protected $casts = [
		'bpId' => 'int',
		'tableId' => 'int',
		'discountId' => 'int',
		'totalDiscounts' => 'float',
		'totalReceivables' => 'float',
		'userId' => 'int',
		'tax' => 'float',
		'noOfSpecial' => 'int',
		'noOfGuests' => 'int',
		'priceAfterDiscount' => 'float',
		'priceAfterVAT' => 'float',
		'change' => 'float',
		'waiterId' => 'int',
		'status' => 'int',
		'paidThru' => 'int',
		'cashAmount' => 'float',
		'billReprint' => 'int',
		'receiptReprint' => 'int',
		'zeroRated' => 'int'
	];

	protected $dates = [
		'entryDate'
	];

	protected $fillable = [
		'code',
		'os_no',
		'bpId',
		'tableId',
		'discountId',
		'entryDate',
		'totalDiscounts',
		'totalReceivables',
		'userId',
		'tax',
		'noOfSpecial',
		'noOfGuests',
		'priceAfterDiscount',
		'priceAfterVAT',
		'change',
		'waiterId',
		'status',
		'paidThru',
		'cardNo',
		'cardTransactionNo',
		'cashAmount',
		'billReprint',
		'receiptReprint',
		'remarks',
		'zeroRated'
	];
}
