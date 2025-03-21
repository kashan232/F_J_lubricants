@include('admin_panel.include.header_include')

<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="card p-4 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center fw-bold mb-4 text-primary">Distributor Ledger Report</h3>

                    <form id="ledgerSearchForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="fw-bold" for="distributor">Select Distributor</label>
                                <select id="distributor" class="form-control">
                                    <option value="">-- Select Distributor --</option>
                                    @foreach($Distributors as $distributor)
                                    <option value="{{ $distributor->id }}"
                                        data-contact="{{ $distributor->Contact }}"
                                        data-city="{{ $distributor->City }}"
                                        data-area="{{ $distributor->Area }}">
                                        {{ $distributor->Customer }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold">Contact</label>
                                <input type="text" id="contact" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="fw-bold">City</label>
                                <input type="text" id="city" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="fw-bold">Area</label>
                                <input type="text" id="area" class="form-control bg-light" readonly>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" id="searchLedger" class="btn btn-primary btn-lg px-5">
                                Search
                            </button>
                        </div>
                    </form>


                    <h4 class="text-warning fw-bold text-center mt-4">Distributor Sales</h4>
                    <table class="table table-striped table-hover">
                        <thead class="table-secondary">
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Booker</th>
                                <th>Salesman</th>
                                <th>Grand Total</th>
                                <th>Discount</th>
                                <th>Scheme</th>
                                <th>Net Amount</th>
                            </tr>
                        </thead>
                        <tbody id="salesData"></tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="7" class="text-end fw-bold">Total Sales:</td>
                                <td class="fw-bold text-warning" id="totalSales">Rs. 0</td>
                            </tr>
                        </tfoot>
                    </table>


                    <hr class="my-4">

                    <div id="ledgerResult" style="display: none;">
                        <h4 class="text-success fw-bold text-center">Recoveries</h4>
                        <table class="table table-striped table-hover">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>Amount Paid</th>
                                    <th>Salesman</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody id="recoveriesData"></tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Recoveries:</td>
                                    <td class="fw-bold text-success" id="totalRecoveries">Rs. 0</td>
                                </tr>
                            </tfoot>
                        </table>

                        <h4 class="text-primary fw-bold text-center mt-4">Distributor Ledger Summary</h4>
                        <table class="table table-bordered">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Previous Balance</th>
                                    <th>Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody id="ledgerData"></tbody>
                        </table>
                    </div>

                    <!-- Ledger Summary at the End -->
                    <div id="ledgerSummary" class="text-center mt-5">
                        <h4 class="fw-bold text-success">Total Recoveries: <span id="totalRecoveriesPrint">Rs. 0</span></h4>
                        <h4 class="fw-bold text-primary">Total Previous Balance: <span id="prevBalancePrint">Rs. 0</span></h4>
                        <h4 class="fw-bold text-danger">Total Closing Balance: <span id="closingBalancePrint">Rs. 0</span></h4>

                        <!-- <button id="printLedger" class="btn btn-danger mt-3">Print Ledger</button> -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')

<script>
    $(document).ready(function() {
        $('#distributor').change(function() {
            var selected = $(this).find(':selected');
            $('#contact').val(selected.data('contact'));
            $('#city').val(selected.data('city'));
            $('#area').val(selected.data('area'));
        });

        $('#searchLedger').click(function() {
            var distributorId = $('#distributor').val();
            if (!distributorId) {
                alert('Please select a distributor.');
                return;
            }

            $.ajax({
                url: "{{ route('fetch-distributor-ledger') }}",
                type: "GET",
                data: {
                    distributor_id: distributorId
                },
                success: function(response) {
                    $('#ledgerResult').show();

                    // Recoveries Data
                    let totalRecovery = 0;
                    let recoveriesHTML = response.recoveries.map(recovery => {
                        totalRecovery += parseFloat(recovery.amount_paid);
                        return `
                    <tr>
                        <td>${recovery.id}</td>
                        <td><b class="text-success">Rs. ${recovery.amount_paid}</b></td>
                        <td>${recovery.salesman}</td>
                        <td>${recovery.date}</td>
                    </tr>
                `;
                    }).join('');

                    $('#recoveriesData').html(recoveriesHTML);
                    $('#totalRecoveries').text(`Rs. ${totalRecovery.toFixed(2)}`);
                    $('#totalRecoveriesPrint').text(`Rs. ${totalRecovery.toFixed(2)}`);

                    // Ledger Data
                    $('#ledgerData').html(`
                <tr>
                    <td class="text-primary fw-bold">Rs. ${response.previous_balance}</td>
                    <td class="text-danger fw-bold">Rs. ${response.closing_balance}</td>
                </tr>
            `);

                    $('#prevBalancePrint').text(`Rs. ${response.previous_balance}`);
                    $('#closingBalancePrint').text(`Rs. ${response.closing_balance}`);

                    // Sales Data
                    let totalSales = 0;
                    let salesHTML = response.sales.map(sale => {
                        totalSales += parseFloat(sale.net_amount);
                        return `
                    <tr>
                        <td>${sale.invoice_number}</td>
                        <td>${sale.Date}</td>
                        <td>${sale.Booker}</td>
                        <td>${sale.Saleman}</td>
                        <td>Rs. ${sale.grand_total}</td>
                        <td>Rs. ${sale.discount_value}</td>
                        <td>Rs. ${sale.scheme_value}</td>
                        <td><b class="text-warning">Rs. ${sale.net_amount}</b></td>
                    </tr>
                `;
                    }).join('');

                    $('#salesData').html(salesHTML);
                    $('#totalSales').text(`Rs. ${totalSales.toFixed(2)}`);
                }
            });
        });

        $('#printLedger').click(function() {
            $('body').addClass('print-mode');
            window.print();
            $('body').removeClass('print-mode');
        });
    });

    // Print styling
    $('<style>')
        .prop('type', 'text/css')
        .html(`
        @media print {
            #ledgerSummary {
                display: block !important;
                text-align: center;
                margin-top: 50px;
                font-size: 22px;
            }
        }
    `).appendTo('head');
</script>