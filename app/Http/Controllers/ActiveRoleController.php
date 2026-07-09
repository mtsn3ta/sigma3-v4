<?php

namespace App\Http\Controllers;

use App\Support\ActiveRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveRoleController extends Controller
{
    public function switch(Request $request): RedirectResponse
    {
        $role = $request->input('role');

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user?->hasRole($role)) {
            abort(403);
        }

        ActiveRole::set($role);

        return redirect()->back();
    }
}
