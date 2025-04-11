@include('admin_panel.include.header_include')

<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="card p-4 shadow-lg">
                <div class="card-body">
                    <h3 class="card-title text-center fw-bold mb-4 text-primary">Vendor Ledger</h3>

                    <form id="ledgerSearchForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="fw-bold" for="Vendor">Select Vendor</label>
                                <select id="Vendor" class="form-control">
                                    <option value="">-- Select Vendor --</option>
                                    @foreach($Vendors as $Vendor)
                                    <option value="{{ $Vendor->id }}"
                                        data-contact="{{ $Vendor->Party_phone }}"
                                        data-city="{{ $Vendor->City }}"
                                        data-area="{{ $Vendor->Area }}">
                                        {{ $Vendor->Party_name }}
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

                            <div class="col-md-6">
                                <label class="fw-bold">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control bg-light">
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control bg-light">
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" id="searchLedger" class="btn btn-primary btn-lg px-5">
                                Search
                            </button>
                        </div>
                    </form>

                    <div id="ledgerResult" style="display: none;">
                        <div class="ledger-container mt-4">
                            <div class="ledger-header">VENDOR LEDGER</div>
                            <div class="ledger-info">
                                <span><strong>Vendor:</strong> <span id="vendorName"></span></span>
                                <span><strong>Duration:</strong> From <span id="startDate"></span> To <span id="endDate"></span></span>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>INV-No</th>
                                        <th>Description</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="opening-balance">Opening Balance:</td>
                                        <td id="openingBalance">Rs. 0</td>
                                    </tr>
                                </thead>
                                <tbody id="ledgerData"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><strong>Totals:</strong></td>
                                        <td id="totalDebit">0</td>
                                        <td id="totalCredit">0</td>
                                        <td id="closingBalance">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')

<script>
    $(document).ready(function() {
        $('#Vendor').change(function() {
            var selected = $(this).find(':selected');
            $('#contact').val(selected.data('contact'));
            $('#city').val(selected.data('city'));
            $('#area').val(selected.data('area'));
        });

        $('#searchLedger').click(function() {
            var vendorID = $('#Vendor').val();
            var vendorName = $('#Vendor option:selected').text();
            let startDate = $('#start_date').val();
            let endDate = $('#end_date').val();
            if (!vendorID) {
                alert('Please select a Vendor.');
                return;
            }

            $.ajax({
                url: "{{ route('fetch-vendor-ledger') }}",
                type: "GET",
                data: {
                    Vendor_id: vendorID,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(response) {
                    $('#ledgerResult').show();
                    $('#vendorName').text(vendorName);
                    $('#startDate').text(response.startDate);
                    $('#endDate').text(response.endDate);

                    let openingBalance = parseFloat(response.opening_balance);
                    let balance = openingBalance;
                    let totalDebit = 0,
                        totalCredit = 0;
                    let ledgerHTML = "";

                    let allEntries = [];

                    // ✅ Opening Balance Entry
                    ledgerHTML += `
                <tr>
                    <td>${response.start_date}</td>
                    <td>-</td>
                    <td class="fw-bold">Opening Balance</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="fw-bold text-primary">Rs. ${balance.toFixed(2)}</td>
                </tr>
            `;

                    // ✅ Sales Entries
                    response.purchases.forEach(entry => {
                        allEntries.push({
                            date: entry.purchase_date,
                            type: 'purchase',
                            invoice_number: entry.invoice_number,
                            amount: parseFloat(entry.net_amount)
                        });
                    });

                    // ✅ Recovery Entries
                    response.returns.forEach(entry => {
                        allEntries.push({
                            date: entry.return_date,
                            type: 'return',
                            invoice_number: entry.return_no,
                            amount: parseFloat(entry.net_amount)
                        });
                    });

                    // ✅ Sort Entries by Date (Sales pehle, Recovery baad me agar date same ho)
                    allEntries.sort((a, b) => {
                        let dateA = new Date(a.date);
                        let dateB = new Date(b.date);
                        if (dateA - dateB === 0) {
                            return a.type === 'sale' ? -1 : 1; // Sale pehle ayegi, Recovery baad me
                        }
                        return dateA - dateB;
                    });

                    // ✅ Maintain Correct Ledger Balance
                    allEntries.forEach(entry => {
                        if (entry.type === 'purchase') {
                            let debit = entry.amount;
                            totalDebit += debit;
                            balance += debit;
                            ledgerHTML += `
        <tr>
            <td>${entry.date}</td>
            <td>${entry.invoice_number}</td>
            <td>To Purchase A/c</td>
            <td>Rs. ${debit.toFixed(2)}</td>
            <td>-</td>
            <td class="fw-bold ${balance < 0 ? 'text-danger' : 'text-success'}">Rs. ${balance.toFixed(2)}</td>
        </tr>`;
                        } else if (entry.type === 'return') {
                            let credit = entry.amount;
                            totalCredit += credit;
                            balance -= credit;
                            ledgerHTML += `
        <tr>
            <td>${entry.date}</td>
            <td>${entry.invoice_number}</td>
            <td>Purchase Return</td>
            <td>-</td>
            <td>Rs. ${credit.toFixed(2)}</td>
            <td class="fw-bold ${balance < 0 ? 'text-danger' : 'text-success'}">Rs. ${balance.toFixed(2)}</td>
        </tr>`;
                        }

                    });


                    // ✅ Update Totals
                    $('#ledgerData').html(ledgerHTML);
                    $('#openingBalance').text(`Rs. ${openingBalance.toFixed(2)}`);
                    $('#totalDebit').text(`Rs. ${totalDebit.toFixed(2)}`);
                    $('#totalCredit').text(`Rs. ${totalCredit.toFixed(2)}`);
                    $('#closingBalance').text(`Rs. ${balance.toFixed(2)}`);
                }
            });
        });
    });
</script>

<style>
    .ledger-container {
        border: 2px solid black;
        padding: 10px;
        max-width: 900px;
        margin: 20px auto;
        background: #fff;
    }

    .ledger-header {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        padding: 10px;
        border-bottom: 2px solid black;
    }

    .ledger-info {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 2px solid black;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }

    thead th {
        background: #f2f2f2;
    }

    .opening-balance {
        text-align: right;
        font-weight: bold;
        padding: 8px;
        border: 1px solid black;
    }
</style>