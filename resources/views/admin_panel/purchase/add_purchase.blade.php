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
                    <form action="{{ route('store-Purchase') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date">

                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Party Code</label>
                                <input type="text" class="form-control party_code" name="party_code" readonly>

                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Party Name</label>
                                <!-- <input type="text"  name="party_name"> -->
                                <select name="party_name" id="party_name" class="form-control vendor-select">
                                    <option value="" selected disabled>Choose One</option> 
                                    @foreach($Vendors as $Vendor)
                                    <option value="{{ $Vendor->id }}" data-code="{{ $Vendor->Party_code }}">{{ $Vendor->Party_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center" id="purchaseTable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Item</th>
                                        <th>Rate (Per Carton)</th>
                                        <th>Carton Qty</th>
                                        <th>Pcs</th>
                                        <th>Gross Total</th>
                                        <th>Discount</th>
                                        <th>Amount</th>
                                        <th>Pcs/Carton</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control form-control-lg category-select" name="category[]" style="width: 150px;">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg subcategory-select" name="subcategory[]" style="width: 150px;">
                                                <option>Select Subcategory</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-lg item-select" name="item[]" style="width: 180px;">
                                                <option>Select Item</option>
                                            </select>
                                        </td>
                                        <td><input type="number" class="form-control form-control-lg rate" name="rate[]" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg carton-qty" name="carton_qty[]" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg pcx" name="pcs[]" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg gross-total" name="gross_total[]" style="width: 100px;" readonly></td>
                                        <td><input type="number" class="form-control form-control-lg discount" name="discount[]" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg amount" name="amount[]" style="width: 100px;" readonly></td>
                                        <td><input type="number" class="form-control form-control-lg pcs-carton" name="pcs_carton[]" style="width: 100px;" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" class="text-end fw-bold">Grand Total:</td>
                                        <td colspan="2"><input type="number" class="form-control form-control-lg fw-bold text-center" id="grandTotal" name="grand_total" readonly></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success mt-3" id="addRow">Add More</button>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin_panel.include.footer_include')
<script>
    $(document).ready(function() {
        // Add New Row
        $(document).on('click', '#addRow', function() {
            let newRow = `
    <tr>
        <td>
            <select class="form-control form-control-lg category-select" name="category[]" style="width: 150px;">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="form-control form-control-lg subcategory-select" name="subcategory[]" style="width: 150px;">
                <option value="">Select Subcategory</option>
            </select>
        </td>
        <td>
            <select class="form-control form-control-lg item-select" name="item[]" style="width: 180px;">
                <option value="">Select Item</option>
            </select>
        </td>
        <td><input type="number" class="form-control form-control-lg rate" name="rate[]" style="width: 100px;"></td>
        <td><input type="number" class="form-control form-control-lg carton-qty" name="carton_qty[]" style="width: 100px;"></td>
        <td><input type="number" class="form-control form-control-lg pcx" name="pcs[]" style="width: 100px;"></td>
        <td><input type="number" class="form-control form-control-lg gross-total" name="gross_total[]" style="width: 100px;" readonly></td>
        <td><input type="number" class="form-control form-control-lg discount" name="discount[]" style="width: 100px;"></td>
        <td><input type="number" class="form-control form-control-lg amount" name="amount[]" style="width: 100px;" readonly></td>
        <td><input type="number" class="form-control form-control-lg pcs-carton" name="pcs_carton[]" style="width: 100px;" readonly></td>
        <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
    </tr>`;

            $("#purchaseTable tbody").append(newRow);
        });


        // Remove row functionality
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal(); // Recalculate grand total after row removal
        });

        // Fetch Subcategories on Category Change
        $(document).on('change', '.category-select', function() {
            let categoryName = $(this).val();
            let subCategoryDropdown = $(this).closest('tr').find('.subcategory-select');

            if (categoryName) {
                $.ajax({
                    url: "{{ route('get.subcategories', ':categoryname') }}".replace(':categoryname', categoryName),
                    type: 'GET',
                    success: function(response) {
                        subCategoryDropdown.html('<option value="">Select Sub Category</option>');
                        $.each(response, function(index, name) {
                            subCategoryDropdown.append(`<option value="${name}">${name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Error fetching subcategories.');
                    }
                });
            } else {
                subCategoryDropdown.html('<option value="">Select Sub Category</option>');
            }
        });

        // Fetch Items on Subcategory Change
        $(document).on('change', '.subcategory-select', function() {
            let subCategoryName = $(this).val();
            let categoryName = $(this).closest('tr').find('.category-select').val();
            let itemDropdown = $(this).closest('tr').find('.item-select');

            if (subCategoryName && categoryName) {
                $.ajax({
                    url: "{{ route('get.items') }}",
                    type: 'GET',
                    data: {
                        category_name: categoryName,
                        sub_category_name: subCategoryName
                    },
                    success: function(response) {
                        itemDropdown.html('<option value="">Select Item</option>');
                        $.each(response, function(index, item) {
                            itemDropdown.append(`<option value="${item.item_name}" data-pcs="${item.pcs_in_carton}">${item.item_name}</option>`);
                        });
                    },
                    error: function() {
                        alert('Error fetching items.');
                    }
                });
            } else {
                itemDropdown.html('<option value="">Select Item</option>');
            }
        });

        // Fetch PCS when Item is Selected
        $(document).on('change', '.item-select', function() {
            let pcsValue = $(this).find(":selected").data('pcs') || 0;
            $(this).closest('tr').find('.pcs-carton').val(pcsValue);
        });

        $(document).on('change', '.vendor-select', function() {
            let partycode = $(this).find(":selected").data('code') || 0;
            $(".party_code").val(partycode);
        });
        
        // Auto-Calculate Amount and Gross Total
        $(document).on('input', '.rate, .carton-qty, .discount', function() {
            let row = $(this).closest('tr');
            let rate = parseFloat(row.find('.rate').val()) || 0;
            let cartonQty = parseFloat(row.find('.carton-qty').val()) || 0;
            let pcsPerCarton = parseFloat(row.find('.pcs-carton').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;

            // Calculate Gross Total (Rate * Carton Qty)
            let grossTotal = rate * cartonQty;
            row.find('.gross-total').val(grossTotal.toFixed(2));

            // Calculate Amount (Gross Total - Discount)
            let amount = grossTotal - discount;
            row.find('.amount').val(amount.toFixed(2));

            calculateGrandTotal(); // Recalculate Grand Total whenever there is a change
        });

        // Calculate Grand Total
        function calculateGrandTotal() {
            let grandTotal = 0;
            $(".amount").each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $("#grandTotal").val(grandTotal.toFixed(2));
        }
    });
</script>