@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Customer List</h4>
                    <h6>Manage Customers</h6>
                </div>
                <div class="page-btn">
                    <button class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                        <img src="assets/img/icons/plus.svg" class="me-1" alt="img">Add Customer
                    </button>
                </div>
            </div>

            @if (session()->has('success'))
            <div class="alert alert-success">
                <strong>Success!</strong> {{ session('success') }}.
            </div>
            @endif

            <div class="container">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Area</th>
                                <th>Address</th>
                                <th>Shop Name</th>
                                <th>Business Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $key => $customer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $customer->customer_name }}</td>
                                <td>{{ $customer->phone_number }}</td>
                                <td>{{ $customer->city ?? 'N/A' }}</td>
                                <td>{{ $customer->area ?? 'N/A' }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->shop_name }}</td>
                                <td>{{ $customer->business_type_name ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning editCustomerBtn" data-id="{{ $customer->id }}">Edit</button>
                                    <button class="btn btn-sm btn-danger deleteCustomerBtn" data-id="{{ $customer->id }}">Delete</button>
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

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <select class="form-control" name="city" id="citySelect" required>
                                <option value="">Select City</option>
                                @foreach($city as $city)
                                <option value="{{ $city->city_name }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-md-6 mb-3">
                            <select class="form-control" name="area" id="areasSelect" required>
                                <option value="">Select Areas</option>
                            </select>
                        </div>
                    </div>

                    <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
                    <input type="text" name="phone_number" class="form-control mt-2" placeholder="Phone Number" required>

                    <input type="text" name="address" class="form-control mt-2" placeholder="Address" required>
                    <input type="text" name="shop_name" class="form-control mt-2" placeholder="Shop Name" required>
                    <input type="number" name="opening_balance" class="form-control mt-2" placeholder="Opening Balance" required>
                    <select name="business_type_id" id="businessTypeDropdown" class="form-control mt-2" required></select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customers.update') }}" method="POST">
                @csrf
                <input type="hidden" name="customer_id" id="edit_customer_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" id="edit_customer_name" required>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on("click", ".editCustomerBtn", function() {
        let id = $(this).data("id");
        let name = $(this).data("name");
        $("#edit_customer_id").val(id);
        $("#edit_customer_name").val(name);
    });

    $(document).on("click", ".deleteCustomerBtn", function(e) {
        e.preventDefault();
        let customerId = $(this).data("id");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete-customer', '') }}/" + customerId,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire("Deleted!", response.message, "success");
                            location.reload(); // Refresh page
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        // Fetch Business Types
        $.get("{{ route('fetch-business-types') }}", function(data) {
            $('#businessTypeDropdown').html('<option value="">Select Business Type</option>');
            $.each(data, function(index, type) {
                $('#businessTypeDropdown').append('<option value="' + type.business_type_name + '">' + type.business_type_name + '</option>');
            });
        });

        // Delete Customer
        $('.deleteCustomerBtn').click(function() {
            let customerId = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/customer/delete/" + customerId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire("Deleted!", response.success, "success").then(() => location.reload());
                        }
                    });
                }
            });
        });
    });
    $(document).ready(function() {
        // Add Product Modal: Fetch areas on Category Change
        $('#citySelect').change(function() {
            var cityId = $(this).val();
            $('#areasSelect').html('<option value="">Loading...</option>');

            if (cityId) {
                $.ajax({
                    url: "{{ route('fetch-areas') }}",
                    type: "GET",
                    data: {
                        city_id: cityId
                    },
                    success: function(data) {
                        $('#areasSelect').html('<option value="">Select Area</option>');
                        $.each(data, function(key, area) {
                            $('#areasSelect').append('<option value="' + area.area_name + '">' + area.area_name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Error fetching areas.');
                    }
                });
            } else {
                $('#areasSelect').html('<option value=""> Area Not Found...</option>');
            }
        });
    });
</script>