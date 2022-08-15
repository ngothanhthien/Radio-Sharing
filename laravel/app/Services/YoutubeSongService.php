<?php 
namespace App\Services;

class YoutubeSongService{
    protected $songService;
    public function __construct(YoutubeSongService $songService)
    {
        $this->songService = $songService;
    }
    public function addSongToUser($songs){
        
    }
}