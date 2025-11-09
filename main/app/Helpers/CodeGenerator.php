<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CodeGenerator
{
    /**
     * Generate next code for a table
     * 
     * @param string $table Table name
     * @param string $column Column name (default: 'ma')
     * @param string $prefix Prefix for code (e.g., 'BH', 'LHDT')
     * @param int $length Total length of numeric part (default: 3)
     * @return string Generated code
     */
    public static function generateCode(string $table, string $column = 'ma', string $prefix = '', int $length = 3): string
    {
        // Get the last code with the same prefix
        $lastCode = DB::table($table)
            ->where($column, 'LIKE', $prefix . '%')
            ->orderBy($column, 'desc')
            ->value($column);

        if (!$lastCode) {
            // First code with this prefix
            $nextNumber = 1;
        } else {
            // Extract numeric part from last code
            $numericPart = substr($lastCode, strlen($prefix));
            $nextNumber = intval($numericPart) + 1;
        }

        // Format with leading zeros
        $formattedNumber = str_pad($nextNumber, $length, '0', STR_PAD_LEFT);

        return $prefix . $formattedNumber;
    }

    /**
     * Check if code exists in table
     */
    public static function codeExists(string $table, string $column, string $code, ?int $excludeId = null): bool
    {
        $query = DB::table($table)->where($column, $code);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
