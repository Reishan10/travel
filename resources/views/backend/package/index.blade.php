@extends('layouts.backend.main')

@section('title', 'Paket')

@section('css')
<!-- Datatables -->
<link rel="stylesheet" href="{{ asset('backend') }}/libs/data-tables/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('backend') }}/libs/data-tables/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.css"/>
@endsection

@section('content')
<div class="container-fluid">
    <div class="layout-specing">
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="mb-0">Paket</h5>

            <nav aria-label="breadcrumb" class="d-inline-block">
                <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
                    <li class="breadcrumb-item text-capitalize"><a href="#">Paket</a></li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">list</li>
                </ul>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('packages.create') }}" class="btn btn-primary mb-3 btn-sm">
                        Tambah Data +
                    </a>
                </div>
                <div class="table-responsive shadow rounded">
                    <div class="card-body">
                        <table class="table table-center bg-white mb-0" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center border-bottom p-3">No</th>
                                    <th class="border-bottom p-3">Foto</th>
                                    <th class="border-bottom p-3">Nama Paket</th>
                                    <th class="border-bottom p-3">Lokasi</th>
                                    <th class="border-bottom p-3">Benefit</th>
                                    <th class="border-bottom p-3">Harga</th>
                                    <th class="border-bottom p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Start -->
                                @foreach($packages as $package)
                                    <tr>
                                        <th class="text-center p-3" style="width: 5%;">{{ $loop->iteration }}</th>
                                        <td>
                                            <div class="row">
                                                @if ($package->images->count() > 0)
                                                    @foreach ($package->images as $image)
                                                        <div class="col-12 col-md-6">
                                                            <img src="{{ asset('storage/packages/' . $image->path) }}" width="100px" class="img-fluid mb-2" alt="package">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-3">{{ $package->name }} ({{ $package->kapasitas }} Pax)</td>
                                        <td class="p-3">{{ $package->location }}</td>
                                        <td class="p-3">
                                            @foreach ($package->benefits as $benefit)
                                                {{ $benefit->name }},
                                            @endforeach
                                        </td>
                                        <td class="p-3">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                        <td style="width: 5%;">
                                            <a href="packages/{{ $package->id }}/edit" class="btn btn-warning btn-sm mb-2"><i class="fa-solid fa-pen"></i> Edit</a>
                                            <button type="button" class="btn btn-danger btn-sm mb-2 btn-delete" data-id="{{ $package->id }}"><i class="fa-solid fa-trash"></i> Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- End -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
</div><!--end container-->
@endsection

@section('javascript')
<!-- Datatables -->
<script src="{{ asset('backend') }}/libs/data-tables/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('backend') }}/libs/data-tables/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('backend') }}/libs/data-tables/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('backend') }}/libs/data-tables/js/responsive.bootstrap5.min.js"></script>
<script src="{{ asset('backend') }}/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    // show datatable with search and pagination
    $(document).ready(function() {
        $('#table').DataTable();
    });

    // show dialog success
    @if (Session::has('message'))
        swal.fire({
            icon: "success",
            title: "Berhasil",
            text: "{{ Session::get('message') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    @endif

    // function delete
    $(".btn-delete").click(function() {
        var id = $(this).data("id");
        Swal.fire({
            title: 'Hapus',
            text: "Apakah anda yakin ingin menghapus?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "packages/" + id,
                    type: 'DELETE',
                    data: {
                        "id": id,
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    },
                });
            }
        })
    });

</script>
@endsection
