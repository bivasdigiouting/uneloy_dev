<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'date',
        'expense_id',
        'amount',
        'bill_no',
        'payment_mode',
        'bank_account_no',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'upi_id',
        'supplier',
        'bill_file',
        'description',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the expense that owns the expense bill.
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    /**
     * Get the formatted amount attribute.
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    /**
     * Get the status badge attribute.
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status ?
            '<span class="badge bg-success">Active</span>' :
            '<span class="badge bg-danger">Inactive</span>';
    }

    /**
     * Get the payment mode badge attribute.
     */
    public function getPaymentModeBadgeAttribute()
    {
        $badges = [
            'cash' => '<span class="badge bg-primary">Cash</span>',
            'bank' => '<span class="badge bg-info">Bank</span>',
            'upi' => '<span class="badge bg-warning">UPI</span>',
        ];

        return $badges[$this->payment_mode] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Scope a query to only include active expense bills.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include inactive expense bills.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /**
     * Scope a query to filter by payment mode.
     */
    public function scopeByPaymentMode($query, $paymentMode)
    {
        return $query->where('payment_mode', $paymentMode);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}
