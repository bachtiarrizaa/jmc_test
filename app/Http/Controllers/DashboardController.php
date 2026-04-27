<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Enums\Gender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $data = [
            'user' => $user,
            'role' => $role
        ];

        if ($role === 'Manager HRD') {
            $data['total_employees'] = Employee::count();

            $data['total_permanent'] = Employee::whereHas('type', function ($q) {
                $q->where('name', 'Staff Tetap');
            })->count();

            $data['total_contract'] = Employee::whereHas('type', function ($q) {
                $q->where('name', 'Karyawan Kontrak');
            })->count();

            $data['total_intern'] = Employee::whereHas('type', function ($q) {
                $q->where('name', 'Peserta Magang');
            })->count();

            $data['male_count'] = Employee::where('gender', Gender::PRIA->value)->count();
            $data['female_count'] = Employee::where('gender', Gender::WANITA->value)->count();

            $data['recent_contracts'] = Employee::whereHas('type', function ($q) {
                $q->where('name', 'Karyawan Kontrak');
            })->latest('join_date')->take(5)->get();
        }

        return view('dashboard', $data);
    }
}
