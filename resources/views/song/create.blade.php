<!-- Khai báo sử dụng layout admin -->
@extends('layout.index')

<!-- Khai báo định nghĩa phần main-container trong layout admin-->
@section('main-container')
<!-- Page Heading -->
<div class="mb-3">
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-primary font-weight-bold">Upload</h1>
    <h5><a class="text-primary mb-5" href="{{url('song/list')}}">Bài Hát</a> / Upload</h5>
</div>
<!-- Page Body -->
<div class="card">
    <div class="card-body">
        <!-- Content Row -->
        <form class="conatainer" action="{{ asset('song/create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row d-flex justify-content-between">

                {{-- nửa form bên trái --}}
                <div class="col-md-5">
                    <h4 class="mb-5">Thông tin</h4>
                    <!-- TÊN BÀI HÁT -->
                    <div class="form-group row">
                        <label for="song_name" class="col-md-3 col-form-label">Tên Bài Hát</label>
                        <div class="col-sm-8">
                            <input type="text" name="song_name"
                                class="form-control text-danger text-right font-weight-bold" id="song_name"
                                placeholder="Nhập Tên Bài Hát" required />
                        </div>
                    </div>

                    <!-- Tên Nghệ sĩ 1-->
                    <div class="form-group row">
                        <label for="artist1" class="col-md-3 col-form-label">Nghệ Sĩ 1</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="artist_id1" id="artist1">
                                @foreach ($artistList as $artist)
                                <option value="{{ $artist->AR_ID}}" class="font-weight-bold">
                                    {{ $artist->AR_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Tên Nghệ sĩ 2-->
                    <div class="form-group row">
                        <label for="artist2" class="col-md-3 col-form-label">Nghệ Sĩ 1</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="artist_id2" id="artist2">
                                @foreach ($artistList as $artist)
                                <option value="{{ $artist->AR_ID}}" class="font-weight-bold">
                                    {{ $artist->AR_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tên Nghệ sĩ 3-->
                    <div class="form-group row">
                        <label for="artist3" class="col-md-3 col-form-label">Nghệ Sĩ 3</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="artist_id3" id="artist3">
                                @foreach ($artistList as $artist)
                                <option value="{{ $artist->AR_ID}}" class="font-weight-bold">
                                    {{ $artist->AR_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Thể Loại Nhạc -->
                    <div class="form-group row">
                        <label for="genre" class="col-md-3 col-form-label">Thể Loại</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="genre_id" id="genre">
                                @foreach ($genreList as $genre)
                                <option value="{{ $genre->GE_ID}}" class="font-weight-bold">
                                    {{ $genre->GE_NAME }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Upload Music -->
                    <div class="form-group row">
                        <label for="song_mp3" class="col-md-3 col-form-label">File Nhạc</label>
                        <div class="col-sm-8">
                            <input type="file" name="song_mp3" class="form-control mb-3 p-1" accept=".mp3,audio/*"
                                id="song_mp3" required>
                        </div>
                    </div>

                    <!-- ảnh -->
                    <div class="form-group row">
                        <label for="song_image" class="col-md-3 col-form-label">Ảnh đại diện</label>
                        <div class="col-sm-8">
                            <input type="file" name="song_image" class="form-control mb-3 p-1" accept="image/*"
                                id="song_image" required>
                            <img id="output" src="{{ asset('images/default.png')}}" width="300"
                                style="border:2px solid #000; border-radius: 5px;" />
                        </div>
                    </div>

                </div>
                {{-- nửa form bên phải --}}
                <div class="col-md-7">
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
        let image_input_DOM = document.querySelector("#song_image");
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