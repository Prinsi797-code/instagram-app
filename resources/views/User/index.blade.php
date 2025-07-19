@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card card-tasks">
                <div class="card-header">
                    <h4 class="card-title">Users</h4>
                </div>
                <div class="card-body">
                    <div class="table-full-width">
                        <table class="table">
                            <thead>
                                <tr>

                                    <th>ID</th>
                                    <th>Device ID</th>
                                    <th>Coin Count</th>
                                    <th>Token</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->device_id ?? 'N/A' }}</td>
                                        <td>{{ $user->coin_count ?? 0 }}</td>
                                        <td>
                                            @if ($user->tokens->isNotEmpty())
                                                {{ $user->tokens->first()->token ?? 'No Token' }}
                                            @else
                                                No Token
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
