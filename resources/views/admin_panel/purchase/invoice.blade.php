@include('admin_panel.include.header_include')

<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="card p-4" id="invoice">
                <div class="card-body">
                    
                    <!-- Centered Logo -->
                    <div class="text-center mb-4">
                        <img src="{{ url('logo.jpeg') }}" alt="Logo" style="max-width: 150px;">
                    </div>
                    
                    <!-- Invoice Header -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Invoice #: {{ $purchase->invoice_number }}</h5>
                            <h5>Purchase Date: {{ $purchase->purchase_date }}</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <h5>Party Code: {{ $purchase->party_code ?? 'N/A' }}</h5>
                            <h5>Party Name: {{ $purchase->party_name }}</h5>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Rate</th>
                                <th>Carton</th>
                                <th>PCS</th>
                                <th>Gross</th>
                                <th>Discount</th>
                                <th>Rs Amount</th>
                                <th>Pcs in Carton</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($purchase->item) as $index => $item)
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td>{{ json_decode($purchase->rate)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->carton_qty)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->pcs)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->gross_total)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->discount)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->amount)[$index] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($purchase->pcs_carton)[$index] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <!-- Grand Total -->
                    <h4 class="text-end mt-3 fw-bold">Grand Total: {{ $purchase->grand_total }}</h4>
                </div>
            </div>

            <!-- Print Button (Hidden in Print) -->
            <div class="text-end mt-4 no-print">
                <button onclick="printInvoice()" class="btn btn-primary">
                    <i class="fa fa-print"></i> Print Invoice
                </button>
            </div>

        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')

<!-- CSS to Hide Navbar & Print Button During Print -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoice, #invoice * {
            visibility: visible;
        }
        #invoice {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    function printInvoice() {
        window.print();
    }
</script>
