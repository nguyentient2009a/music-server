<!-- Khai báo sử dụng layout admin -->
@extends('layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Playlist</h1>
</div>

<!-- Page Body -->
<div class="card">
    <div class="card-body">

        <!-- Content Row -->
        <div class="row mb-4">
            <div class="col-md-2">
                <a href="{{ url('playlist/create') }}" class="btn btn-success">Tạo Mới</a>
            </div>
        </div>

        <h4>Danh Sách</h4>
        <!-- Content Row -->
        <div class="row">
            <!-- Table -->
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã</th>
                            <th>Ảnh</th>
                            <th>Tên Playlist</th>
                            <th>Mô Tả</th>
                            <th>Thể Loại</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $number = 1
                        @endphp

                        @if ( isset($playlists) )
                        @foreach($playlists as $playlist)
                        <tr>
                            <td>{{ $number }}</td>
                            <td>{{ $playlist->PL_ID }}</td>
                            <td>
                                <image src={{ "storage/playlist-image/$playlist->PL_IMG" }} alt="img" width="80">
                            </td>
                            <td>{{ $playlist->PL_NAME }}</td>
                            <td class="font-weight-bold text-primary">
                                @if ( $playlist->PL_TYPE == 1)
                                {{"Chủ Đề Hot"}}

                                @elseif( $playlist->PL_TYPE == 2)
                                {{"Top 100"}}

                                @elseif ( $playlist->PL_TYPE == 3)
                                {{"Tâm Trạng và Làm Việc"}}
                                @endif

                            </td>
                            <td>{{ $playlist->PL_DES }}</td>
                            <td>
                                <a href="{{ url("playlist/info/$playlist->PL_ID") }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <a href="{{ url("playlist/delete/$playlist->PL_ID ") }}"
                                    class="btn btn-danger btn-circle btn-sm btn-delete"
                                    onclick="return confirmDelete(this)">
                                    <i class=" fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @php
                        $number++;
                        @endphp
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- kết thúc main-container -->
@endsection
<!-- Javascript -->
@section('script')
<script>
    // Xác nhận trước khi xóa. btnDelete được truyền vào bằng từ khóa this trong lúc gọi hàm
    const confirmDelete = (btnDelete) => {
        Swal.fire({
            title: 'Xóa Sản Phẩm này?',
            text: "Bạn không thể khôi phục sau khi xóa",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                location.assign(btnDelete.href)
            }
            return false
        })
        return false
    }
</script>
@endsection