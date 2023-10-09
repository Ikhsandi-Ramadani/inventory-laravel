@extends('base')

@section('title', 'Tambah Purchase')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Purchase /</span> Tambah</h4>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <h5 class="card-header">Form Repeater</h5> --}}
                    <div class="card-body">
                        <form class="form-repeater" action="{{ route('purchase.store') }}" method="POST">
                            @csrf
                            <div data-repeater-list="data">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                            <label class="form-label" for="form-repeater-1-1">Nama Barang</label>
                                            <select class="form-select select2 form-control" id="form-repeater-1-1"
                                                data-allow-clear="true" name="inventory_id" required>
                                                @foreach ($inventories as $inventory)
                                                    <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 col-lg-6 col-xl-3 col-12 mb-0">
                                            <label class="form-label" for="form-repeater-1-2">Jumlah Barang</label>
                                            <input type="number" id="form-repeater-1-2" class="form-control"
                                                name="qty" />
                                        </div>
                                        <div class="mb-3 col-lg-12 col-xl-6 col-12 d-flex align-items-center mb-0">
                                            <button class="btn btn-label-danger mt-4 me-4" data-repeater-delete>
                                                <i class="bx bx-x"></i>
                                                <span class="align-middle">Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" name="date" />
                                </div>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-primary" data-repeater-create>
                                    <i class="bx bx-plus"></i>
                                    <span class="align-middle">Tambah Barang</span>
                                </button>
                                <button class="btn btn-primary" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Form Repeater
            var formRepeater = $('.form-repeater');
            if (formRepeater.length) {
                var row = 2;
                var col = 1;

                formRepeater.repeater({
                    show: function() {
                        var formControl = $(this).find('.form-control, .form-select');
                        var formLabel = $(this).find('.form-label');

                        formControl.each(function(i) {
                            var id = 'form-repeater-' + row + '-' + col;
                            $(formControl[i]).attr('id', id);
                            $(formLabel[i]).attr('for', id);
                            col++;
                        });

                        row++;

                        $(this).slideDown();
                        $('.select2-container').remove();
                        $('.select2').select2();
                        $('.select2-container').css('width', '100%');
                    },
                    hide: function(e) {
                        confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
                    }
                });
            }
        });
    </script>
@endpush
