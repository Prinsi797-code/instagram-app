@extends('layouts.app')

@section('title', 'Coupon Store')

<style>
    .remove-image {
        position: absolute;
        top: 2px;
        right: 4px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 14px;
        width: 20px;
        height: 20px;
        cursor: pointer;
        text-align: center;
        line-height: 17px;
        z-index: 2;
    }

    .upload-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .file-upload-wrapper {
        position: relative;
        overflow: hidden;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
        /* Ensure input is above other elements */
    }

    .placeholder-container {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .placeholder-box {
        width: 120px;
        height: 120px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .placeholder-box:hover {
        border-color: #007bff;
        background-color: #e7f3ff;
    }

    .placeholder-box.active {
        border-color: #28a745;
        background-color: #e8f5e8;
    }

    .placeholder-icon {
        font-size: 24px;
        color: #6c757d;
    }

    .placeholder-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2;
    }

    .placeholder-box.has-image .remove-btn {
        display: flex;
    }

    .placeholder-box.has-image .placeholder-icon {
        display: none;
    }

    .upload-text {
        font-size: 12px;
        color: #6c757d;
        text-align: center;
        margin-top: 5px;
    }

    .business-logo-container {
        display: flex;
        justify-content: center;
    }

    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
    }

    .text-muted {
        font-size: 13px;
        margin-top: 8px;
    }

    .success-message {
        color: #28a745;
        font-size: 12px;
        margin-top: 5px;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    .upload-section {
        margin-bottom: 40px;
        padding: 20px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }
</style>

@section('content')
    <div class="content">
        <div class="col-md-12">
            <form action="{{ route('setting.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Coupon Store</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="coins">Coins</label>
                            <input type="text" class="form-control input-square" id="coins" name="coins"
                                value="{{ old('coins') }}" placeholder="Coins">
                            @error('coins')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="giveaway">Giveaway</label>
                            <input type="text" class="form-control input-square" id="giveaway" name="giveaway"
                                value="{{ old('giveaway') }}" placeholder="Giveaway">
                            @error('giveaway')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-danger" onclick="window.history.back()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Display success notification if present
        @if (session('success'))
            $.notify({
                icon: 'la la-bell',
                title: 'Success',
                message: '{{ session('success') }}',
            }, {
                type: 'success',
                placement: {
                    from: "bottom",
                    align: "right"
                },
                time: 1000,
            });
        @endif
    </script>
@endsection
