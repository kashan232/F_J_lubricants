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
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control form-control-lg" name="invoice_number">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control form-control-lg" name="purchase_date">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Party Code</label>
                                <input type="text" class="form-control form-control-lg" name="party_code">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Party Name</label>
                                <input type="text" class="form-control form-control-lg" name="party_name">
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
                                        <th>Pcx</th>
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
                                            <select class="form-control form-control-lg category-select" style="width: 150px;">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><select class="form-control form-control-lg subcategory-select" style="width: 150px;">
                                                <option>Select Subcategory</option>
                                            </select></td>
                                        <td><select class="form-control form-control-lg item-select" style="width: 180px;">
                                                <option>Select Item</option>
                                            </select></td>
                                        <td><input type="number" class="form-control form-control-lg rate" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg carton-qty" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg pcx" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg gross-total" style="width: 100px;" readonly></td>
                                        <td><input type="number" class="form-control form-control-lg discount" style="width: 100px;"></td>
                                        <td><input type="number" class="form-control form-control-lg amount" style="width: 100px;" readonly></td>
                                        <td><input type="number" class="form-control form-control-lg pcs-carton" style="width: 100px;" readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="8" class="text-end fw-bold">Grand Total:</td>
                                        <td colspan="2"><input type="number" class="form-control form-control-lg fw-bold text-center" id="grandTotal" readonly></td>
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
        document.getElementById('addRow').addEventListener('click', function() {
            const newRow = `
        <tr>
            <td>
                <select class="form-control form-control-lg category-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-control form-control-lg subcategory-select">
                    <option>Select Subcategory</option>
                </select>
            </td>
            <td>
                <select class="form-control form-control-lg item-select">
                    <option>Select Item</option>
                </select>
            </td>
            <td><input type="number" class="form-control form-control-lg rate"></td>
            <td><input type="number" class="form-control form-control-lg carton-qty"></td>
            <td><input type="number" class="form-control form-control-lg pcx" readonly></td>
            <td><input type="number" class="form-control form-control-lg gross-total" readonly></td>
            <td><input type="number" class="form-control form-control-lg discount"></td>
            <td><input type="number" class="form-control form-control-lg amount" readonly></td>
            <td><input type="number" class="form-control form-control-lg pcs-carton" readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Delete</button></td>
        </tr>`;
            document.querySelector("#purchaseTable tbody").insertAdjacentHTML('beforeend', newRow);
        });

        // Remove row functionality
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
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
                        subCategoryDropdown.empty().append('<option value="">Select Sub Category</option>');
                        $.each(response, function(index, name) {
                            subCategoryDropdown.append(`<option value="${name}">${name}</option>`);
                        });
                    }
                });
            } else {
                subCategoryDropdown.empty().append('<option value="">Select Sub Category</option>');
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
                        itemDropdown.empty().append('<option value="">Select Item</option>');
                        $.each(response, function(id, itemName) {
                            itemDropdown.append(`<option value="${id}" data-pcs="${id}">${itemName}</option>`);
                        });
                    }
                });
            } else {
                itemDropdown.empty().append('<option value="">Select Item</option>');
            }
        });

        // Fetch PCS when Item is Selected
        $(document).on('change', '.item-select', function() {
            let pcsValue = $(this).find(":selected").data('pcs');
            $(this).closest('tr').find('.pcx').val(pcsValue);
        });

    });
</script>