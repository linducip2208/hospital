<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    public function next(string $key, string $prefix, int $width = 4): string
    {
        return DB::transaction(function () use ($key, $prefix, $width): string {
            DB::table('document_sequences')->insertOrIgnore([
                'sequence_key' => $key,
                'current_value' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $sequence = DB::table('document_sequences')
                ->where('sequence_key', $key)
                ->lockForUpdate()
                ->first();
            $next = (int) $sequence->current_value + 1;

            DB::table('document_sequences')->where('id', $sequence->id)->update([
                'current_value' => $next,
                'updated_at' => now(),
            ]);

            return sprintf('%s-%s-%0*d', $prefix, now()->format('Ymd'), $width, $next);
        });
    }
}
