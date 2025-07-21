@extends('layouts.app')

@section('title', 'Coupon')

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card card-tasks">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Setting</h4>
                    <a href="{{ route('setting.view') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-full-width">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Giveaway</th>
                                    <th>Coins</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settings as $setting)
                                    <tr>
                                        <td>{{ $setting->id }}</td>
                                        <td>{{ $setting->giveaway ?? 'N/A' }}</td>
                                        <td>{{ $setting->coins ?? 0 }}</td>
                                        <td class="td-actions text-right">
                                            <div class="form-button-action">
                                                <a href="{{ route('settings.edit', $setting->id) }}"
                                                    class="btn btn-link btn-simple-primary" data-toggle="tooltip"
                                                    title="Edit Setting">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('settings.destroy', $setting->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-simple-danger"
                                                        data-toggle="tooltip" title="Delete Setting"
                                                        onclick="return confirm('Are you sure you want to delete this setting ?')">
                                                        <i class="la la-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="card-footer">
                    <div class="stats">
                        <i class="now-ui-icons loader_refresh spin"></i> Updated 3 minutes ago
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
