@extends('layouts.app')

@section('title', 'Edit Coupon')

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
            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Coupon</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control input-square" id="title" name="title"
                                value="{{ old('title', $coupon->title) }}" placeholder="Title">
                            @error('title')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="coins">Coins</label>
                            <input type="text" class="form-control input-square" id="coins" name="coins"
                                value="{{ old('coins', $coupon->coins) }}" placeholder="Coins">
                            @error('coins')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="giveaway">Giveaway</label>
                            <input type="text" class="form-control input-square" id="giveaway" name="giveaway"
                                value="{{ old('giveaway', $coupon->giveaway) }}" placeholder="Giveaway">
                            @error('giveaway')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="upload-section">
                            <label for="pkg_image_url" class="form-label">Upload Image</label>
                            <div class="file-upload-wrapper">
                                <input class="form-control" type="file" id="pkg_image_url" name="pkg_image_url"
                                    accept=".jpg,.jpeg,.png">
                                <div class="business-logo-container">
                                    <div class="placeholder-box business-logo-placeholder {{ $coupon->pkg_image_url ? 'has-image active' : '' }}"
                                        id="pkg_image_url-placeholder">
                                        @if ($coupon->pkg_image_url)
                                            <img src="{{ asset($coupon->pkg_image_url) }}" alt="Coupon Image">
                                            <button class="remove-btn"
                                                onclick="removeImage('pkg_image_url', 'pkg_image_url-placeholder')">×</button>
                                        @else
                                            <div class="placeholder-icon">📷</div>
                                            <button class="remove-btn"
                                                onclick="removeImage('pkg_image_url', 'pkg_image_url-placeholder')">×</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="upload-text">Click to upload logo</div>
                            <div id="pkg_image_url_error" class="error-message d-none"></div>
                            @error('pkg_image_url')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select 1 image (JPG, JPEG, PNG only, max 5MB)</small>
                        </div>
                        <div class="form-group">
                            <label for="label_popular">Label Popular</label>
                            <input type="text" class="form-control input-square" id="label_popular" name="label_popular"
                                value="{{ old('label_popular', $coupon->label_popular) }}" placeholder="Label Popular">
                            @error('label_popular')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="label_color">Label Color</label>
                            <input type="text" class="form-control input-square" id="label_color" name="label_color"
                                value="{{ old('label_color', $coupon->label_color) }}" placeholder="Label Color">
                            @error('label_color')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="price_per_coin">Price Per Coin</label>
                            <input type="text" class="form-control input-square" id="price_per_coin"
                                name="price_per_coin" value="{{ old('price_per_coin', $coupon->price_per_coin) }}"
                                placeholder="Price Per Coin">
                            @error('price_per_coin')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="total_price">Total Price</label>
                            <input type="text" class="form-control input-square" id="total_price" name="total_price"
                                value="{{ old('total_price', $coupon->total_price) }}" placeholder="Total Price">
                            @error('total_price')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="product_id">Product ID</label>
                            <input type="text" class="form-control input-square" id="product_id" name="product_id"
                                value="{{ old('product_id', $coupon->product_id) }}" placeholder="Product ID">
                            @error('product_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-action">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Trigger file input click when placeholder-box is clicked
        document.getElementById('pkg_image_url-placeholder').addEventListener('click', function() {
            document.getElementById('pkg_image_url').click();
        });

        // Handle file selection and preview
        document.getElementById('pkg_image_url').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const placeholder = document.getElementById('pkg_image_url-placeholder');
            const errorDiv = document.getElementById('pkg_image_url_error');

            if (file) {
                // Validate file
                if (!validateFile(file)) {
                    errorDiv.textContent = 'Invalid file. Please upload a JPG, JPEG, or PNG file (max 5MB).';
                    errorDiv.classList.remove('d-none');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    placeholder.innerHTML = `
                        <img src="${e.target.result}" alt="Image">
                        <button class="remove-btn" onclick="removeImage('pkg_image_url', 'pkg_image_url-placeholder')">×</button>
                    `;
                    placeholder.classList.add('has-image', 'active');
                    errorDiv.classList.add('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // File validation function
        function validateFile(file) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            return file && allowedTypes.includes(file.type) && file.size <= maxSize;
        }

        // Remove image function
        function removeImage(inputId, placeholderId) {
            const input = document.getElementById(inputId);
            const placeholder = document.getElementById(placeholderId);
            const errorDiv = document.getElementById(`${inputId}_error`);

            // Clear the file input
            input.value = '';

            // Reset the placeholder
            placeholder.innerHTML = `
                <div class="placeholder-icon">📷</div>
                <button class="remove-btn" onclick="removeImage('${inputId}', '${placeholderId}')">×</button>
            `;
            placeholder.classList.remove('has-image', 'active');
            errorDiv.classList.add('d-none');
        }

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
