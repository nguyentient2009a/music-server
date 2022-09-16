<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PlaylistController extends Controller
{


    // Playlist Mới Nhất
    // Get
    public function getNewest()
    {

        try {

            $playlistNewest = DB::table('playlist')->limit(4)->get();

            return $playlistNewest;
        } catch (Exception $err) {
            return response()->json(['errors' => $err->getMessage()]);
        }
    }


    // Playlist bằng Type
    // Get
    public function getPlaylistByType($type, $number)
    {
        $playlist = [];

        if ($number == 0) {
            $playlist = DB::table('playlist')->where('PL_TYPE', '=', $type)->get();
        } else {
            $playlist = DB::table('playlist')->where('PL_TYPE', '=', $type)->limit($number)->get();
        }
        return $playlist;
    }


    // WEB - get
    // Hiển thị danh sách
    public function showListView()
    {
        $playlists = DB::table('playlist')->get();

        // dd($playlists);
        return view('playlist.list', ['playlists' => $playlists]);
    }


    // VIEW
    // Tạo playlist
    // GET

    public function createPlaylistView()
    {

        $songList = DB::table('SONG')
            ->join('GENRE', 'GENRE.GE_ID', '=', 'SONG.GE_ID')
            ->get();

        // dd($songList);

        for ($i = 0; $i < sizeof($songList); $i++) {

            $songId = $songList[$i]->SO_ID;

            $artists = DB::table('artist')
                ->join('artist_song', 'artist_song.AR_ID', '=', 'artist.AR_ID')
                ->where('artist_song.SO_ID', '=', $songId)
                ->select(['artist.AR_ID', 'artist.AR_NAME'])
                ->get();
            $songList[$i]->ARTISTS =  $artists;
        }


        return view('playlist.create', ['songList' => $songList]);
    }

    // View - Post
    // Tạo playlist

    public function createPlaylist(Request $request)
    {
        // dd($request);

        // lấy dữ liệu từ request
        $data = [
            'PL_NAME' => trim($request->playlist_name, " "), //cắt khoảng trắng 2 bên của tên
            'PL_TYPE' => $request->playlist_type,
            'PL_DES' => $request->playlist_des,
            'songsId' => $request->songId,
        ];


        try {

            // Lưu ảnh vô thư mục song: public/storage/playlist-image/<image_name>
            if ($request->hasFile('playlist_image')) {
                $imagePath = Storage::putFile('playlist-image', $request->file('playlist_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            if ($request->hasFile('playlist_image2')) {
                $imagePath2 = Storage::putFile('playlist-image', $request->file('playlist_image2'));
                $imageName2 = basename(($imagePath2)); // trả về tên file
            }


            // Lưu PLAYLIST DB
            $playlistId = DB::table('playlist')
                ->insertGetId([
                    'PL_NAME' => $data['PL_NAME'],
                    'PL_DES' => $data['PL_DES'],
                    'PL_TYPE' => $data['PL_TYPE'],
                    'PL_IMG' => $imageName,
                    'PL_IMG2' => $imageName2
                ]);


            // Lưu PlaylistId và SongId vào Collection

            foreach ($data['songsId'] as $songId) {
                DB::table('collection')
                    ->insert(['PL_ID' => $playlistId, 'SO_ID' => $songId]);
            }
        } catch (Exception $ex) {

            Session::flash('fail', 'Lỗi Server');
            dd($ex->getMessage());
            return Redirect::back();
        }

        Session::flash('success', 'Đã Upload');
        return Redirect::to('playlist');
    }
    // GET
    // GET PLAYLIST SIMILAR
    public function getPlaylistSimilar(Request $request, $word)
    {
        try {
            $playlistSimilar = DB::table('playlist')
                                ->where("PL_NAME", "LIKE", "%" . $word . "%")
                                ->where("PL_TYPE", "!=", 0)
                                ->get();

            return $playlistSimilar;
        } catch (Exception $err) {
            return response()->json(['errors' => $err->getMessage()]);
        }
    }
}
