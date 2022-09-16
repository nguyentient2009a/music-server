<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Exception;

class CollectionController extends Controller
{
    // Playlist theo ID
    // Get
    public function getCollectionByPlaylistId($playlistId)
    {

        try {
            $playlist = DB::table('playlist')->where('PL_ID', '=', $playlistId)->first();

            $songs = DB::table('collection')
                ->join('song', 'song.SO_ID', '=', 'collection.SO_ID')
                ->where('PL_ID', '=', $playlistId)
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

            return response()->json(['playlist' => $playlist, 'songs' => $songs, 'artists' => $artists]);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
