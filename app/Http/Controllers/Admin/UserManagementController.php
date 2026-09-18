<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'job_seeker')
            ->with(['profile.educationLevel', 'skills', 'experiences']);

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(int $id): View
    {
        $user = User::where('role', 'job_seeker')
            ->with(['profile.educationLevel', 'skills', 'experiences', 'preferredCategories', 'savedJobs'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Status akun Administrator tidak dapat diubah.');
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        $statusText = $user->status === 'active' ? 'diaktifkan kembali' : 'dinonaktifkan';

        return back()->with('success', "Akun pengguna {$user->name} berhasil {$statusText}.");
    }
}
