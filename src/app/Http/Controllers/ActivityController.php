<?php

namespace App\Http\Controllers;

use App\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = auth()->user()->activities()->paginate(20);

        return view('activity.index', compact('activities'));
    }

    public function destroy(Activity $activity)
    {
        $this->authorize('deleteActivity', $activity);
        $activity->delete();

        return back()->with('msg', 'deleted successfully');
    }
}