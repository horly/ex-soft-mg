<?php

namespace App\Repository;

use App\Models\Entreprise;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntrepriseRepo 
{
    public function getAll()
    {
        return DB::table('entreprises')->get();
    }

    public function getEntrepriseBySub()
    {
        $user = Auth::user();
        
        return Entreprise::where('sub_id', $user->sub_id)
                            ->orderBy('id', 'asc')
                            ->get();
    }

    public function add($name, $rccm, $idnat, $address, $nif, $website, $slogan)
    {
        /*return Entreprise::create([
            'name' => 
        ]);*/
    }
}