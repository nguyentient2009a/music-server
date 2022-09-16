<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;


class SongController extends Controller
{
    // VIEW
    // Xem danh sách 
    // GET
    public function viewList()
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

        // dd($songList);
        return view('song.list', ['songList' => $songList]);
    }

    // VIEW
    // Tạo bài hát
    // GET
    public function viewCreate()
    {

        $genreList = DB::table('GENRE')->get();
        $artistList = DB::table('ARTIST')->get();

        // dd($genreList, $artistList);

        return view('song.create', ['genreList' => $genreList, 'artistList' => $artistList]);
    }

    // VIEW
    // Tạo bài hát
    // POST

    public function create(Request $request)
    {

        // dd($request);

        $data = [
            'song_name' => trim($request->song_name, " "), //cắt khoảng trắng 2 bên của tên
            'GE_ID' => $request->genre_id,
            'AR_ID1' => $request->artist_id1,
            'AR_ID2' => $request->artist_id2,
            'AR_ID3' => $request->artist_id3
        ];

        try {

            // Lưu ảnh vô thư mục song: public/storage/song-image/<image_name>
            if ($request->hasFile('song_image')) {
                $imagePath = Storage::putFile('song-image', $request->file('song_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // Lưu file nhạc vô thư mục public/storeage/song/
            if ($request->hasFile('song_mp3')) {
                $songPath = Storage::putFile('song', $request->file('song_mp3'));
                $songName = basename($songPath);
            }

            // Lưu song db
            $songId = DB::table('song')
                ->insertGetId([
                    'SO_NAME' => $data['song_name'],
                    'GE_ID' => $data['GE_ID'],
                    'SO_SRC' => $songName,
                    'SO_IMG' => $imageName
                ]);

            // Lưu Artist_Song db
            DB::table('artist_song')
                ->insert([
                    ['SO_ID' => $songId, 'AR_ID' => $data['AR_ID1']],
                    ['SO_ID' => $songId, 'AR_ID' => $data['AR_ID2']],
                    ['SO_ID' => $songId, 'AR_ID' => $data['AR_ID3']],
                ]);
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            return Redirect::back();
        }

        Session::flash('success', 'Đã Upload');
        return Redirect::to('song');
    }


    // Lấy Thông tin Bài Hát
    // GET
    public function GetSong(Request $request, $id)
    {
        $host = $request->getHttpHost();
        $song = DB::table('SONG')->where('SO_ID', '=', $id)->first();

        return response()->json($song);
    }

    // GET
    // Lấy thông tin 5 bài hát mới
    public function GetNew()
    {
        $songList = DB::table('SONG')
            ->orderBy('SO_ID', 'desc')
            ->limit(4)
            ->get();

        return $songList;
    }

    // GET
    // Lấy thông tin danh sách bài hát liên quan 
    public function getListSongRelated(Request $request, $word)
    {

        // $songList = DB::table('SONG')
        //         ->where('SO_NAME', 'LIKE', ''.$word.'%')
        //         ->join('artist_song', 'artist_song.song')
        //         ->get();

        $songs = DB::table('SONG')->where('SONG.SO_NAME', 'LIKE', '' . $word . '%')
            ->get();


        // $songs = DB::table('collection')
        //     ->join('song', 'song.SO_ID', '=', 'collection.SO_ID')
        //     ->where('SONG.SO_NAME', 'LIKE', '' . $word . '%')
        //     ->get();



        for ($i = 0; $i < sizeof($songs); $i++) {
            $songId = $songs[$i]->SO_ID;

            $artists = DB::table('artist')
                ->join('artist_song', 'artist_song.AR_ID', '=', 'artist.AR_ID')
                ->where('artist_song.SO_ID', '=', $songId)
                ->select(['artist.AR_ID', 'artist.AR_NAME'])
                ->get();

            $songs[$i]->ARTISTS =  $artists;
        }
        // dd($songs);

        return $songs;
    }

    // GET
    // Lấy thông tin bài hát bằng tên bài hát
    public function getSongInfoByName(Request $request, $name)
    {
        $host = $request->getHttpHost();
        $listsong = DB::table('SONG')
            ->where('SO_NAME', '=', $name);

        return $listsong;
    }
}
