<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client - ReturnPro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            width: 250px; min-height: 100vh;
            background: #1e3c72; position: fixed;
            top: 0; left: 0; z-index: 100;
        }
        .sidebar .brand {
            padding: 20px; color: #fff;
            font-size: 20px; font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px; display: block;
            text-decoration: none; font-size: 14px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1); color: #fff;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .main-content { margin-left: 250px; padding: 20px; }
        .topbar {
            background: #fff; padding: 12px 20px;
            border-radius: 8px; margin-bottom: 20px;
            display: flex; justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .section-title {
            font-size: 13px; font-weight: 700;
            text-transform: uppercase; color: #1e3c72;
            border-bottom: 2px solid #1e3c72;
            padding-bottom: 5px; margin-bottom: 15px;
        }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="brand"><i class="fas fa-file-invoice me-2"></i>ReturnPro</div>
    <nav class="mt-2">
        <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="{{ route('clients.index') }}" class="nav-link active"><i class="fas fa-users"></i> Clients</a>
        <a href="#" class="nav-link"><i class="fas fa-file-alt"></i> Income Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-receipt"></i> Sales Tax</a>
        <a href="#" class="nav-link"><i class="fas fa-file-invoice-dollar"></i> Vouchers</a>
        <a href="#" class="nav-link"><i class="fas fa-money-bill-wave"></i> Collections</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Expenses</a>
        <a href="#" class="nav-link"><i class="fas fa-folder-open"></i> Documents</a>
        <a href="#" class="nav-link"><i class="fas fa-bell"></i> Notices</a>
        <a href="#" class="nav-link"><i class="fas fa-tasks"></i> Tasks</a>
        <a href="#" class="nav-link"><i class="fas fa-chart-pie"></i> Reports</a>
        <a href="#" class="nav-link"><i class="fas fa-cog"></i> Settings</a>
    </nav>
</div>

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Client: {{ $client->name }}</h5>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('clients.update', $client) }}">
        @csrf
        @method('PUT')

        {{-- Basic Info --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Basic Information</div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Client Code</label>
                        <input type="text" class="form-control" value="{{ $client->client_code }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Case Number</label>
                        <input type="text" name="case_number" class="form-control"
                               value="{{ old('case_number', $client->case_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $client->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Father Name</label>
                        <input type="text" name="father_name" class="form-control"
                               value="{{ old('father_name', $client->father_name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Name</label>
                        <input type="text" name="business_name" class="form-control"
                               value="{{ old('business_name', $client->business_name) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tax Info --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Tax Information</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" class="form-control @error('cnic') is-invalid @enderror"
                               value="{{ old('cnic', $client->cnic) }}">
                        @error('cnic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">NTN</label>
                        <input type="text" name="ntn" class="form-control @error('ntn') is-invalid @enderror"
                               value="{{ old('ntn', $client->ntn) }}">
                        @error('ntn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">FBR Username</label>
                        <input type="text" name="fbr_username" class="form-control"
                               value="{{ old('fbr_username', $client->fbr_username) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">FBR Password</label>
                        <input type="text" name="fbr_password" class="form-control"
                               value="{{ old('fbr_password', $client->fbr_password) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Registration Date</label>
                        <input type="date" name="registration_date" class="form-control"
                               value="{{ old('registration_date', $client->registration_date) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Annual Fee (Rs.)</label>
                        <input type="number" name="annual_fee" class="form-control"
                               value="{{ old('annual_fee', $client->annual_fee) }}" min="0" step="0.01">
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="section-title">Contact Information</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control"
                               value="{{ old('mobile', $client->mobile) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control"
                               value="{{ old('whatsapp', $client->whatsapp) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $client->email) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $client->city) }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $client->address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="Active" {{ $client->status == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ $client->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Update Client
            </button>
            <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>