<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public const TYPES = [
        'taken_from_boss' => 'Taken from Boss',
        'given_to_boss' => 'Given to Boss',
        'given_to_others' => 'Given to Others',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function types(): array
    {
        return self::TYPES;
    }

    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
