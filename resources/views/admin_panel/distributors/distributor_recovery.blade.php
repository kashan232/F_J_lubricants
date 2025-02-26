@include('admin_panel.include.header_include')
<div class="main-wrapper">
    @include('admin_panel.include.navbar_include')
    @include('admin_panel.include.admin_sidebar_include')

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Distributor Recoveries</h4>
                    <h6>Track all recoveries from salesmen</h6>
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
                                    <th>Distributor</th>
                                    <th>Salesman</th>
                                    <th>Amount Paid</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Recoveries as $key => $recovery)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $recovery->distributor->Customer ?? 'N/A' }}</td>
                                    <td>{{ $recovery->salesman }}</td>
                                    <td>{{ number_format($recovery->amount_paid, 0) }}</td>
                                    <td>{{ $recovery->date }}</td>
                                   
                                </tr>
                                @endforeach
                                @if($Recoveries->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">No recoveries found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin_panel.include.footer_include')
