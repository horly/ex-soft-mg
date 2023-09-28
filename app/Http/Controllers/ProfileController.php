<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    //
    protected $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function savePhoto()
    {
        $image = $this->request->input('image-saved');
        $type = $this->request->input('type-photo');
        $id_entreprise = $this->request->input('id-entreprise');


        if($type == "entreprise")
        {
            //on hashe uplodad_profile + le md5 uniqid + l'id de l'utilisateur
            $image_hash = 'upload_profile' . md5(uniqid()) . $id_entreprise;
            //$folderPath = base_path() . '/public_html/images/profile/';
            $folderPath = public_path() . '/assets/img/logo/entreprise/';

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $image_hash . '.png';
            file_put_contents($file, $image_base64);

            DB::table('entreprises')
                ->where('id', $id_entreprise)
                ->update([
                'url_logo' => $image_hash,
                'updated_at' => new \DateTimeImmutable
            ]);

            return redirect()->back()->with('success', __('entreprise.photo_saved_successfully'));
        }
        else
        {

        }
    }
}
