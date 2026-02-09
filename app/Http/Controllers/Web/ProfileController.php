<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function edit(Request $request)
    {
        $user = $request->user()->load('companies:id,name');

        return view('pages.dashboard', compact('user'));
    }

    public function update()
    {
        return back()->with('status', 'Profile updated');
    }

    public function destroy()
    {
        return redirect()->route('info')->with('status', 'Account deleted');
    }
}

