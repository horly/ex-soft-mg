<?php

namespace App\Repository;
use Illuminate\Support\Facades\DB;

class GradeRepo
{
    public function getGradeAll()
    {
        return DB::table('grades')->get();
    }
}
