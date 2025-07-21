@extends('layouts.app')

@section('title', 'Coupon')

@section('content')
    <div class="content">
        <div class="col-md-12">
            <div class="card card-tasks">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Coupons</h4>
                    <a href="{{ route('coupons') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-full-width">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Coins</th>
                                    <th>Giveaway</th>
                                    <th>Image</th>
                                    <th>Label Popular</th>
                                    <th>Label Color</th>
                                    <th>Price Per Icon</th>
                                    <th>Total Price</th>
                                    <th>Product Id</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coupons as $coupon)
                                    <tr>

                                        <td>{{ $coupon->id }}</td>
                                        <td>{{ $coupon->title ?? 'N/A' }}</td>
                                        <td>{{ $coupon->coins ?? 0 }}</td>
                                        <td>{{ $coupon->giveaway ?? 0 }}</td>
                                        <td>
                                            @if ($coupon->pkg_image_url)
                                                <img src="{{ $coupon->pkg_image_url }}" alt="Coupon Image"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $coupon->label_popular ?? 'N/A' }}</td>
                                        <td>{{ $coupon->label_color ?? 'N/A' }}</td>
                                        <td>{{ $coupon->price_per_coin ?? '0' }}</td>
                                        <td>{{ $coupon->total_price ?? 0 }}</td>
                                        <td>{{ $coupon->product_id ?? 'N/A' }}</td>

                                        <td class="td-actions text-right">
                                            <div class="form-button-action">
                                                <a href="{{ route('coupons.edit', $coupon->id) }}"
                                                    class="btn btn-link btn-simple-primary" data-toggle="tooltip"
                                                    title="Edit Coupon">
                                                    <i class="la la-edit"></i>
                                                </a>
                                                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-simple-danger"
                                                        data-toggle="tooltip" title="Delete Coupon"
                                                        onclick="return confirm('Are you sure you want to delete this coupon?')">
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
