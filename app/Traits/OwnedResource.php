<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait OwnedResource
{
    /**
     * Otomatis set created_by saat data baru dibuat.
     */
    public static function bootOwnedResource()
    {
        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    /**
     * Scope: Filter data hanya milik user yang login,
     * digunakan KHUSUS untuk Tunjangan Transport (RO).
     *
     * Catatan sesuai matriks RBAC:
     * - Manager HRD & Admin HRD: Tunjangan Transport = RO (hanya miliknya)
     * - Untuk employees & users: Manager HRD bisa baca SEMUA (R), Admin HRD CRUD semua
     * - Untuk data master (Dept, Jabatan, Tipe): semua baca semua
     */
    public function scopeOnlyOwned(Builder $query)
    {
        $user = Auth::user();
        if (!$user) return $query;

        // Superadmin melihat semua data
        if ($user->hasRole('Superadmin')) {
            return $query;
        }

        // Untuk RO: hanya data yang dibuat oleh user ini
        // (termasuk data seeder yang NULL created_by dibiarkan tampil)
        return $query->where(function (Builder $q) use ($user) {
            $q->where('created_by', $user->id)
              ->orWhereNull('created_by');
        });
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
