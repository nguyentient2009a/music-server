<!-- Khai báo sử dụng layout admin -->
@extends('layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Tạo Album</h1>
    <h5><a class="text-primary mb-5" href="{{url('album/list')}}">Album</a> / Upload</h5>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">
        <!-- Content Row -->
        <form class="conatainer" action="{{ asset('/album/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-between">

                {{-- nửa form bên trái --}}
                <div class="col-md-5">
                    <h4 class="mb-5">Thông tin</h4>
                    <!-- TÊN ALBUM -->
                    <div class="form-group row">
                        <label for="album_name" class="col-md-3 col-form-label">Tên Album</label>
                        <div class="col-sm-8">
                            <input type="text" name="album_name"
                                class="form-control text-danger text-right font-weight-bold" id="album_name"
                                placeholder="Nhập Tên Album" required />
                        </div>
                    </div>

                    <!-- Chọn Ca Sĩ-->
                    <div class="form-group row">
                        <label for="album_artist" class="col-md-3 col-form-label">Ca Sĩ</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="album_artist" id="album_artist">
                                @if ( isset($artistList) )
                                @foreach($artistList as $artist)
                                <option value="{{$artist->AR_ID}}">{{ $artist->AR_NAME }}</option>
                                @endforeach
                                @endif

                            </select>
                        </div>
                    </div>

                    <!-- Mô Tả -->
                    <div class="form-group row">
                        <label for="album_des" class="col-md-3 col-form-label">Mô Tả</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="album_des" name="album_des" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- ảnh vuông-->
                    <div class="form-group row">
                        <label for="album_image" class="col-md-3 col-form-label">Ảnh vuông</label>
                        <div class="col-sm-8">
                            <input type="file" name="album_image" class="form-control mb-3 p-1" accept="image/*"
                                id="album_image" required>
                            <img id="output" src="{{ asset('images/default.png')}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                    <!-- ảnh chữ nhật-->
                    <div class="form-group row">
                        <label for="album_image2" class="col-md-3 col-form-label">Ảnh Chữ Nhật</label>
                        <div class="col-sm-8">
                            <input type="file" name="album_image2" class="form-control mb-3 p-1" accept="image/*"
                                id="album_image2" required>
                            <img id="output2" src="{{ asset('images/default.png')}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                </div>
                {{-- nửa form bên phải --}}
                <div class="col-md-7">
                    <h4 class="mb-5">Chọn Bài Hát</h4>
                    <div class="table-wrapper-scroll-y my-custom-scrollbar">

                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>chọn</th>
                                    <th>Mã</th>
                                    <th>Ảnh</th>
                                    <th>Tên Bài Hát</th>
                                    <th>Nghệ Sĩ</th>
                                    <th>Thể Loại</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $number = 1
                                @endphp

                                @if ( isset($songList) )
                                @foreach($songList as $song)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="songId[]" value="{{$song->SO_ID}}">
                                    </td>
                                    <td>{{ $song->SO_ID }}</td>
                                    <td>
                                        <image src={{ "/storage/song-image/$song->SO_IMG" }} alt="img" width="80">
                                    </td>
                                    <td>{{ $song->SO_NAME }}</td>
                                    <td class="text-right font-weight-bold text-primary">

                                        @foreach($song->ARTISTS as $artist)
                                        {{ $artist->AR_NAME . " "}}

                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $song->GE_NAME }}
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

            <!-- submit -->
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mr-3" type="submit">Lưu</button>
                <a href="{{ url('album/list') }}" class="btn btn-secondary">Hủy</a>
            </div>

        </form>

        <!-- errors -->
        <div class="row">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                {{$error}}<br>
                @endforeach
            </div>
            @endif
        </div>
        <!-- end card body -->
    </div>
    <!-- kết thúc main-container -->
    @endsection
    {{-- Javascript --}}
    @section('script')
    <script>
        // Kiểm tra biến errors từ server gửi về. Nếu có lỗi xuất popup thông báo
        @if(count($errors) > 0)
        Swal.fire({
            title: 'Thất Bại',
            text: 'Vui lòng kiểm tra lại thông tin',
            icon: 'error',
            confirmButtonText: 'OK'
        })
        @endif

        // Hiển thị ảnh upload vuông
        let image_input_DOM = document.querySelector("#album_image");
        image_input_DOM.addEventListener("input", () => {
            let reader = new FileReader();
            let output = document.querySelector("#output");

            reader.onload = (e) => {
                console.log(e.target);
                console.log(output);
                output.src = e.target.result;
            };

            reader.readAsDataURL(image_input_DOM.files[0]);
        });

        // Hiển thị ảnh upload chữ nhật
        let image_input_DOM2 = document.querySelector("#album_image2");
        image_input_DOM2.addEventListener("input", () => {
            let reader = new FileReader();
            let output = document.querySelector("#output2");

            reader.onload = (e) => {
                console.log(e.target);
                console.log(output);
                output.src = e.target.result;
            };

            reader.readAsDataURL(image_input_DOM2.files[0]);
        });
    </script>
    @endsection