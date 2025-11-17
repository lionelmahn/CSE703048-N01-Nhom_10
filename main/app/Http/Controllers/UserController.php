<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::with('khoa')->paginate(20);
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $khoas = Khoa::all();
        $roles = ['admin' => 'Admin', 'khoa' => 'Khoa', 'giang_vien' => 'Giảng viên', 'sinh_vien' => 'Sinh viên'];
        return view('user.create', compact('khoas', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'khoa_id' => 'nullable|exists:khoa,id',
            'role' => 'required|in:admin,khoa,giang_vien,sinh_vien',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Tạo người dùng thành công');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $khoas = Khoa::all();
        $roles = ['admin' => 'Admin', 'khoa' => 'Khoa', 'giang_vien' => 'Giảng viên', 'sinh_vien' => 'Sinh viên'];

        return view('user.edit', compact('user', 'khoas', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'khoa_id' => 'nullable|exists:khoa,id',
            'role' => 'required|in:admin,khoa,giang_vien,sinh_vien',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể xóa tài khoản đang sử dụng');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công');
    }

    public function toggleActive(User $user)
    {
        $this->authorize('update', $user);

        
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể khóa tài khoản đang sử dụng'
            ], 400);
        }

        $user->active = !$user->active;
        $user->save();

        $action = $user->active ? 'mở khóa' : 'khóa';

        return response()->json([
            'success' => true,
            'message' => "Đã {$action} tài khoản thành công",
            'active' => $user->active
        ]);
    }
}
