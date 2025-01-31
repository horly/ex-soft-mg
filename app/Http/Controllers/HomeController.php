<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Manage;
use App\Models\PermissionAssign;
use App\Repository\ConnectionHistoryRepo;
use App\Repository\EntrepriseRepo;
use App\Repository\NotificationRepo;
use App\Services\Permissions\Permissions;
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
    protected $notificationRepo;

    function __construct(Request $request, EntrepriseRepo $entrepriseRepo, ConnectionHistoryRepo $connectHistory, NotificationRepo $notificationRepo)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->connectHistory = $connectHistory;
        $this->notificationRepo = $notificationRepo;
    }

    public function main()
    {
        $entreprises = null;
        $user = Auth::user();

        $user->role->name == 'admin' || $user->role->name == 'superadmin'
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

        $histories = $this->connectHistory->getHistoryByUser($user->id);

        return view('main.user-management-info', compact('user', 'histories'));
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

        //Notification
        $url = route('app_entreprise', ['id' => $id_entreprise]);
        $description = "entreprise.added_you_in_the_company";
        $this->notificationRepo->setNotificationSpecificUsr($id_entreprise, $description, $url, $id_user);

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

        //$permissions = DB::table('permissions')->get();

        $functionalUnits = DB::table('functional_units')->where('id_entreprise', $entreprise->id)->get();

        return view('main.assign_functional_unit_to_user', compact('entreprise', 'user', 'functionalUnits'));
    }

    public function readNotif()
    {
        $id = $this->request->input('id_element');
        $user = Auth::user();

        $readNotif = DB::table('read_notifs')->where('id', $id)->first();
        $notification = DB::table('notifications')->where('id', $readNotif->id_notif)->first();
        $hostwithHttp = request()->getSchemeAndHttpHost();

        $route = $hostwithHttp . $notification->link;

        $read = DB::table('read_notifs')
                    ->where([
                        'id_user' => $user->id,
                        'id_notif' => $id,
                    ])->first();

        DB::table('read_notifs')
                ->where('id', $id)
                ->update([
                    'read' => 1
                ]);

        return redirect($route);
    }

    public function allNotif()
    {
        $notifsAll = null;

        if(Auth::user()->role->name == "admin" || Auth::user()->role->name == "superadmin")
        {
            $notifsAll = DB::table('notifications')
                        ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                        ->where('read_notifs.id_user', Auth::user()->id)
                        ->orderBy('read_notifs.id', 'desc')
                        ->paginate(5);
        }
        else
        {
            $notifsAll = DB::table('notifications')
                    ->join('manages', 'manages.id_entreprise', '=', 'notifications.id_entreprise')
                    ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                    ->where('read_notifs.id_user', Auth::user()->id)
                    ->orderBy('read_notifs.id', 'desc')
                    ->paginate(5);
        }
        return view('main.all-notification', compact('notifsAll'));
    }

    public function unviewedNotif()
    {
        $notifsAll = null;

        if(Auth::user()->role->name == "admin")
        {
            $notifsAll = DB::table('notifications')
                        ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                        ->where([
                            'read_notifs.id_user' => Auth::user()->id,
                            'read_notifs.read'=> 0,
                        ])
                        ->orderBy('read_notifs.id', 'desc')
                        ->paginate(5);
        }
        else
        {
            $notifsAll = DB::table('notifications')
                    ->join('manages', 'manages.id_entreprise', '=', 'notifications.id_entreprise')
                    ->join('read_notifs', 'read_notifs.id_notif', '=', 'notifications.id')
                    ->where([
                        'read_notifs.id_user' => Auth::user()->id,
                        'read_notifs.read' => 0,
                    ])
                    ->orderBy('read_notifs.id', 'desc')
                    ->paginate(5);
        }
        return view('main.unviewed-notifications', compact('notifsAll'));
    }

    public function get_permission()
    {
        $id_user = $this->request->input('id_user');
        $id_fu = $this->request->input('id_fu');

        $full_dashboard_view = DB::table('permissions')->where('name', 'full_dashboard_view')->first();
        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();
        $billing = DB::table('permissions')->where('name', 'billing')->first();
        $report_generation = DB::table('permissions')->where('name', 'report_generation')->first();

        $full_dashboard_view_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $full_dashboard_view->id,
        ])->first();

        $edit_delete_contents_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $edit_delete_contents->id,
        ])->first();

        $billing_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $billing->id,
        ])->first();

        $report_generation_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $report_generation->id,
        ])->first();

        $full_dash = 0;
        $edit_del = 0;
        $bill = 0;
        $report = 0;

        if($full_dashboard_view_assgn)
        {
            $full_dash = 1;
        }

        if($edit_delete_contents_assgn)
        {
            $edit_del = 1;
        }

        if($billing_assgn)
        {
            $bill = 1;
        }

        if($report_generation_assgn)
        {
            $report = 1;
        }

        return response()->json([
            'full_dashboard_view_assgn' => $full_dash,
            'edit_delete_contents_assgn' => $edit_del,
            'billing_assgn' => $bill,
            'report_generation_assgn' => $report,
        ]);
    }

    public function save_permissions()
    {
        //dd($this->request->all());

        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $group = 'global';

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $group
        ])->delete();

        if($this->request->has('full_dashboard_view'))
        {
            $permission = DB::table('permissions')->where('name', "full_dashboard_view")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $group,
            ]);
        }

        if($this->request->has('edit_delete_contents'))
        {
            $permission = DB::table('permissions')->where('name', "edit_delete_contents")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $group,
            ]);
        }

        if($this->request->has('billing'))
        {
            $permission = DB::table('permissions')->where('name', "billing")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $group,
            ]);
        }

        if($this->request->has('report_generation'))
        {
            $permission = DB::table('permissions')->where('name', "report_generation")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $group,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_contact_permissions()
    {

        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $customer = "customer";
        $supplier = "supplier";
        $creditor = "creditor";
        $debtor = "debtor";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $customer,
        ])->delete();

        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $supplier,
        ])->delete();

        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $creditor,
        ])->delete();

        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $debtor,
        ])->delete();

        if($this->request->has('customer_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_customer")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $customer,
            ]);
        }

        if($this->request->has('suppliers_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_supplier")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $supplier,
            ]);
        }

        if($this->request->has('creditors_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_creditor")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $creditor,
            ]);
        }

        if($this->request->has('debtors_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_debtor")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $debtor,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_stock_permissions()
    {

        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $stock = "stock";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $stock,
        ])->delete();


        if($this->request->has('stock_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_stock")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $stock,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_service_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $service = "service";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $service,
        ])->delete();


        if($this->request->has('service_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_service")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $service,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_currency_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $currency = "currency";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $currency,
        ])->delete();


        if($this->request->has('currency_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_currency")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $currency,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_payment_method_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $payment_method = "payment_method";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $payment_method,
        ])->delete();


        if($this->request->has('payment_method_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_payment_method")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $payment_method,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_debt_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $debt = "debt";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $debt,
        ])->delete();


        if($this->request->has('debt_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_debt")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $debt,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_receivable_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $receivable = "receivable";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $receivable,
        ])->delete();


        if($this->request->has('receivable_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_receivable")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $receivable,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_sales_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $sale = "sale";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $sale,
        ])->delete();


        if($this->request->has('sale_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_sale")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $sale,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_expense_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $expense = "expense";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $expense,
        ])->delete();


        if($this->request->has('expense_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_expense")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $expense,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function save_report_permissions()
    {
        $id_fu = $this->request->input('id_fu');
        $id_user = $this->request->input('id_user');
        $report = "report";

        //on supprime toutes les permissions de l'utilisateur
        DB::table('permission_assigns')
            ->where([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'group' => $report,
        ])->delete();


        if($this->request->has('report_assign'))
        {
            $permission = DB::table('permissions')->where('name', "app_report")->first();
            PermissionAssign::create([
                'id_user' => $id_user,
                'id_fu' => $id_fu,
                'id_perms' => $permission->id,
                'group' => $report,
            ]);
        }

        return redirect()->back()->with('success', __('main.permissions_saved_successfully'));
    }

    public function permissions($id_user, $id_fu)
    {
        $user = DB::table('users')->where('id', $id_user)->first();
        $fu = DB::table('functional_units')->where('id', $id_fu)->first();
        $entreprise = DB::table('entreprises')->where('id', $fu->id_entreprise)->first();

        $permissions = new Permissions($id_user, $id_fu);

        $dataA = $permissions->getGlobalPermissions();
        $global_permissions = $dataA->getData(true);

        $dataB = $permissions->getContactPermission();
        $contact_permissions = $dataB->getData(true);

        $dataC = $permissions->getStockPermission();
        $stock_permissions = $dataC->getData(true);

        $dataD = $permissions->getServicePermission();
        $service_permissions = $dataD->getData(true);

        $dataE = $permissions->getCurrencyPermission();
        $currency_permissions = $dataE->getData(true);

        $dataF = $permissions->getPaymentMethodPermission();
        $payment_method_permissions = $dataF->getData(true);

        $dataG = $permissions->getDebtPermission();
        $debt_permissions = $dataG->getData(true);

        $dataH = $permissions->getReceivablePermission();
        $receivable_permissions = $dataH->getData(true);

        $dataI = $permissions->getSalePermission();
        $sale_permissions = $dataI->getData(true);

        $dataJ = $permissions->getExpensePermission();
        $expense_permissions = $dataJ->getData(true);

        $dataK = $permissions->getReportPermission();
        $report_permissions = $dataK->getData(true);


        //dd($global_permissions['billing_assgn']);

        return view('main.permissions', compact(
            'user',
            'fu',
            'entreprise',
            'global_permissions',
            'contact_permissions',
            'stock_permissions',
            'service_permissions',
            'currency_permissions',
            'payment_method_permissions',
            'debt_permissions',
            'receivable_permissions',
            'sale_permissions',
            'expense_permissions',
            'report_permissions'
        ));
    }
}
