@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div class="page-title">
                    <h4>Purchase Management</h4>
                    <h6>Manage Purchases Efficiently</h6>
                </div>
            </div>

            <div class="card p-4">
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
                                    <th>Invoice Number</th>
                                    <th>Purchase Date</th>
                                    <th>Party Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Item</th>
                                    <th>Rate</th>
                                    <th>Carton Qty</th>
                                    <th>Pcs</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->invoice_number }}</td>
                                    <td>{{ $purchase->purchase_date }}</td>
                                    <td>{{ $purchase->party_name }}</td>
                                    <td>
                                        @foreach(json_decode($purchase->category) as $category)
                                        {{ $category }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->subcategory) as $subcategory)
                                        {{ $subcategory }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->item) as $item)
                                        {{ $item }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->rate) as $rate)
                                        {{ $rate }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->carton_qty) as $carton_qty)
                                        {{ $carton_qty }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->pcs) as $pcs)
                                        {{ $pcs }},
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach(json_decode($purchase->amount) as $amount)
                                        {{ $amount }},
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ route('purchase.invoice', $purchase->id) }}" class="btn btn-primary btn-sm text-white">
                                             Invoice
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin_panel.include.footer_include')