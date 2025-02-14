@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sub Category List</h4>
                    <h6>Manage Sub Categories</h6>
                </div>
                <div class="page-btn">
                    <button class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addSubCategoryModal">
                        <img src="assets/img/icons/plus.svg" class="me-1" alt="img"> Add Sub Category
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
                                    <th>Category Name</th>
                                    <th>Sub Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Sub_Categories as $key => $subCategory)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $subCategory->category_name }}</td>
                                    <td>{{ $subCategory->sub_category_name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editSubCategoryBtn" 
                                                data-id="{{ $subCategory->id }}" 
                                                data-category="{{ $subCategory->category_name }}" 
                                                data-subcategory="{{ $subCategory->sub_category_name }}" 
                                                data-bs-toggle="modal" data-bs-target="#editSubCategoryModal">Edit</button>
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

<!-- Add SubCategory Modal -->
<div class="modal fade" id="addSubCategoryModal" tabindex="-1" aria-labelledby="addSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('store-sub-category') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Category</label>
                        <select class="form-control" name="category_name" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($Categories as $category)
                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Category Name</label>
                        <input type="text" class="form-control" name="sub_category_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit SubCategory Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1" aria-labelledby="editSubCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sub Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('sub-category.update') }}" method="POST">
                @csrf
                <input type="hidden" name="sub_category_id" id="edit_sub_category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Category</label>
                        <select class="form-control" name="category_name" id="edit_category_name" required>
                            @foreach($Categories as $category)
                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Category Name</label>
                        <input type="text" class="form-control" name="sub_category_name" id="edit_sub_category_name" required>
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
    $(document).on("click", ".editSubCategoryBtn", function() {
        let id = $(this).data("id");
        let category = $(this).data("category");
        let subcategory = $(this).data("subcategory");

        $("#edit_sub_category_id").val(id);
        $("#edit_category_name").val(category);
        $("#edit_sub_category_name").val(subcategory);
    });
</script>
