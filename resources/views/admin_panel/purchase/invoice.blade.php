@include('admin_panel.include.header_include')

<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="card p-4" id="invoice">
                <div class="card-body">

                    <!-- Header Section -->
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-4">
                            <img src="{{ url('logo.jpeg') }}" alt="Logo" style="max-width: 120px;">
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="fw-bold">FJ Lubricants</h4>
                            <p>6-B Block-E, Latifabad No. 08, Hyderabad</p>
                            <p>Phone: 0314-4021603 / 0334-2611233</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h4 class="fw-bold">MACKSOL</h4>
                        </div>
                    </div>

                    <!-- Bottom Border -->
                    <div class="border-bottom border-2 mb-3"></div>

                    <!-- Invoice Title -->
                    <h3 class="text-center fw-bold my-3">Purchase Invoice</h3>

                    <!-- Invoice Details -->
                    <div class="border p-3 mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Invoice #: {{ $purchase->invoice_number }}</h5>
                                <h5>Purchase Date: {{ $purchase->purchase_date }}</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <h5>Party Code: {{ $purchase->party_code ?? 'N/A' }}</h5>
                                <h5>Party Name: {{ $purchase->party_name }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th>Item Name</th>
                                <th>Pcs in Carton</th>
                                <th>Carton Qty</th>
                                <th>PCS Qty</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(json_decode($purchase->item) as $index => $item)
                            <tr>
                                <td>{{ $item }}</td>
                                <td class="text-center">{{ json_decode($purchase->pcs_carton)[$index] ?? 'N/A' }}</td>
                                <td class="text-center">{{ json_decode($purchase->carton_qty)[$index] ?? 'N/A' }}</td>
                                <td class="text-center">{{ json_decode($purchase->pcs)[$index] ?? 'N/A' }}</td>
                                <td class="text-center">{{ json_decode($purchase->rate)[$index] ?? 'N/A' }}</td>
                                <td class="text-center">{{ json_decode($purchase->discount)[$index] ?? 'N/A' }}</td>
                                <td class="text-end">{{ json_decode($purchase->amount)[$index] ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5"></td>
                                <td class="fw-bold text-center">Gross Amount:</td>
                                <td class="fw-bold text-end">{{ $purchase->gross_total_sum }}</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="fw-bold text-center">Discount:</td>
                                <td class="fw-bold text-end">{{ $purchase->discount_total_sum }}</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td>
                                <td class="fw-bold text-center">Net Total:</td>
                                <td class="fw-bold text-end">{{ $purchase->grand_total }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Footer Section -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-start">
                            <h5 class="fw-bold">For FJL Signature</h5>
                            <p>Data Feeder Hyderabad</p>
                        </div>
                    </div>
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

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #invoice,
        #invoice * {
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