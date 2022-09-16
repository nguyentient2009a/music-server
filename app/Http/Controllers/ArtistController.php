<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ArtistController extends Controller
{

    // VIEW - GET
    // List
    public function listArtistView()
    {

        try {
            $artistList = DB::table("ARTIST")->get();

            // dd($artistList);
            return view("artist.list", ["artistList" => $artistList]);
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            return Redirect::back();
        }
    }

    // VIEW - GET
    // Create
    public function createArtistView()
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


        return view('artist.create', ['songList' => $songList]);
    }

    // VIEW - POST
    // Create
    public function createArtist(Request $request)
    {

        try {

            // Lưu ảnh vô thư mục artist: public/storage/artist-image/<image_name>
            if ($request->hasFile('artist_image')) {
                $imagePath = Storage::putFile('artist-image', $request->file('artist_image'));
                $imageName = basename(($imagePath)); // trả về tên file
            }

            // Lưu vào db
            DB::table("ARTIST")->insert([
                "AR_NAME" => trim($request->artist_name, " "),
                "AR_STORY" => trim($request->artist_des, " "),
                "AR_IMG" => $imageName
            ]);
            Session::flash('success', 'Đã Upload');
            return Redirect::to("artist/create");
        } catch (Exception $ex) {
            Session::flash('fail', 'Lỗi Server');
            return Redirect::back();
        }
    }


    // API - GET
    public function getAlbumByArtistId($artistId)
    {
        try {
            // Get Album
            $albums = DB::table("ALBUM")->where("AR_ID", "=", $artistId)->get();

            return $albums;
        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }
    // GET
    // GET LIST ARTIST SIMILAR
    public function getlistArtistSimilar(Request $request, $word)
    {
        try {
            $artistList = DB::table("ARTIST")
                            ->where("AR_NAME",  'LIKE', '%' . $word . '%')
                            ->get();

            // dd($artistList);
            return $artistList;
        } catch (Exception $ex) {
            return response()->json(["error" => $ex->getMessage()]);
        }
    }

}
