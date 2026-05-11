<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cost extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
        'employee_id',
        'department_id',
        'cost_type',
        'notes',
        'document',
    ];

    public const COST_TYPES = [
        'Bazar khoroch',
        'Home rent',
        'Current bill',
        'Internet charge',
        'Water bill',
        'Paper bill',
        'Instrument cost',
        'TA & DA',
        'Transport',
        'Repairing cost',
        'Others',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}