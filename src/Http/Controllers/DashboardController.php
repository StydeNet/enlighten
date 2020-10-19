<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class DashboardController extends Controller
{
    public function index()
    {
        if (Run::count() === 0) {
            return redirect(route('enlighten.intro'));
        }
        return view('enlighten::dashboard.index');
    }
}
