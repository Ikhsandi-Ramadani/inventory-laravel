@extends('base')

@section('title', 'Sales')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Sales</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-header d-flex justify-content-end mb-3">
                <div id="test"></div>
                <div class="pt-3 pt-md-0">
                    @if (auth()->user()->role !== 'Manager')
                        <a class="btn btn-primary" href="{{ route('sales.create') }}">
                            <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Tambah
                                    Data</span></span>
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-datatable text-nowrap">
                <table class="table table-bordered datatables-ajax" id="table-sales">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Jumlah Barang</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            @if (auth()->user()->role !== 'Manager')
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sales as $sale)
                            @foreach ($sale->salesdetail as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sale->number }}</td>
                                    <td>{{ $data->inventory->name }}</td>
                                    <td>{{ $data->qty }}</td>
                                    <td>{{ $data->price }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sale->date)->isoFormat('D MMMM Y') }}</td>
                                    <td>{{ $sale->user->name }}</td>
                                    @if (auth()->user()->role !== 'Manager')
                                        <td>
                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                data-bs-target="#edit-{{ $data->id }}">
                                                <span><i class="bx bx-edit me-sm-2"></i>
                                                    <span class="d-none d-sm-inline-block">Edit</span></span>
                                            </button>
                                            <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal"
                                                data-bs-target="#hapus-{{ $data->id }}">
                                                <span><i class="bx bx-trash me-sm-2"></i> <span
                                                        class="d-none d-sm-inline-block">Delete</span></span>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                {{-- Modal --}}
                @include('pages.sales.modal')
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jszip/jszip.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pdfmake/pdfmake.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/buttons.html5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/buttons.print.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <!-- Page JS -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('#table-sales').DataTable({
                scrollY: '300px',
                scrollX: true,
                dom: "<'row'<'col-sm-12 dt-btn'B>>" +
                    "<'row'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-label-primary dropdown-toggle me-2',
                    text: '<i class="bx bx-export me-sm-2"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [{
                            extend: 'print',
                            text: '<i class="bx bx-printer me-2" ></i>Print',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, ],
                            },
                        },
                        {
                            extend: 'csv',
                            text: '<i class="bx bx-file me-2" ></i>Csv',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, ],
                            }
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, ],
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, ],
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="bx bx-copy me-2" ></i>Copy',
                            className: 'dropdown-item',
                            exportOptions: {
                                columns: [0, 1, 2, 3, ],
                            }
                        },
                    ]
                }],
            });

            $(".dt-buttons").appendTo("#test");

            const select2 = $('.select2');
            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        dropdownParent: $this.parent()
                    });
                });
            }
        });
    </script>
@endpush
