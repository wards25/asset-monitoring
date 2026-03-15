<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sticker_no',
        'qr_data',
        'type',
        'brand',
        'model',
        'serial_no',
        'status',
        'department',
        'assigned_to',
        'old_user',
        'date_purchased',
        'date_deployed',
        'purchase_cost',
        'supplier',
        'remarks',
        'specs',
    ];

    protected $casts = [
        'date_purchased' => 'date',
        'date_deployed'  => 'date',
    ];

    const TYPES = [
        'system_unit' => 'System Unit',
        'monitor'     => 'Monitor',
        'mouse'       => 'Mouse',
        'keyboard'    => 'Keyboard',
        'avr'         => 'AVR',
        'laptop'      => 'Laptop',
        'printer'     => 'Printer',
    ];

    // Type suffix codes for sticker: e.g. SD-0001-M
    const TYPE_CODES = [
        'system_unit' => 'C',   // C = Computer/System Unit
        'monitor'     => 'D',   // D = Display/Monitor
        'mouse'       => 'M',   // M = Mouse
        'keyboard'    => 'K',   // K = Keyboard
        'avr'         => 'A',   // A = AVR
        'laptop'      => 'L',   // L = Laptop
        'printer'     => 'P',   // P = Printer
    ];

    // Department prefix codes: e.g. Sales = SD, IT = IT, HR = HR
    const DEPT_CODES = [
        'IT'               => 'IT',
        'HR'               => 'HR',
        'Finance'          => 'FN',
        'Accounting'       => 'AC',
        'Operations'       => 'OP',
        'Admin'            => 'AD',
        'Sales'            => 'SD',
        'Marketing'        => 'MK',
        'Management'       => 'MG',
        'Warehouse'        => 'WH',
        'Engineering'      => 'EN',
        'Customer Service' => 'CS',
    ];

    const STATUSES = [
        'new'          => 'New',
        'working'      => 'Working',
        'defective'    => 'Defective',
        'for_disposal' => 'For Disposal',
        'disposed'     => 'Disposed',
    ];

    const DEPARTMENTS = [
        'IT',
        'HR',
        'Finance',
        'Accounting',
        'Operations',
        'Admin',
        'Sales',
        'Marketing',
        'Management',
        'Warehouse',
        'Engineering',
        'Customer Service',
    ];

    public function getTypeLabel()
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusLabel()
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getTypeCode()
    {
        return self::TYPE_CODES[$this->type] ?? 'X';
    }

    public function getDeptCode()
    {
        return self::DEPT_CODES[$this->department] ?? 'XX';
    }

    public function isDeployed()
    {
        return in_array($this->status, ['working', 'new']) && !empty($this->assigned_to);
    }

    /**
     * Generate sticker number based on department + type
     * Format: {DEPT_CODE}-{0001}-{TYPE_CODE}
     * Example: SD-0001-M (Sales Department, Mouse)
     *          IT-0003-C (IT Department, System Unit)
     *          HR-0001-K (HR Department, Keyboard)
     * If no department, uses "GEN" prefix
     */
    public static function generateStickerNo(string $type, string $department = null): string
    {
        $deptCode = 'GEN';
        if ($department && isset(self::DEPT_CODES[$department])) {
            $deptCode = self::DEPT_CODES[$department];
        }

        $typeCode = self::TYPE_CODES[$type] ?? 'X';

        // Count existing assets with same dept code + type code combination
        $count = self::where('sticker_no', 'like', $deptCode . '-%-%' . $typeCode)
            ->withTrashed()
            ->count();

        $num = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return $deptCode . '-' . $num . '-' . $typeCode;
    }

    /**
     * Generate QR code data string
     * Contains all essential asset info for quick identification
     */
    public static function generateQrData(string $stickerNo, string $assigned_to ,string $type, string $brand, string $department = null): string
    {
        $dept = $department ?? 'UNASSIGNED';
        return json_encode([
            'sticker' => $stickerNo,
            'assigned_to' => $assigned_to,
            'type'    => $type,
            'brand'   => $brand,
            'dept'    => $dept,
        ]);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('sticker_no', 'like', "%{$term}%")
              ->orWhere('qr_data', 'like', "%{$term}%")
              ->orWhere('brand', 'like', "%{$term}%")
              ->orWhere('model', 'like', "%{$term}%")
              ->orWhere('serial_no', 'like', "%{$term}%")
              ->orWhere('assigned_to', 'like', "%{$term}%")
              ->orWhere('department', 'like', "%{$term}%");
        });
    }
}