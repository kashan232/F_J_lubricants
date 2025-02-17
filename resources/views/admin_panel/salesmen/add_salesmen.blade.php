@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Salesmen List</h4>
                    <h6>Manage Salesmen</h6>
                </div>
                <div class="page-btn">
                    <button class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addSalesmanModal">
                        <img src="assets/img/icons/plus.svg" class="me-1" alt="img">Add Salesman
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salesmen as $key => $salesman)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $salesman->name }}</td>
                                    <td>{{ $salesman->email }}</td>
                                    <td>{{ $salesman->phone_number }}</td>
                                    <td>{{ $salesman->address }}</td>
                                    <td>{{ $salesman->status == 1 ? 'Active' : 'Inactive' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editSalesmanBtn" 
                                                data-id="{{ $salesman->id }}"
                                                data-name="{{ $salesman->name }}"
                                                data-email="{{ $salesman->email }}"
                                                data-phone_number="{{ $salesman->phone_number }}"
                                                data-address="{{ $salesman->address }}"
                                                data-status="{{ $salesman->status }}"
                                                data-bs-toggle="modal" data-bs-target="#editSalesmanModal">
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

<!-- Add Salesman Modal -->
<div class="modal fade" id="addSalesmanModal" tabindex="-1" aria-labelledby="addSalesmanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Salesman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <form action="{{ route('store-salesman') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" required>
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
                
        </div>
    </div>
</div>

<!-- Edit Salesman Modal -->
<div class="modal fade" id="editSalesmanModal" tabindex="-1" aria-labelledby="editSalesmanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Salesman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update-salesman') }}" method="POST">
                @csrf
                <input type="hidden" name="salesman_id" id="edit_salesman_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                    </div>
            
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" id="edit_phone_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="edit_address" required>
                        </div>
                    </div>
            
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
$(document).ready(function () {
    // When clicking "Edit" button, load Salesman data
    $(document).on("click", ".editSalesmanBtn", function () {
        var salesmanId = $(this).data("id");
        var selectedStatus = $(this).data("status");

        $("#edit_salesman_id").val(salesmanId);
        $("#edit_name").val($(this).data("name"));
        $("#edit_email").val($(this).data("email"));
        $("#edit_phone_number").val($(this).data("phone_number"));
        $("#edit_address").val($(this).data("address"));
        $("#edit_status").val(selectedStatus);
    });
});

</script>
