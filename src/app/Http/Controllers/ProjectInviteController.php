<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectInviteController extends Controller
{
    public function invite(Request $request, Project $project)
    {
        $this->authorize('invite', $project);
        $validator = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);
        $invitee = User::where('email', $validator['email'])->first();
        if ($invitee->email === Auth::user()->email) {
            return \back()->withErrors('You can not invite your self');
        }
        $project->invite($invitee);

        return redirect($project->path());
    }
}