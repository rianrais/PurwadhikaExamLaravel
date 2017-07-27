<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\UnitRumah;

class ProductController extends Controller
{
    public function CreateUnit(Request $request){
        DB::beginTransaction(); /* Fungsi ini agar dapat melakukan banyak transaksi data 
                                kedalam database. */

        try 
        {
            //Membuat validasi atas property.
            $this->validate($request, [
                'nomor_rumah' => 'required',
                'harga' => 'required',
                'luas_tanah' => 'required',
                'luas_bangunan' => 'required'
            ]);
            
            //Memasukan input request kedalam variable.
            $addKavling = $request->input('kavling');
            $addBlock = $request->input('blok');
            $addNo = $request->input('nomor_rumah');
            $addPrice = $request->input('harga');
            $addGroundSize = $request->input('luas_tanah');
            $addPropertySize = $request->input('luas_bangunan');

            //Dari variable diatas dimasukan kedalam DB.
            $product = new Unit;
            $product->kavling = $addKavling;
            $product->blok = $addBlock;
            $product->nomor_rumah = $addNo;
            $product->harga = $addPrice;
            $product->luas_tanah = $addGroundSize;
            $product->luas_bangunan = $addPropertySize;
            $product->save();


            // Setelah save() mereturn data kembali.
            $newProduct = Unit::get();

            /*Message yang dikeluarkan setelah sukses memasukan data.
            Fungsi DB commit memasukan semua data yang sukses */
            DB::commit();
            return response()->json($newProduct, 200);
        }

        catch(\Exception $e) 
        {
            DB::rollBack(); /* Fungsi ini merollback apabila terdapat 
                            data yang gagal/error masuk kedalam DB */
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function DeleteUnit(Request $request){
        DB::beginTransaction();
        try {     
            // Raw Query Deletion, jawaban untuk soal exam:
            $id = $request->input('id');
            $pList = DB::delete('delete from units where id = ?', [$id]);
            
            //Eloquent 
            $newProduct = Unit::get();

            DB::commit();
            return response()->json($vendor, 200);
        }

        catch(\Exception $e) 
        {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);

        }
    }
}
