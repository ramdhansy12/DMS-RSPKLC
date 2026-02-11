@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Edit User</strong>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password (Kosongkan jika tidak diganti)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select">
                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

@endsection
