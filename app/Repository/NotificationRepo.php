<?php

namespace App\Repository;

use App\Models\Notification;
use App\Models\ReadNotif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationRepo
{
    public function setNotification($id_entreprise, $description, $url)
    {
        //on récupère juste le chemin sans le domaine
        $route = parse_url($url, PHP_URL_PATH);

        $notif = Notification::create([
            'description' => $description,
            'link' => $route,
            'sub_id' => Auth::user()->sub_id,
            'id_user' => Auth::user()->id,
            'id_entreprise' => $id_entreprise,
        ]);

        $users = DB::table('users')->where('sub_id', Auth::user()->sub_id)->get();

        foreach($users as $user)
        {
            ReadNotif::create([
                'id_notif' => $notif->id,
                'id_user' => $user->id,
                'id_sender' => Auth::user()->id,
            ]);
        }
    }

    public function setNotificationSpecificUsr($id_entreprise, $description, $url, $id_user)
    {
        //on récupère juste le chemin sans le domaine
        $route = parse_url($url, PHP_URL_PATH);

        $notif = Notification::create([
            'description' => $description,
            'link' => $route,
            'sub_id' => Auth::user()->sub_id,
            'id_user' => Auth::user()->id,
            'id_entreprise' => $id_entreprise,
        ]);

        ReadNotif::create([
            'id_notif' => $notif->id,
            'id_user' => $id_user,
            'id_sender' => Auth::user()->id,
        ]);

    }
}