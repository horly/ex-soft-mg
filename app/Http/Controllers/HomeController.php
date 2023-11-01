<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Manage;
use App\Repository\ConnectionHistoryRepo;
use App\Repository\EntrepriseRepo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $connectHistory;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo, ConnectionHistoryRepo $connectHistory)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->connectHistory = $connectHistory;
    }

    public function main()
    {
        $entreprises = null;
        $user = Auth::user();

        $user->role->name == 'admin' 
                ? $entreprises = $this->entrepriseRepo->getEntrepriseBySub() 
                : $entreprises = $this->entrepriseRepo->getEntrepriseByManagement($user);
        
        //dd($entreprises);
        
        return view('main.main', compact('entreprises'));
    }

    public function infosOnlineUser($matricule)
    {
        $user = User::where('matricule', $matricule)->first();
        $grade = Grade::where('id', $user->grade_id)->first();

        return view('main.infos-online-user', [
            'user' => $user,
            'grade' => $grade,
        ]);
    }

    public function userManagement()
    {
        $user = Auth::user();

        $users = User::where('sub_id', $user->sub_id)
                            ->orderBy('id', 'asc')
                            ->get();

        return view('main.user-management',
            compact('users')
        );
    }

    public function loginHistory()
    {
        $user = Auth::user();

        $histories = $this->connectHistory->getHistoryByUser($user->id);

        return view('auth.login-histoty', compact('histories'));
    }

    public function userManagementInfo($id)
    {
        $user = User::where('id', $id)->first();
        
        
        return view('main.user-management-info', compact('user'));
    }

    public function deleteUser()
    {
        $id_user = $this->request->input('id_element');

        DB::table('users')
                    ->where('id', $id_user)
                    ->delete();

        return redirect()->route('app_user_management')->with('success', __('main.user_deleted_successfully'));
    }

    public function assignEntreUser()
    {
        $id_entreprise = $this->request->input('id_entreprise');
        $id_user = $this->request->input('id_user');

        Manage::create([
            'id_user' => $id_user,
            'id_entreprise' => $id_entreprise,
        ]);

        return redirect()->back()->with('success', __('main.the_company_has_been_successfully_assigned_to_the_user'));
    }

    public function deleteManagementEntr()
    {
        $id_entreprise = $this->request->input('id_element1');
        $id_user = $this->request->input('id_element2');

        DB::table('manages')
                    ->where([
                        'id_user' => $id_user,
                        'id_entreprise' => $id_entreprise,
                    ])->delete();

        return redirect()->back()->with('success', __('main.company_assignment_was_successfully_deleted'));
    }

    public function assignFunctUser($id, $idUser)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $user = DB::table('users')->where('id', $idUser)->first();

        $functionalUnits = DB::table('functional_units')->where('id_entreprise', $entreprise->id)->get();

        return view('main.assign_functional_unit_to_user', compact('entreprise', 'user', 'functionalUnits'));
    }
}
