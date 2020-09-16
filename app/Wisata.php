<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{

    protected $table = "wisata";

    protected $fillable = [
      'nama_wisata', 'nama_daerah', 'jenis', 'foto', 'deskripsi', 'created_at', 'updated_at'
    ];


}
