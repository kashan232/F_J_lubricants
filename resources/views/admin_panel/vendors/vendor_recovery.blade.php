@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Vendor Payments</h4>
                    <h6>Track all Payments from salesmen</h6>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    @if (session()->has('success'))
                    <div class="alert alert-success">
                        <strong>Success!</strong> {{ session('success') }}.
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Party Code</th>
                                    <th>Party Name</th>
                                    <th>Paid Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($VendorPayments as $key => $VendorPayment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $VendorPayment->payment_date }}</td>
                                    <td>{{ $VendorPayment->vendor->Party_code ?? 'N/A' }}</td>
                                    <td>{{ $VendorPayment->vendor->Party_name ?? 'N/A' }}</td>
                                    <td>{{ $VendorPayment->amount_paid }}</td>
                                    <td>{{ $VendorPayment->description }}</td>
                                </tr>
                                @endforeach
                                @if($VendorPayments->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">No Payments found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')
