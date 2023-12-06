<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryForm;
use App\Models\CategoryArticle;
use App\Repository\EntrepriseRepo;
use App\Repository\GenerateRefenceNumber;
use App\Repository\NotificationRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    //
    protected $request;
    protected $entrepriseRepo;
    protected $notificationRepo;
    protected $generateReferenceNumber;

    function __construct(Request $request, 
                            EntrepriseRepo $entrepriseRepo, 
                            NotificationRepo $notificationRepo, 
                            GenerateRefenceNumber $generateReferenceNumber)
    {
        $this->request = $request;
        $this->entrepriseRepo = $entrepriseRepo;
        $this->notificationRepo = $notificationRepo;
        $this->generateReferenceNumber = $generateReferenceNumber;
    }

    public function categoryArticle($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_articles = DB::table('category_articles')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();
                    
        return view('article.category-article', compact('entreprise', 'functionalUnit', 'category_articles'));
    }

    public function addNewCategoryArticle($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        return view('article.add_new_category-article', compact('entreprise', 'functionalUnit'));
    }

    public function createCategoryArticle(CreateCategoryForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_cat_art = $requestF->input('id_cat_art');
        $name_cat = $requestF->input('name_cat');
        $customerRequest = $requestF->input('customerRequest');


        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("category_articles", $id_fu);
            $ref = $this->generateReferenceNumber->generate("CA", $refNum);

            CategoryArticle::create([
                'reference_cat_art' => $ref,
                'reference_number' => $refNum,
                'name_cat_art' => $name_cat,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_category_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "article.added_a_new_article_category_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_article', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.item_category_added_successfully'));
        }
        else
        {
            DB::table('category_articles')
                ->where('id', $id_cat_art)
                ->update([
                    'name_cat_art' => $name_cat,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_category_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "article.updated_an_article_category";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_article', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.item_category_updated_successfully'));
        }
    }

    public function infoArticleCategory($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_article = DB::table('users')
                    ->join('category_articles', 'category_articles.id_user', '=', 'users.id')
                    ->where('category_articles.id', $id3)->first();

        return view('article.info_category-article', compact('entreprise', 'functionalUnit', 'category_article'));
    }

    public function deleteCategoryArticle()
    {
        $id_cat_art = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('category_articles')->where('id', $id_cat_art)->delete();

        //Notification
        $url = route('app_category_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "article.deleted_an_article_category_in_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_category_article', [
            'id' => $id_entreprise, 
            'id2' => $id_fu ])->with('success', __('article.article_category_successfully_deleted'));
    }

    public function updateArticleCategory($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_article = DB::table('category_articles')->where('id', $id3)->first();

        return view('article.update_category-article', compact('entreprise', 'functionalUnit', 'category_article'));
    }
}
