<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Wisata;
use File;

class WisataController extends Controller
{

    public function getAll($limit = 10, $offset = 0){
      try{
	        $data["count"] = Wisata::count();
	        $wisata = array();

	        foreach (Wisata::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "nama_wisata" => $p->nama_wisata,
	                "nama_daerah" => $p->nama_daerah,
	                "deskripsi"   => $p->deskripsi,
                  "jenis"    	  => $p->jenis,
                  "foto"        => $p->foto,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($wisata, $item);
	        }
	        $data["wisata"] = $wisata;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function tambah(Request $request){
      $wisata = new Wisata();
      $wisata->nama_wisata = $request->nama_wisata;
      $wisata->nama_daerah = $request->nama_daerah;
      $wisata->jenis = $request->jenis;
      $wisata->deskripsi = $request->deskripsi;

        //Proses Upload Foto
        $file = $request->file('foto');
        $nama_file = time()."_".$file->getClientOriginalName();

        $tujuan_upload = 'Backend_WEB';
        $file->move($tujuan_upload,$nama_file);

      $wisata->foto = $nama_file;
      $wisata->save();

      return response()->json([
        'Status' => '1',
        'Message' => 'Data Wisata Berhasil Diinputkan'
      ], 201);
    }

    public function edit(Request $request){
      $wisata = Wisata::where('id', $request->id)->first();
      $wisata->nama_wisata = $request->nama_wisata;
      $wisata->nama_daerah = $request->nama_daerah;
      $wisata->jenis = $request->jenis;
      $wisata->deskripsi = $request->deskripsi;

        //Proses Upload Foto
        $file = $request->file('foto');
        $nama_file = time()."_".$file->getClientOriginalName();

        $tujuan_upload = 'Backend_WEB';
        $file->move($tujuan_upload,$nama_file);

      $wisata->foto = $nama_file;
      $wisata->save();

    return response()->json([
      'Status' => '1',
      'Message' => 'Data Wisata Berhasil DiEdit'
    ], 201);
    }

    public function hapus($id){
      try{
          $wisata = Wisata::where("id", $id)->first();
          File::delete('Backend_WEB/'.$wisata->foto);

          Wisata::where("id", $id)->delete();

          return response([
            "status"	=> 1,
              "message"   => "Data berhasil dihapus."
          ]);
      } catch(\Exception $e){
          return response([
            "status"	=> 0,
              "message"   => $e->getMessage()
          ]);
      }
    }

    public function cari(Request $request, $limit = 10, $offset = 0){
      $find = $request->find;
      $wisata = Wisata::where("id","like","%$find%")
      ->orWhere("nama_wisata","like","%$find%")
      ->orWhere("nama_daerah","like","%$find%");
      $data["count"] = $wisata->count();
      $wisatas = array();
      foreach ($wisata->skip($offset)->take($limit)->get() as $p) {
      $item = [
        "id"          => $p->id,
        "nama_wisata" => $p->nama_wisata,
        "nama_daerah" => $p->nama_daerah,
        "deskripsi"   => $p->deskripsi,
        "jenis"    	  => $p->jenis,
        "foto"        => $p->foto,
        "created_at"  => $p->created_at,
        "updated_at"  => $p->updated_at
      ];
      array_push($wisatas,$item);
      }
      $data["wisata"] = $wisatas;
      $data["status"] = 1;
      return response($data);
    }
}
