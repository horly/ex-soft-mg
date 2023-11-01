<?php

namespace App\Http\Controllers;

use App\Http\Requests\FunctionalUnitForm;
use App\Models\FunctionalUnit;
use App\Models\FunctionalunitEmail;
use App\Models\FunctionalUnitPhone;
use App\Models\ManageFU;
use App\Repository\EntrepriseRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FunctionalUnitController extends Controller
{
    //
    //
    protected $request;
    protected $entrepriseRepo;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
    }

    public function saveFunctionalUnit(FunctionalUnitForm $requestionF)
    {
        $user = Auth::user();

        $name = $requestionF->input('unit_name');
        $address = $requestionF->input('unit_address');
        $unit_phone = $requestionF->input('unit_phone');
        $unit_email = $requestionF->input('unit_email');
        $id_entreprise = $requestionF->input('id_entreprise');

        $functUnit = FunctionalUnit::create([
            'name' => $name,
            'address' => $address, 
            'id_entreprise' => $id_entreprise,
            'sub_id' => $user->id,
        ]);

        FunctionalUnitPhone::create([
            'phone_number' => $unit_phone,
            'id_func_unit' => $functUnit->id
        ]);

        FunctionalunitEmail::create([
            'email' => $unit_email,
            'id_func_unit' => $functUnit->id
        ]);

        return redirect()->route('app_entreprise', ['id' => $id_entreprise])->with('success', __('entreprise.functional_unit_saved_successfully'));
    }

    public function modules($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        
        return view('functional_unit.modules', compact('entreprise', 'functionalUnit'));
    }

    public function assignFUtoUSer()
    {
        $id_functionalUnit = $this->request->input('id_functionalUnit');
        $id_entreprise = $this->request->input('id_entreprise');
        $id_user = $this->request->input('id_user');

        ManageFU::create([
            'id_user' => $id_user,
            'id_entreprise' => $id_entreprise,
            'id_fu' => $id_functionalUnit,
        ]);

        return redirect()->back()->with('success', __('entreprise.functional_has_been_successfully_assigned_to_the_user'));
    }

    public function deleteManagementFU()
    {
        $id_entreprise = $this->request->input('id_element1');
        $id_user = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('manage_f_u_s')
                    ->where([
                        'id_user' => $id_user,
                        'id_entreprise' => $id_entreprise,
                        'id_fu' => $id_fu,
                    ])->delete();

        return redirect()->back()->with('success', __('entreprise.functional_unit_assignment_was_successfully_deleted'));
    }
}
