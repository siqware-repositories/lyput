<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    public function playlist_detail(){
        return $this->hasMany(PlaylistDetail::class);
    }
}
