@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Session Management Debug</h2>
    <p style="color: {{ $dbStatus == 'Database connection successful!' ? 'green' : 'red' }};">{{ $dbStatus }}</p>
    @if($tableExists)
        <p style="color: green;">user_sessions table exists!</p>
        <h3>Table Structure:</h3>
        <table class="table table-bordered">
            <thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>
            <tbody>
            @foreach($tableStructure as $row)
                <tr>
                    <td>{{ $row->Field }}</td>
                    <td>{{ $row->Type }}</td>
                    <td>{{ $row->Null }}</td>
                    <td>{{ $row->Key }}</td>
                    <td>{{ $row->Default }}</td>
                    <td>{{ $row->Extra }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p><strong>Total sessions in database:</strong> {{ $totalSessions }}</p>
        <p><strong>Active sessions (not expired):</strong> {{ $activeSessions }}</p>
        <h3>All Sessions:</h3>
        @if(count($allSessions))
            <table class="table table-bordered">
                <thead><tr><th>ID</th><th>User ID</th><th>Session ID</th><th>IP</th><th>Created</th><th>Expires</th><th>Last Activity</th></tr></thead>
                <tbody>
                @foreach($allSessions as $session)
                    <tr>
                        <td>{{ $session->id }}</td>
                        <td>{{ $session->user_id }}</td>
                        <td>{{ substr($session->id, 0, 20) }}...</td>
                        <td>{{ $session->ip_address }}</td>
                        <td>{{ $session->created_at }}</td>
                        <td>{{ $session->expires_at }}</td>
                        <td>{{ $session->last_activity }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p style="color: orange;">No sessions found in database.</p>
        @endif
        <h3>Current Session Info:</h3>
        <p><strong>Session ID:</strong> {{ $currentSessionId }}</p>
        <p><strong>Session Data:</strong></p>
        <pre>{{ print_r($sessionData, true) }}</pre>
        <h3>SessionManager Test:</h3>
        @if($user)
            <p><strong>Current User ID:</strong> {{ $user->id }}</p>
            <p><strong>Sessions found by SessionManager:</strong> {{ count($userSessions) }}</p>
            @if(count($userSessions))
                <table class="table table-bordered">
                    <thead><tr><th>Session ID</th><th>Device ID</th><th>IP</th><th>Last Activity</th><th>Expires</th></tr></thead>
                    <tbody>
                    @foreach($userSessions as $session)
                        <tr>
                            <td>{{ substr($session->id, 0, 20) }}...</td>
                            <td>{{ substr($session->device_id, 0, 16) }}...</td>
                            <td>{{ $session->ip_address }}</td>
                            <td>{{ $session->last_activity }}</td>
                            <td>{{ $session->expires_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @else
            <p style="color: orange;">No user logged in (no user_id in session).</p>
        @endif
        <a href="{{ route('session.management') }}">Go to Session Management</a>
    @else
        <p style="color: red;">user_sessions table does NOT exist!</p>
    @endif
</div>
@endsection
