<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Repository\GenerateRefenceNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    //
    protected $request;
    protected $generateReferenceNumber;

    function __construct(Request $request, GenerateRefenceNumber $generateReferenceNumber)
    {
        $this->request = $request;
        $this->generateReferenceNumber = $generateReferenceNumber;
    }

    public function super_admin_dashboard()
    {
        return view('super_admin.super_admin_dashboard');
    }

    public function subscription()
    {
        $subscriptions = DB::table('subscriptions')->orderByDesc('id')->get();

        return view('super_admin.subscription', compact('subscriptions'));
    }

    public function user_super_admin()
    {
        return view('super_admin.user_super_admin');
    }

    public function add_subscription($id)
    {
        $subscription = DB::table('subscriptions')->where('id', $id)->first();
        $id_sub = $id;

        $state = null;
        $text = null;
        $type = null;

        if($subscription)
        {
            $subscriptionEndDate = Carbon::parse(date('Y-m-d', strtotime($subscription->end_date))); // Example end date
            $currentDate = Carbon::now();

            if($currentDate->lessThanOrEqualTo($subscriptionEndDate)) {
                $state = __('super_admin.active');
                $text = "is-valid";
            }
            else {
                $state = __('super_admin.expired');
                $text = "is-invalid";
            }

            if($subscription->type == 1)
            {
                $type = "Business";
            }
            else if($subscription->type == 2)
            {
                $type = "Prenium";
            }
            else
            {
                $type = "Startup";
            }
        }
        return view('super_admin.add_subscription', compact('subscription', 'id_sub', 'state', 'text', 'type'));
    }

    public function create_subscription()
    {
        $id_sub = $this->request->input('id_sub');
        $customerRequest = $this->request->input('customerRequest');
        $type_sub = $this->request->input('type_sub');
        $description_sub = $this->request->input('description_sub');
        $start_date_sub = $this->request->input('start_date_sub');
        $end_date_sub = $this->request->input('end_date_sub');

        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumberSimple("subscriptions");
            $ref = $this->generateReferenceNumber->generate("SUB", $refNum);

            Subscription::create([
                'reference' => $ref,
                'reference_number' => $refNum,
                'type' => $type_sub,
                'description' => $description_sub,
                'start_date' => $start_date_sub,
                'end_date' => $end_date_sub,
            ]);

            return redirect()->route('app_subscription')->with('success', __('super_admin.subscription_added_successfully'));
        }
        else
        {
            DB::table('subscriptions')
                ->where('id', $id_sub)
                ->update([
                    'type' => $type_sub,
                    'description' => $description_sub,
                    'start_date' => $start_date_sub,
                    'end_date' => $end_date_sub,
            ]);

            return redirect()->route('app_subscription')->with('success', __('super_admin.subscription_updated_successfully'));
        }
    }

    public function delete_subscription()
    {
        $id_sub = $this->request->input('id_element');

        DB::table('subscriptions')->where('id', $id_sub)->delete();

        return redirect()->route('app_subscription')->with('success', __('super_admin.subscription_deleted_successfully'));
    }
}
