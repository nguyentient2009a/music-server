<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
Route::get('migrate', function() {
    Artisan::call('migrate:fresh -—seed');
});
Route::get('/', 'SongController@viewList');


// SONG

Route::group(['prefix' => 'song'], function () {

    // view
    Route::get('', 'SongController@viewList');
    Route::get('/create', 'SongController@viewCreate');
    Route::post('/create', 'SongController@create');


    //api
    Route::get('/new', "SongController@GetNew");
    Route::get('/{id}', 'SongController@GetSong');
    Route::get('/search/{name}', 'SongController@getSongInfoByName');
    Route::get('/songrelate/{word}', 'SongController@getListSongRelated');
});



// PLAYLIST

Route::group(['prefix' => 'playlist'], function () {

    //view
    Route::get('', 'PlaylistController@showListView');
    Route::get('/create', 'PlaylistController@createPlaylistView');
    Route::post('/create', 'PlaylistController@createPlaylist');


    // api
    Route::get('/newest', 'PlaylistController@getNewest');
    Route::get('/type/{type}/{number}', 'PlaylistController@getPlaylistByType');
    Route::get('/similar/{word}', 'PlaylistController@getPlaylistSimilar');
});



// COLLECTION

Route::group(['prefix' => 'collection'], function () {
    Route::get('/{playlist_id}', 'CollectionController@getCollectionByPlaylistId'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id

});


// USER
Route::group(['prefix' => 'user'], function () {
    // api
    Route::get('/liked-song/{user_id}', 'UserController@getLikedSong'); // Lấy danh sách tất cả bài hát của 1 user
    Route::get('/liked-playlist/{user_id}', 'UserController@getLikedPlaylist'); // Lấy danh sách tất cả bài hát của 1 playlist bằng playlist_id
    Route::get('/playlist/{userId}', 'UserController@getPlaylistByUserId');
    Route::get('/liked/playlist/{user_id}', 'UserController@getPlaylistLiked'); // Lấy danh sách đã like
    Route::get('/liked/album/{user_id}', 'UserController@getAlbumLiked'); // lấy danh sách album đã like
    Route::post('/register', 'UserController@register');
    Route::post('/login', 'UserController@login');
    Route::post('/like', 'UserController@like');
    Route::get('/liked_song/{user_id}/{song_id}', 'UserController@checkLikeSong'); // Kiểm tra bài hát đã được thích hay chưa?
    Route::post('/new_userplaylist', 'UserController@createNewPlaylist'); // Add New User Playlist
    Route::post('/addsong_to_userplaylist', 'UserController@addSongToUserPlaylist'); // Add Song to Playlist
    Route::post('/change-info', 'UserController@changeUserInfo'); // Đổi thông tin user
    Route::get('/check-playlist-liked/{user_id}/{playlist_id}', 'UserController@checkPlaylistIsLiked');
    Route::get('/check-album-liked/{user_id}/{album_id}', 'UserController@checkAlbumIsLiked');
    Route::get('/like-album/{user_id}/{album_id}', 'UserController@likeAlbum');
    Route::get('/like-playlist/{user_id}/{playlist_id}', 'UserController@likePlaylist');
});


// ARTIST
Route::group(['prefix' => 'artist'], function () {
    // view
    Route::get('/', "ArtistController@listArtistView");
    Route::get('/create', "ArtistController@createArtistView");
    Route::post('/create', "ArtistController@createArtist");

    // api
    Route::get('/artist-album/{artist_id}', "ArtistController@getAlbumByArtistId");
    Route::get("/similar/{word}", "ArtistController@getlistArtistSimilar");
});

// ALBUM
Route::group(['prefix' => 'album'], function () {
    Route::get('/newest', 'AlbumController@getNewestAlbum');
    // view
    Route::get('/', 'AlbumController@listAlbumView');
    Route::get('/create', "AlbumController@createAlbumView");
    Route::post('/create', "AlbumController@createAlbum");

    // api
    Route::get('/song-album/{albumId}', 'AlbumController@getSongAlbumById');
    Route::get('/{albumId}', 'AlbumController@getAlbumById');
});
