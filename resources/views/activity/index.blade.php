@extends('layouts.admin')

@section('title','Activity Log')

@section('content')

<div class="card shadow-sm">
    <div class="card-header">
        <strong>Activity Log</strong>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-light">
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Module</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->module }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>

@endsection
