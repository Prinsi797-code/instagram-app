@extends('layouts.app')

@section('title', 'Coupon')

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card card-tasks">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Purchase Coins</h4>
                    {{-- <a href="{{ route('setting.view') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add
                    </a> --}}
                </div>
                <div class="card-body">
                    <div class="table-full-width">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Id</th>
                                    <th>Device Id</th>
                                    <th>Image</th>
                                    <th>Coin Count</th>
                                    <th>Coint</th>
                                    <th>Giveaway</th>
                                    <th>PricePerCoin</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ($purchaseDetails->isEmpty())
                                    <div class="alert alert-danger" role="alert">
                                        No purchase details found.
                                    </div>
                                @else
                                    @foreach ($purchaseDetails as $purchaseDetail)
                                        <tr>
                                            <td>{{ $purchaseDetail->id }}</td>
                                            <td>{{ $purchaseDetail->product_id }}</td>
                                            <td>{{ $purchaseDetail->device_id }}</td>
                                            <td>
                                                @if ($purchaseDetail->pkg_image_url)
                                                    <img src="{{ asset($purchaseDetail->pkg_image_url) }}"
                                                        class="card-img-top" alt="Package Image"
                                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                @else
                                                    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center"
                                                        style="height: 200px;">
                                                        No Image Available
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $purchaseDetail->coin_count }}</td>
                                            <td>{{ $purchaseDetail->coins }}</td>
                                            <td>{{ $purchaseDetail->giveaway ?? 'None' }}</td>
                                            <td>{{ number_format($purchaseDetail->price_per_coin, 2) }}</td>
                                            <td>{{ number_format($purchaseDetail->total_price, 2) }}</td>
                                            <td class="td-actions text-right">
                                                {{-- <div class="form-button-action">
                                                <a href="{{ route('settings.edit', $purchaseDetail->id) }}"
                                                    class="btn btn-link btn-simple-primary" data-toggle="tooltip"
                                                    title="Edit Setting">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('settings.destroy', $purchaseDetail->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-simple-danger"
                                                        data-toggle="tooltip" title="Delete Setting"
                                                        onclick="return confirm('Are you sure you want to delete this setting ?')">
                                                        <i class="la la-times"></i>
                                                    </button>
                                                </form>
                                            </div> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
