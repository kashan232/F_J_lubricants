@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product List</h4>
                    <h6>Manage Products</h6>
                </div>
                <div class="page-btn">
                    <button class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <img src="assets/img/icons/plus.svg" class="me-1" alt="img">Add Product
                    </button>
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
                                    <th>Category</th>
                                    <th>Sub-Category</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Size</th>
                                    <th>pcs_in_carton</th>
                                    <th>Wholesale Price</th>
                                    <th>Retail Price</th>
                                    <th>Initial Stock</th>
                                    <th>Alert Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $key => $product)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ $product->sub_category }}</td>
                                    <td>{{ $product->item_code }}</td>
                                    <td>{{ $product->item_name }}</td>
                                    <td>{{ $product->size }}</td>
                                    <td>{{ $product->pcs_in_carton }}</td>
                                    <td>{{ $product->wholesale_price }}</td>
                                    <td>{{ $product->retail_price }}</td>
                                    <td>{{ $product->initial_stock }}</td>
                                    <td>{{ $product->alert_quantity }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editProductBtn" 
                                                data-id="{{ $product->id }}"
                                                data-category="{{ $product->category }}"
                                                data-sub_category="{{ $product->sub_category }}"
                                                data-item_code="{{ $product->item_code }}"
                                                data-item_name="{{ $product->item_name }}"
                                                data-size_id="{{ $product->size }}"
                                                data-pcs_in_carton="{{ $product->pcs_in_carton }}"
                                                data-wholesale_price="{{ $product->wholesale_price }}"
                                                data-retail_price="{{ $product->retail_price }}"
                                                data-initial_stock="{{ $product->initial_stock }}"
                                                data-alert_quantity="{{ $product->alert_quantity }}"
                                                data-bs-toggle="modal" data-bs-target="#editProductModal">
                                                Edit
                                            </button>
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

<div class="modal fade bd-example-modal-lg" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('store-product') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category" id="categorySelect" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sub-Category</label>
                            <select class="form-control" name="sub_category" id="subCategorySelect" required>
                                <option value="">Select Sub-Category</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Code</label>
                            <input type="text" class="form-control" name="item_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="item_name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Size</label>
                            <select class="form-control" name="size" id="sizeSelect" required>
                                <option value="">Select Size</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->size_name }}">{{ $size->size_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Carton Quantity</label>
                            <input type="number" class="form-control" name="carton_quantity" id="carton_quantity" required>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pieces per Carton</label>
                            <input type="number" class="form-control" name="pcs_in_carton" id="pieces_per_carton" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pieces</label>
                            <input type="number" class="form-control" name="pcs" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Wholesale Price</label>
                            <input type="number" step="0.01" class="form-control" name="wholesale_price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Retail Price</label>
                            <input type="number" step="0.01" class="form-control" name="retail_price" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" name="initial_stock" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alert Quantity</label>
                            <input type="number" class="form-control" name="alert_quantity" required>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('product.update') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" id="edit_product_id">
                <div class="modal-body">
                    <div class="row">
                    <!-- Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category" id="edit_category" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sub-Category -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sub-Category</label>
                            <select class="form-control" name="sub_category" id="edit_sub_category" required>
                                <option value="">Select Sub-Category</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Item Code -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Code</label>
                            <input type="text" class="form-control" name="item_code" id="edit_item_code" required>
                        </div>
                        <!-- Item Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="item_name" id="edit_item_name" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Size -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Size</label>
                            <select class="form-control" name="size_id" id="edit_size" required>
                                <option value="">Select Size</option>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Carton Quantity</label>
                            <input type="number" class="form-control" name="carton_quantity" id="carton_quantity" required>
                        </div>    
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pieces per Carton</label>
                            <input type="number" class="form-control" name="pcs_in_carton" id="pieces_per_carton" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pieces</label>
                            <input type="number" class="form-control" name="pcs" id="edit_pcs" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Wholesale Price</label>
                            <input type="number" step="0.01" class="form-control" name="wholesale_price" id="edit_wholesale_price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Retail Price</label>
                            <input type="number" step="0.01" class="form-control" name="retail_price" id="edit_retail_price" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Initial Stock</label>
                            <input type="number" class="form-control" name="initial_stock" id="edit_initial_stock" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alert Quantity</label>
                            <input type="number" class="form-control" name="alert_quantity" id="edit_alert_quantity" required>
                        </div>
                    </div>
                    
                </div>

                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')

