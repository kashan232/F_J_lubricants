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
                                        <th>Phone</th>
                                        <th>City</th>
                                        <th>Area</th>
                                        <th>Address</th>
                                        <th>Salary</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesmen as $key => $salesman)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $salesman->name }}</td>
                                        <td>{{ $salesman->phone }}</td>
                                        <td>{{ $salesman->city }}</td>
                                        <td>{{ $salesman->area }}</td>
                                        <td>{{ $salesman->address }}</td>
                                        <td>{{ number_format($salesman->salary, 2) }}</td>
                                        <td>
                                            <button class="btn btn-sm toggle-status" 
                                                    data-id="{{ $salesman->id }}" 
                                                    data-status="{{ $salesman->status }}">
                                                {{ $salesman->status == 1 ? 'Active' : 'Inactive' }}
                                            </button>
                                        </td>
                                        {{-- <td>{{ $salesman->status == 1 ? 'Active' : 'Inactive' }}</td> --}}
                                        <td>
                                            <button class="btn btn-sm btn-primary editSalesmanBtn" 
                                                    data-id="{{ $salesman->id }}"
                                                    data-name="{{ $salesman->name }}"
                                                    data-phone="{{ $salesman->phone }}"
                                                    data-city="{{ $salesman->city }}"
                                                    data-area="{{ $salesman->area }}"
                                                    data-address="{{ $salesman->address }}"
                                                    data-salary="{{ $salesman->salary }}"
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
    <div class="modal fade" id="addSalesmanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Salesman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('store-salesman') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                        
                        <label>Phone</label>
                        <input type="text" class="form-control" name="phone" required>

                        <label>City</label>
                        <select class="form-control" name="city" id="city-dropdown" required>
                            <option value="">Select City</option>
                        </select>
                        
                        <label>Area</label>
                        <select class="form-control" name="area" id="area-dropdown" required>
                            <option value="">Select Area</option>
                        </select>

                        <label>Address</label>
                        <input type="text" class="form-control" name="address" required>

                        <label>Salary</label>
                        <input type="number" class="form-control" name="salary" required>

                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Salesman Modal -->
<div class="modal fade" id="editSalesmanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Salesman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('update-salesman') }}" method="POST">
                @csrf
                <input type="hidden" id="edit_salesman_id" name="salesman_id">

                <div class="modal-body">
                    <label>Name</label>
                    <input type="text" class="form-control" id="edit_name" name="name" required>
                    
                    <label>Phone</label>
                    <input type="text" class="form-control" id="edit_phone" name="phone" required>

                    <label>City</label>
                    <select class="form-control" id="edit_city" name="city" required></select>

                    <label>Area</label>
                    <select class="form-control" id="edit_area" name="area" required></select>

                    <label>Address</label>
                    <input type="text" class="form-control" id="edit_address" name="address" required>

                    <label>Salary</label>
                    <input type="number" class="form-control" id="edit_salary" name="salary" required>

                    <label>Status</label>
                    <select class="form-control" id="edit_status" name="status" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
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
        $(document).on("click", ".editSalesmanBtn", function () {
            $("#edit_salesman_id").val($(this).data("id"));
            $("#edit_name").val($(this).data("name"));
            $("#edit_phone").val($(this).data("phone"));
            $("#edit_city").val($(this).data("city"));
            $("#edit_area").val($(this).data("area"));
            $("#edit_address").val($(this).data("address"));
            $("#edit_salary").val($(this).data("salary"));
            $("#edit_status").val($(this).data("status"));
        });
    });
    $(document).ready(function () {
    $(".toggle-status").click(function () {
        var button = $(this);
        var salesmanId = button.data("id");
        var currentStatus = button.data("status");

        $.ajax({
            url: "{{ route('toggle-salesman-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                salesman_id: salesmanId,
                status: currentStatus == 1 ? 0 : 1
            },
            success: function (response) {
                if (response.success) {
                    let newStatus = currentStatus == 1 ? 0 : 1;
                    button.data("status", newStatus);
                    button.text(newStatus == 1 ? "Active" : "Inactive");
                    button.toggleClass("btn-success btn-danger");
                }
            }
        });
    });
});



$(document).ready(function () {
    // Fetch cities on page load
    $.ajax({
        url: "{{ route('fetch-cities') }}",
        type: "GET",
        success: function (cities) {
            $("#city-dropdown, #edit_city").html('<option value="">Select City</option>');
            $.each(cities, function (key, city) {
                $("#city-dropdown, #edit_city").append(`<option value="${city.id}">${city.city_name}</option>`);
            });
        }
    });
// Fetch areas based on selected city
$(document).ready(function () {
    $("#city-dropdown").change(function () {
        var cityId = $(this).val();
        if (cityId) {
            $.ajax({
                url: "{{ route('fetch-areas') }}",
                type: "GET",
                data: { city_id: cityId },
                dataType: "json",
                success: function (response) {
                    $("#area-dropdown").empty().append('<option value="">Select Area</option>');
                    if (response.length > 0) {
                        $.each(response, function (key, area) {
                            $("#area-dropdown").append(`<option value="${area.id}">${area.area_name}</option>`);
                        });
                    } else {
                        $("#area-dropdown").append('<option value="">No Areas Found</option>');
                    }
                    },
                    error: function () {
                        alert("Error fetching areas. Please check your setup.");
                    }
            });
        } else {
            $("#area-dropdown").html('<option value="">Select Area</option>');
        }
    });
});
});


    </script>
