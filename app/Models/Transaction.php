<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['paidAmount', 'currency', 'parentEmail', 'statusCode', 'paymentDate', 'parentIdentification'];

    const AUTHORIZED = 1;
    const DECLINE = 2;
    const REFUNDED = 3;

    public function getStatusCodeAttribute($value)
    {
        switch ($value) {
            case self::AUTHORIZED:
                return 'authorized';
            case self::DECLINE:
                return 'decline';
            case self::REFUNDED:
                return 'refunded';
            default:
                return null;
        }
    }

    public function scopeApplyFilters($query, $filters)
    {

        if (!empty($filters['status_code'])) {
            $query->statusCode($filters['status_code']);
        }
        if (!empty($filters['currency'])) {
            $query->currency($filters['currency']);
        }
        if (!empty($filters['min_amount']) && !empty($filters['max_amount'])) {
            $query->amountRange($filters['min_amount'], $filters['max_amount']);
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }
        return $query;
    }

    public function scopeStatusCode($query, $statusCode)
    {
        $statusCodeMap = [
            'authorized' => self::AUTHORIZED,
            'decline' => self::DECLINE,
            'refunded' => self::REFUNDED,
        ];

        if (isset($statusCodeMap[$statusCode])) {
            $statusCode = $statusCodeMap[$statusCode];
        }

        return $query->where('statusCode', $statusCode);
    }


    public function scopeCurrency($query, $currency)
    {
        return $query->where('Currency', $currency);
    }

    public function scopeAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('paidAmount', [$minAmount, $maxAmount]);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('paymentDate', [$startDate, $endDate]);
    }
}
