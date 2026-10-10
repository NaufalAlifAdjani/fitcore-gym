<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman beranda member.
     */
    public function index(Request $request): View
    {
        if ($request->user()->role !== 'member') {
            abort(403);
        }

        return view('member.dashboard');
    }
}
