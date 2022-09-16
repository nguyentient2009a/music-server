<!-- Khai báo sử dụng layout admin -->
@extends('layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Tạo Nghệ Sĩ</h1>
    <h5><a class="text-primary mb-5" href="{{url('artist/list')}}">Nghệ Sĩ</a> / Tạo Mới</h5>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">
        <!-- Content Row -->
        <form class="conatainer" action="{{ asset('artist/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-between">

                {{-- nửa form bên trái --}}
                <div class="col-md-5">
                    <!-- TÊN NGHE SI -->
                    <div class="form-group row">
                        <label for="artist_name" class="col-md-3 col-form-label">Tên Nghệ Sĩ</label>
                        <div class="col-sm-8">
                            <input type="text" name="artist_name"
                                class="form-control text-danger text-right font-weight-bold" id="artist_name"
                                placeholder="Nhập Tên Nghệ Sĩ" required />
                        </div>
                    </div>

                    <!-- ảnh -->
                    <div class="form-group row">
                        <label for="artist_image" class="col-md-3 col-form-label">Ảnh đại diện</label>
                        <div class="col-sm-8">
                            <input type="file" name="artist_image" class="form-control mb-3 p-1" accept="image/*"
                                id="artist_image" required>
                            <img id="output" src="{{ asset('images/default.png')}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                </div>
                {{-- nửa form bên phải --}}
                <div class="col-md-7">
                    <!-- Mo Ta -->
                    <div class="form-group row">
                        <label for="artist_des" class="col-md-2 col-form-label">Giới Thiệu</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="artist_des" name="artist_des" rows="3" cols="100"
                                required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- submit -->
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mr-3" type="submit">Lưu</button>
                <a href="{{ url('song/list') }}" class="btn btn-secondary">Hủy</a>
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

        // Hiển thị ảnh upload
        let image_input_DOM = document.querySelector("#artist_image");
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
    </script>
    @endsection