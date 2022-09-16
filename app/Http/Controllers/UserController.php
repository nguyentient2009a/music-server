<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    // GET
    // Lấy danh sách bài hát đã Like
    public function getLikedSong($userId)
    {

        $songs = DB::table('LIKE_SONG')
            ->join("SONG", "SONG.SO_ID", "LIKE_SONG.SO_ID")
            ->where("LIKE_SONG.US_ID", '=', $userId)
            ->get();

        $artistsTemp = [];

        for ($i = 0; $i < sizeof($songs); $i++) {
            $songId = $songs[$i]->SO_ID;
            $artist = DB::table('artist')
                ->join('artist_song', 'artist_song.AR_ID', '=', 'artist.AR_ID')
                ->where('artist_song.SO_ID', '=', $songId)
                ->select("ARTIST.AR_ID", "ARTIST.AR_NAME", "ARTIST.AR_IMG", "ARTIST.AR_STORY")
                ->get();
            $songs[$i]->ARTISTS =  $artist;
            array_push($artistsTemp, $artist[0]);
        }
        // Sắp xếp tăng dần
        sort($artistsTemp);

        // dd($artistsTemp);
        $artists = [];

        // Xóa giá trị trùng
        $size = sizeof($artistsTemp);

        for ($i = 0; $i < $size; $i++) {
            if ($i < $size - 1 && $artistsTemp[$i]->AR_ID == $artistsTemp[$i + 1]->AR_ID) {
                continue;
            }
            array_push($artists, $artistsTemp[$i]);
        }


        return response()->json(["songs" => $songs, "artists" => $artists]);
    }

    // GET
    // Lấy Danh Sách Playlist mà USER_ID đã tạo
    public function getPlaylistByUserId($userId)
    {

        $playlists = DB::table("playlist")->where("US_ID", '=', $userId)->get();

        return $playlists;
    }

    // POST
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_name' => 'required|min:6|max:100',
            'user_email' => 'required|email',
            'user_password' => 'required|min:6',
        ], [
            'required' => ':attribute Trống',
            'email' => ':attribute Email Không Hợp Lệ',
            'min' => ':attribute Có Ít Nhất 6 Ký Tự',
            'max:100' => 'attribute Có Tối Đa 100 Ký Tự'
        ], [
            'user_name' => 'Tên Người Dùng',
            'user_email' => 'Email',
            'user_password' => 'Mật khẩu',
        ]);

        if ($validator->fails()) {
            // return redirect('post/create')
            //             ->withErrors($validator)
            //             ->withInput();

            return response()->json(["result" => "fail", "message" => $validator->getMessageBag()]);
        }



        try {
            // Kiểm tra email đã tồn tại chưa
            $users = DB::table('USER')->where('US_EMAIL', '=', $request->user_email)->get();

            if (count($users) > 0) {
                return response()->json(["result" => "fail", "message" => "Email đã được đăng kí"]);
            }

            // Lưu vào DB
            DB::table('USER')->insert([
                'US_NAME' => $request->user_name,
                'US_EMAIL' => $request->user_email,
                'US_PASS' => bcrypt($request->user_password),
                'US_TYPE' => 1
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
        return response()->json(["result" => "success", "message" => "Đăng Kí Thành Công"]);
    }


    //POST
    // LOGIN
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_email' => 'required|email',
            'user_password' => 'required|min:6',
        ], [
            'required' => ':attribute Trống',
            'email' => ':attribute Email Không Hợp Lệ',
            'min' => ':attribute Có Ít Nhất 6 Ký Tự',
        ], [
            'user_email' => 'Email',
            'user_password' => 'Mật khẩu',
        ]);

        if ($validator->fails()) {
            return response()->json(["result" => "fail", "message" => $validator->getMessageBag()]);
        }


        // kiểm tra người dùng có tồn tại ??
        $user = DB::table('USER')->where('US_EMAIL', '=', $request->user_email)->first();

        if ($user == null) {
            return response()->json(["result" => "fail", "message" => "Đăng Nhập Không Thành Công"]);
        }

        // kiểm tra mật khẩu có đúng ??
        $comparePassword = Hash::check($request->user_password, $user->US_PASS);

        if ($comparePassword == false) {
            return response()->json(["result" => "fail", "message" => "Đăng Nhập Không Thành Công"]);
        }

        unset($user->US_PASS);
        unset($user->US_TYPE);

        // Lưu thời gian đăng nhập vào db
        return response()->json(["result" => "success", "message" => "Đăng Nhập Thành Công", "user" => $user]);
    }

    // POST
    // Lưu thông tin lượt thích của người dùng
    public function like(Request $request)
    {

        // Debug
        // dd($request->user_id, $request->song_id);

        try {
            //Kiểm tra người dùng đã like bài hát chưa
            $users = DB::table('LIKE_SONG')->where('US_ID', '=', $request->user_id)
                ->where("SO_ID", "=", $request->song_id)
                ->get();

            if (count($users) > 0) {
                DB::table('LIKE_SONG')->where('US_ID', '=', $request->user_id)
                    ->where("SO_ID", "=", $request->song_id)
                    ->delete();
                return response()->json(["result" => "success", "message" => "unlike"]);
            }

            // Lưu vào DB
            DB::table('LIKE_SONG')->insert([
                'US_ID' => $request->user_id,
                'SO_ID' => $request->song_id
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
        return response()->json(["result" => "success", "message" => "like"]);
    }

    // GET
    // Kiểm tra bài hát đã được like hay chưa
    public function checkLikeSong($userId, $songId)
    {
        //dd($userId, $songId);
        $like_song = DB::table("LIKE_SONG")->where("US_ID", '=', $userId)
            ->where("SO_ID", "=", $songId)
            ->get();
        return $like_song;
    }

    // POST
    // Add New User Playlist
    public function createNewPlaylist(Request $request)
    {
        try {
            // Retrieve img file from public/storage/song-image/<image_name>
            $imgfile = Storage::path('song-image/' . $request->song_img);
            //Save img in: public/storage/playlist-image/<image_name>
            $imagePath = Storage::putFile('playlist-image', new \Illuminate\Http\File($imgfile));
            $imageName = basename(($imagePath));

            //Add New User Playlist
            $id = DB::table('PLAYLIST')->insertGetId([
                'US_ID' => $request->user_playlist_use_id,
                'PL_NAME' => $request->user_playlist_name,
                'PL_TYPE' => $request->user_playlist_type,
                'PL_IMG' => $imageName
            ]);
            //Add Song to Playlist
            DB::table('COLLECTION')->insert([
                'PL_ID' => $id,
                'SO_ID' => $request->song_id
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return response()->json(["result" => "fail", "message" => "Error"]);
        }
        return response()->json(["result" => "success", "message" => "create new user playlist successfully"]);
    }

    //POST
    // Add Song to User Playlist
    public function addSongToUserPlaylist(Request $request)
    {
        try {
            // Add Song to Playlist
            DB::table('COLLECTION')->insert([
                'PL_ID' => $request->user_playlist_id,
                'SO_ID' => $request->user_playlist_song_id
            ]);
        } catch (Exception $ex) {
            return response()->json(["result" => "fail", "message" => 'error']);
        }
        return response()->json(["result" => "success", "message" => "Add song to user playlist successfully"]);
    }

    // GET
    // PLAYLIST LIKED
    public function getPlaylistLiked($user_id)
    {
        try {

            // Lấy id playlit mà người dùng đã like
            $playlistId = DB::table("LIKE_PLAYLIST")->where("US_ID", "=", $user_id)->select('PL_ID')->get();
            // dd($playlistId);

            // Lấy thông tin chi tiết của từng playlist trong danh sách
            $playlist = [];
            $size = sizeof($playlistId);
            for ($i = 0; $i < $size; $i++) {
                $playlistTemp = DB::table("PLAYLIST")->where("PL_ID", "=", $playlistId[$i]->PL_ID)->get();

                if (sizeof($playlistTemp) == 0) {
                    continue;
                }
                array_push($playlist, $playlistTemp[0]);
            }

            // dd($playlist);
            return $playlist;
        } catch (Exception $ex) {
            return response()->json(["result" => $ex->getMessage()]);
        }
    }

    // GET
    // ALBUM LIKED
    public function getAlbumLiked($user_id)
    {
        try {
            // Lấy id album
            $albumId = DB::table("LIKE_ALBUM")->where("US_ID", "=", $user_id)->select("AL_ID")->get();
            // dd($albumId);

            // Lấy thông tin chi tiết album
            $album = [];
            $size = sizeof($albumId);
            for ($i = 0; $i < $size; $i++) {
                $albumTemp = DB::table("ALBUM")->where("AL_ID", "=", $albumId[$i]->AL_ID)->get();
                // dd($albumTemp);
                if (sizeof($albumTemp) == 0) {
                    continue;
                }
                array_push($album, $albumTemp[0]);
            }
            // dd($album);
            return $album;
        } catch (Exception $ex) {
            return response()->json(["result" => "fail", "message" => "Lỗi Máy Chủ"]);
        }
    }

    // API - POST
    public function changeUserInfo(Request $request)
    {
        try {
            $data = [
                "id" => $request->user_id,
                "name" => $request->user_name,
                "oldPassword" => $request->user_old_password,
                "newPassword" => $request->user_new_password,
            ];

            $user = DB::table("USER")->where("US_ID", "=", $data["id"])->first();

            if (!Hash::check($data["oldPassword"], $user->US_PASS)) {
                return response()->json(["result" => "fail"]);
            }

            $user = DB::table("USER")
                ->where("US_ID", "=", $data["id"])
                ->update([
                    "US_NAME" => $data["name"],
                    "US_PASS" => bcrypt($data["newPassword"])
                ]);

            return response()->json(["result" => "success"]);
        } catch (Exception $ex) {
            return response()->json(["errors" => $ex->getMessage()]);
        }
    }

    // API - GET
    public function checkPlaylistIsLiked($user_id, $playlist_id)
    {

        try {

            $result = DB::table("LIKE_PLAYLIST")
                ->where("US_ID", $user_id)
                ->where("PL_ID", $playlist_id)
                ->first();

            if ($result) {
                return response()->json(["result" => "yes"]);
            } else {
                return response()->json(["result" => "no"]);
            }
        } catch (Exception $ex) {
            return response()->json(["errors" => $ex->getMessage()]);
        }
    }

    // API - GET
    public function checkAlbumIsLiked($user_id, $album_id)
    {

        try {

            $result = DB::table("LIKE_ALBUM")
                ->where("US_ID", $user_id)
                ->where("AL_ID", $album_id)
                ->first();

            if ($result) {
                return response()->json(["result" => "yes"]);
            } else {
                return response()->json(["result" => "no"]);
            }
        } catch (Exception $ex) {
            return response()->json(["errors" => $ex->getMessage()]);
        }
    }

    // API - GET
    public function likeAlbum($user_id, $album_id)
    {
        try {

            $isLiked = DB::table("LIKE_ALBUM")
                ->where("US_ID", $user_id)
                ->where("AL_ID", $album_id)
                ->first();

            if ($isLiked) {
                DB::table("LIKE_ALBUM")
                    ->where("US_ID", $user_id)
                    ->where("AL_ID", $album_id)
                    ->delete();
                return response()->json(["result" => "unlike"]);
            } else {
                DB::table("LIKE_ALBUM")
                    ->insert([
                        "US_ID" => $user_id,
                        "AL_ID" => $album_id,
                    ]);

                return response()->json(["result" => "like"]);
            }
        } catch (Exception $ex) {
            return response()->json(["errors" => $ex->getMessage()]);
        }
    }

    // API - GET
    public function likePlaylist($user_id, $playlist_id)
    {
        try {
            $isLiked = DB::table("LIKE_PLAYLIST")
                ->where("US_ID", $user_id)
                ->where("PL_ID", $playlist_id)
                ->first();

            if ($isLiked) {
                DB::table("LIKE_PLAYLIST")
                    ->where("US_ID", $user_id)
                    ->where("PL_ID", $playlist_id)
                    ->delete();
                return response()->json(["result" => "unlike"]);
            } else {
                DB::table("LIKE_PLAYLIST")
                    ->insert([
                        "US_ID" => $user_id,
                        "PL_ID" => $playlist_id
                    ]);
                return response()->json(["result" => "like"]);
            }
        } catch (Exception $ex) {
            return response()->json(["errors" => $ex->getMessage()]);
        }
    }
}
