<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Models\Appointment;

class RatingController extends Controller
{
    // A patient submits a rating for one of their own completed appointments.
    public function store(RatingRequest $request, Appointment $appointment)
    {
        // Make sure this appointment actually belongs to the person
        // submitting the rating -- otherwise anyone could rate any
        // appointment just by guessing the URL.
        if ($appointment->user_id !== $request->user()->id) {
            abort(403);
        }

        // Only appointments that already happened can be rated.
        if ($appointment->status !== 'completed') {
            return back()->with('error', 'You can only rate an appointment after it has been completed.');
        }

        // Don't allow rating the same appointment twice.
        if ($appointment->rating) {
            return back()->with('error', 'You have already rated this appointment.');
        }

        $validated = $request->validated();

        Appointment::find($appointment->id)->rating()->create([
            'user_id'   => $request->user()->id,
            'doctor_id' => $appointment->doctor_id,
            'rating'    => $validated['rating'],
            'comment'   => $validated['comment'] ?? null,
        ]);

        return back()->with('status', 'Thanks! Your rating has been submitted.');
    }
}
