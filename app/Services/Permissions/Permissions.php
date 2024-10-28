<?php

namespace App\Services\Permissions;

use App\Models\FunctionalUnit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Permissions
{
    protected $id_user;
    protected $id_fu;

    function __construct($id_user, $id_fu)
    {
        $this->id_user = $id_user;
        $this->id_fu = $id_fu;
    }

    public function getGlobalPermissions()
    {
        $full_dashboard_view = DB::table('permissions')->where('name', 'full_dashboard_view')->first();
        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();
        $billing = DB::table('permissions')->where('name', 'billing')->first();
        $report_generation = DB::table('permissions')->where('name', 'report_generation')->first();

        $full_dashboard_view_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $full_dashboard_view->id,
        ])->first();

        $edit_delete_contents_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $edit_delete_contents->id,
        ])->first();

        $billing_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $billing->id,
        ])->first();

        $report_generation_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
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

        $globalPermissions = [
            'full_dashboard_view_assgn' => $full_dash,
            'edit_delete_contents_assgn' => $edit_del,
            'billing_assgn' => $bill,
            'report_generation_assgn' => $report,
        ];

        return response()->json($globalPermissions);
    }

    public function getContactPermission()
    {
        $app_customer = DB::table('permissions')->where('name', 'app_customer')->first();
        $app_supplier = DB::table('permissions')->where('name', 'app_supplier')->first();
        $app_creditor = DB::table('permissions')->where('name', 'app_creditor')->first();
        $app_debtor = DB::table('permissions')->where('name', 'app_debtor')->first();

        $app_customer_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_customer->id,
        ])->first();

        $app_supplier_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_supplier->id,
        ])->first();

        $app_creditor_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_creditor->id,
        ])->first();

        $app_debtor_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_debtor->id,
        ])->first();

        $customer = 0;
        $supplier = 0;
        $creditor = 0;
        $debtor = 0;

        if($app_customer_assgn)
        {
            $customer = 1;
        }

        if($app_supplier_assgn)
        {
            $supplier = 1;
        }

        if($app_creditor_assgn)
        {
            $creditor = 1;
        }

        if($app_debtor_assgn)
        {
            $debtor = 1;
        }

        $contactPermissions = [
            'app_customer_assgn' => $customer,
            'app_supplier_assgn' => $supplier,
            'app_creditor_assgn' => $creditor,
            'app_debtor_assgn' => $debtor,
        ];

        return response()->json($contactPermissions);
    }

    public function getStockPermission()
    {
        $app_stock = DB::table('permissions')->where('name', 'app_stock')->first();

        $app_stock_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_stock->id,
        ])->first();

        $stock = 0;

        if($app_stock_assgn)
        {
            $stock = 1;
        }

        $stockPermissions = [
            'app_stock_assgn' => $stock,
        ];

        return response()->json($stockPermissions);
    }

    public function getServicePermission()
    {
        $app_service = DB::table('permissions')->where('name', 'app_service')->first();

        $app_service_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_service->id,
        ])->first();

        $service = 0;

        if($app_service_assgn)
        {
            $service = 1;
        }

        $servicePermissions = [
            'app_service_assgn' => $service,
        ];

        return response()->json($servicePermissions);
    }

    public function getCurrencyPermission()
    {
        $app_currency = DB::table('permissions')->where('name', 'app_currency')->first();

        $app_currency_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_currency->id,
        ])->first();

        $currency = 0;

        if($app_currency_assgn)
        {
            $currency = 1;
        }

        $currencyPermissions = [
            'app_currency_assgn' => $currency,
        ];

        return response()->json($currencyPermissions);
    }

    public function getPaymentMethodPermission()
    {
        $app_payment_method = DB::table('permissions')->where('name', 'app_payment_method')->first();

        $app_payment_method_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_payment_method->id,
        ])->first();

        $payment_method = 0;

        if($app_payment_method_assgn)
        {
            $payment_method = 1;
        }

        $payment_methodPermissions = [
            'app_payment_method_assgn' => $payment_method,
        ];

        return response()->json($payment_methodPermissions);
    }

    public function getDebtPermission()
    {
        $app_debt = DB::table('permissions')->where('name', 'app_debt')->first();

        $app_debt_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_debt->id,
        ])->first();

        $debt = 0;

        if($app_debt_assgn)
        {
            $debt = 1;
        }

        $debtPermissions = [
            'app_debt_assgn' => $debt,
        ];

        return response()->json($debtPermissions);
    }

    public function getReceivablePermission()
    {
        $app_receivable = DB::table('permissions')->where('name', 'app_receivable')->first();

        $app_receivable_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_receivable->id,
        ])->first();

        $receivable = 0;

        if($app_receivable_assgn)
        {
            $receivable = 1;
        }

        $receivablePermissions = [
            'app_receivable_assgn' => $receivable,
        ];

        return response()->json($receivablePermissions);
    }

    public function getSalePermission()
    {
        $app_sale = DB::table('permissions')->where('name', 'app_sale')->first();

        $app_sale_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_sale->id,
        ])->first();

        $sale = 0;

        if($app_sale_assgn)
        {
            $sale = 1;
        }

        $salePermissions = [
            'app_sale_assgn' => $sale,
        ];

        return response()->json($salePermissions);
    }

    public function getExpensePermission()
    {
        $app_expense = DB::table('permissions')->where('name', 'app_expense')->first();

        $app_expense_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_expense->id,
        ])->first();

        $expense = 0;

        if($app_expense_assgn)
        {
            $expense = 1;
        }

        $expensePermissions = [
            'app_expense_assgn' => $expense,
        ];

        return response()->json($expensePermissions);
    }

    public function getReportPermission()
    {
        $app_report = DB::table('permissions')->where('name', 'app_report')->first();

        $app_report_assgn = DB::table('permission_assigns')
            ->where([
                'id_user' => $this->id_user,
                'id_fu' => $this->id_fu,
                'id_perms' => $app_report->id,
        ])->first();

        $report = 0;

        if($app_report_assgn)
        {
            $report = 1;
        }

        $reportPermissions = [
            'app_report_assgn' => $report,
        ];

        return response()->json($reportPermissions);
    }

}
