<?php

namespace App\Services\Reference;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Reference
{
    protected $f_firstname;
    protected $f_lastname;
    protected $year;

    function __construct($name)
    {
        /**
         * On génère une référence pour chaque user
         * par exemple
         * IK/001/2024
         */
        $user_name = strtoupper($name); //Tous mettre en majuscule
        $array = explode(" ", $user_name);
        $firstname = $array[0];
        $lastname = $array[1];

        $this->f_firstname = str_split($firstname); //On converti la chaine en tableau dont chaque lettre est un élément du tableau
        $this->f_lastname = str_split($lastname);

        $this->year = date('Y');
    }

    public function getProformaReference()
    {
        $sales_invoice = DB::table('sales_invoices')
            ->where('is_proforma_inv', 1)
            ->whereYear("created_at", $this->year)
            ->count();

        $number_count = str_pad($sales_invoice + 1, 3, '0', STR_PAD_LEFT); //La longueur souhaitée de la chaîne finale 001 par exemple.

        return $this->f_firstname[0] . $this->f_lastname[0] . "/" . $number_count . "/" . $this->year;
    }

    public function getInvoiceReference()
    {
        $sales_invoice = DB::table('sales_invoices')
            ->where('is_simple_invoice', 1)
            ->whereYear("created_at", $this->year)
            ->count();

        $number_count = str_pad($sales_invoice + 1, 3, '0', STR_PAD_LEFT); //La longueur souhaitée de la chaîne finale 001 par exemple.

        return $this->f_firstname[0] . $this->f_lastname[0] . "/" . $number_count . "/" . $this->year;
    }

    public function getDeliveryReference()
    {
        $sales_invoice = DB::table('sales_invoices')
            ->where('is_delivery_note', 1)
            ->whereYear("created_at", $this->year)
            ->count();

        $number_count = str_pad($sales_invoice + 1, 3, '0', STR_PAD_LEFT); //La longueur souhaitée de la chaîne finale 001 par exemple.

        return $this->f_firstname[0] . $this->f_lastname[0] . "/" . $number_count . "/" . $this->year;
    }


}
