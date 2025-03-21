@include('admin_panel.include.header_include')

<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="card p-4 shadow-lg">
                <div class="card-body">
                    <h2 class="card-title text-center fw-bold mb-4 text-primary">Item Stock Report</h2>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="fw-bold">Category</label>
                            <select class="form-control category-select">
                                <option value="all">All</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->category_name }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Subcategory</label>
                            <select class="form-control subcategory-select">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Item</label>
                            <select class="form-control item-select">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100 search-item">Search</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mt-4" id="stockReport">
                            <thead class="bg-gray text-white">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Size</th>
                                    <th>Packing</th>
                                    <th>Purchased Qty</th>
                                    <th>Distributor Sold Qty</th>
                                    <th>Total Local Sale</th>
                                    <th>Carton Qty</th>
                                    <th>Liters</th>
                                    <th>Wholesale Price</th>
                                    <th>Stock</th>
                                    <th>Alert Qty</th>
                                    <th>Total Stock Value</th>
                                </tr>
                            </thead>
                            <tbody id="item-details"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="12" class="text-end fw-bold">Total Stock Value:</td>
                                    <td class="fw-bold" id="subtotalStockValue">0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <button class="btn btn-danger mt-3" id="exportPdf">Export PDF</button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

<script>
    // Fetch Subcategories on Category Change
    $(document).on('change', '.category-select', function() {
        let categoryName = $(this).val();
        let subCategoryDropdown = $('.subcategory-select');

        if (categoryName !== "all") {
            $.ajax({
                url: "{{ route('get.subcategories', ':categoryname') }}".replace(':categoryname', categoryName),
                type: 'GET',
                success: function(response) {
                    subCategoryDropdown.html('<option value="all">All</option>');
                    $.each(response, function(index, name) {
                        subCategoryDropdown.append(`<option value="${name}">${name}</option>`);
                    });
                }
            });
        } else {
            subCategoryDropdown.html('<option value="all">All</option>');
        }
    });

    // Fetch Items on Subcategory Change
    $(document).on('change', '.subcategory-select', function() {
        let subCategoryName = $(this).val();
        let itemDropdown = $('.item-select');

        if (subCategoryName !== "all") {
            $.ajax({
                url: "{{ route('get.items.report', ':subcategory') }}".replace(':subcategory', subCategoryName),
                type: 'GET',
                success: function(response) {
                    itemDropdown.html('<option value="all">All</option>');
                    $.each(response, function(index, item) {
                        itemDropdown.append(`<option value="${item.item_code}">${item.item_name}</option>`);
                    });
                }
            });
        } else {
            itemDropdown.html('<option value="all">All</option>');
        }
    });

    function calculateSubtotal() {
        let totalStockValue = 0;
        $(".total-stock-value").each(function() {
            totalStockValue += parseFloat($(this).text()) || 0;
        });
        $("#subtotalStockValue").text(totalStockValue.toFixed(2));
    }

    $(document).on('click', '.search-item', function() {
        let category = $('.category-select').val();
        let subcategory = $('.subcategory-select').val();
        let itemCode = $('.item-select').val();
        let url = "{{ route('get.item.details') }}";

        $.ajax({
            url: url,
            type: 'GET',
            data: {
                category,
                subcategory,
                itemCode
            },
            success: function(response) {
                console.log(response); // 🛠 Debugging ke liye JSON output dekhne ke liye

                let tableContent = '';
                let totalStockValue = 0;

                $.each(response, function(index, item) {
                    console.log(item); // 🛠 Check karo ke item object ke andar `purchased_quantity` hai ya nahi

                    let stockValue = item.carton_quantity * item.wholesale_price;
                    totalStockValue += stockValue;

                    // 🔥 Size Calculation: Agar ml hai tu /1000, warna as it is
                    let sizeValue = item.size.toLowerCase().includes("ml") ?
                        parseFloat(item.size) / 1000 :
                        parseFloat(item.size);

                    let totalLiters = sizeValue * item.pcs_in_carton * item.carton_quantity;

                    tableContent += `<tr>
                    <td>${item.item_code}</td>
                    <td>${item.item_name}</td>
                    <td>${item.size}</td>
                    <td>${item.pcs_in_carton}</td>
                    <td>${item.total_purchased ?? 'N/A'}</td> <!-- 🛠 Undefined ko avoid karne ke liye default value -->
                    <td>${item.total_distributor_sold ?? 'N/A'}</td>
                    <td>${item.total_local_sold ?? 'N/A'}</td>
                    <td>${item.carton_quantity}</td>
                    <td>${totalLiters.toFixed(2)}</td> <!-- 🔥 Liters Calculation -->
                    <td>${item.wholesale_price}</td>
                    <td>${item.initial_stock}</td>
                    <td>${item.alert_quantity}</td>
                    <td class="total-stock-value">${stockValue.toFixed(2)}</td>
                </tr>`;
                });

                $('#item-details').html(tableContent);
                $("#subtotalStockValue").text(totalStockValue.toFixed(2));
            }
        });

    });


    $(document).on('click', '#exportPdf', function() {
        const {
            jsPDF
        } = window.jspdf;
        let pdf = new jsPDF('l', 'pt', 'a4'); // Landscape mode for full width

        pdf.setFontSize(14);
        pdf.text("Item Stock Report", 40, 30);

        pdf.autoTable({
            html: '#stockReport',
            theme: 'grid',
            startY: 20,
            styles: {
                fontSize: 8,
                cellPadding: 4
            },
            headStyles: {
                fillColor: [41, 128, 185]
            }, // Blue header
        });

        pdf.save("Item_Stock_Report.pdf");
    });
</script>