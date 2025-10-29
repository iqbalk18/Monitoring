<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel & Save Manual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    {{-- Card Utama --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">📂 Import Data</h4>
        </div>
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    ❌ {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h5>Import File Excel</h5>
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Choose File Excel (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" class="form-control" id="file" required>
                </div>
                <button type="submit" class="btn btn-dark">🚀 Import Data</button>
                <a href="{{ url('download-json') }}" class="btn btn-dark text-white">📥 Download JSON</a>
            </form>

            <hr>

            <h5>Save Data Manual</h5>
            <form action="{{ route('save_manual') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="materialDocument" class="form-label">Material Document</label>
                    <input type="text" name="materialDocument" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="materialDocumentYear" class="form-label">Material Document Year</label>
                    <input type="text" name="materialDocumentYear" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="plant" class="form-label">Plant</label>
                    <input type="text" name="plant" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="documentDate" class="form-label">Document Date</label>
                    <input type="date" name="documentDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="postingDate" class="form-label">Posting Date</label>
                    <input type="date" name="postingDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="goodMovementText" class="form-label">Good Movement Text</label>
                    <input type="text" name="goodMovementText" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="vendor" class="form-label">Vendor</label>
                    <input type="text" name="vendor" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="purchaseOrder" class="form-label">Purchase Order</label>
                    <input type="text" name="purchaseOrder" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="reservation" class="form-label">Reservation</label>
                    <input type="text" name="reservation" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="outboundDelivery" class="form-label">Outbound Delivery</label>
                    <input type="text" name="outboundDelivery" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="sapTransactionDate" class="form-label">SAP Transaction Date</label>
                    <input type="date" name="sapTransactionDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="sapTransactionTime" class="form-label">SAP Transaction Time</label>
                    <input type="time" name="sapTransactionTime" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" name="user" class="form-control">
                </div>
                <button type="submit" class="btn btn-dark">💾 Save</button>
            </form>

        </div>
    </div>

</div>

</body>
</html>
