<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::with(['user', 'doctor.hospital']);

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments', 'status'));
    }
}