<script>
    // Script for editing a product
    $(document).on("click", ".editProductBtn", function() {
    $("#edit_product_id").val($(this).data("id"));
    $("#edit_category").val($(this).data("category"));
    $("#edit_sub_category").val($(this).data("sub_category"));
    $("#edit_item_code").val($(this).data("item_code"));
    $("#edit_item_name").val($(this).data("item_name"));
    $("#edit_size").val($(this).data("size_id"));
    $("#edit_pcs").val($(this).data("pcs"));
    $("#edit_wholesale_price").val($(this).data("wholesale_price"));
    $("#edit_retail_price").val($(this).data("retail_price"));
    $("#edit_initial_stock").val($(this).data("initial_stock"));
    $("#edit_alert_quantity").val($(this).data("alert_quantity"));
});


</script>
<script>

$(document).ready(function () {
    // Add Product Modal: Fetch Subcategories on Category Change
    $('#categorySelect').change(function () {
        var categoryId = $(this).val();
        $('#subCategorySelect').html('<option value="">Loading...</option>');

        if (categoryId) {
            $.ajax({
                url: "{{ route('fetch-subcategories') }}",
                type: "GET",
                data: { category_id: categoryId },
                success: function (data) {
                    $('#subCategorySelect').html('<option value="">Select Sub-Category</option>');
                    $.each(data, function (key, subCategory) {
                        $('#subCategorySelect').append('<option value="' + subCategory.sub_category_name + '">' + subCategory.sub_category_name + '</option>');
                    });
                },
                error: function () {
                    alert('Error fetching subcategories.');
                }
            });
        } else {
            $('#subCategorySelect').html('<option value="">Select Sub-Category</option>');
        }
    });

    // Edit Product Modal: Fetch Subcategories when Category is Changed
    $('#edit_category').change(function () {
        var categoryId = $(this).val();
        $('#edit_sub_category').html('<option value="">Loading...</option>');

        if (categoryId) {
            $.ajax({
                url: "{{ route('fetch-subcategories') }}",
                type: "GET",
                data: { category_id: categoryId },
                success: function (data) {
                    $('#edit_sub_category').html('<option value="">Select Sub-Category</option>');
                    $.each(data, function (key, subCategory) {
                        $('#edit_sub_category').append('<option value="' + subCategory.sub_category_name + '">' + subCategory.sub_category_name + '</option>');
                    });
                },
                error: function () {
                    alert('Error fetching subcategories.');
                }
            });
        } else {
            $('#edit_sub_category').html('<option value="">Select Sub-Category</option>');
        }
    });

    // When clicking "Edit" button, load subcategories and select the right one
    $(document).on("click", ".editProductBtn", function () {
        var productId = $(this).data("id");
        var selectedCategory = $(this).data("category");
        var selectedSubCategory = $(this).data("sub_category");

        $("#edit_product_id").val(productId);
        $("#edit_category").val(selectedCategory).change(); // Trigger category change event

        // Fetch subcategories based on the selected category
        $.ajax({
            url: "{{ route('fetch-subcategories') }}",
            type: "GET",
            data: { category_id: selectedCategory },
            success: function (data) {
                $('#edit_sub_category').html('<option value="">Select Sub-Category</option>');
                $.each(data, function (key, subCategory) {
                    var isSelected = subCategory.sub_category_name === selectedSubCategory ? "selected" : "";
                    $('#edit_sub_category').append('<option value="' + subCategory.sub_category_name + '" ' + isSelected + '>' + subCategory.sub_category_name + '</option>');
                });
            },
            error: function () {
                alert('Error fetching subcategories.');
            }
        });

        $("#edit_item_code").val($(this).data("item_code"));
        $("#edit_item_name").val($(this).data("item_name"));
        $("#edit_size").val($(this).data("size_id"));
        $("#edit_pcs").val($(this).data("pcs"));
        $("#carton_quantity").val($(this).data("carton_quantity"));
        $("#pieces_per_carton").val($(this).data("pieces_per_carton"));
        $("#loose_pieces").val($(this).data("loose_pieces"));
        $("#edit_wholesale_price").val($(this).data("wholesale_price"));
        $("#edit_retail_price").val($(this).data("retail_price"));
        $("#edit_initial_stock").val($(this).data("initial_stock"));
        $("#edit_alert_quantity").val($(this).data("alert_quantity"));
    });
});


</script>