<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class DashboardController extends Controller
{
    public function index()
    {
        $runs = Run::query()->with('stats')->latest()->get();

        if ($runs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        return view('enlighten::dashboard.index', ['runs' => $runs]);
    }
}
