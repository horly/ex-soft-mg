<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class GenerateRefenceNumber
{
    public function generate($code, $number)
    {
        $size = "0000000";
        $numlength = strlen((string)$number);

        $valuefull = $size . $number;
        $value = substr((string)$valuefull, $numlength);

        $generated = $code . $value;

        return $generated;
    }

    public function getReferenceNumber($table, $id_fu)
    {
        $data = DB::table($table)->where('id_fu', $id_fu)->first();
        $ref = 1;

        if($data)
        {
            $dataGet = DB::table($table)->where('id', DB::raw("(select max(`id`) from " . $table . ")"))->first();
            $refGet = $dataGet->reference_number;
            $ref = $ref + $refGet;
        }

        return $ref;
    }

    public function getReferenceNumberSimple($table)
    {
        $data = DB::table($table)->first();
        $ref = 1;

        if($data)
        {
            $dataGet = DB::table($table)->where('id', DB::raw("(select max(`id`) from " . $table . ")"))->first();
            $refGet = $dataGet->reference_number;
            $ref = $ref + $refGet;
        }

        return $ref;
    }
}
