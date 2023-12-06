<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryForm;
use App\Http\Requests\CreateSubCategoryForm;
use App\Models\CategoryArticle;
use App\Models\SubcategoryArticle;
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


    /**
     * Subcategory article
     */

    public function subCategoryArticle($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $subcategory_articles = DB::table('category_articles')
                    ->join('subcategory_articles', 'subcategory_articles.id_cat', '=', 'category_articles.id')
                    ->where('subcategory_articles.id_fu', $functionalUnit->id)
                    ->orderByDesc('subcategory_articles.id')
                    ->get();
                    
        return view('article.subcategory-article', compact('entreprise', 'functionalUnit', 'subcategory_articles'));
    }

    public function addNewSubCategoryArticle($id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_articles = DB::table('category_articles')
                ->where('id_fu', $functionalUnit->id)
                ->orderByDesc('id')
                ->get();

        return view('article.add_new_subcategory-article', compact('entreprise', 'functionalUnit', 'category_articles'));
    }

    public function createSubCategoryArticle(CreateSubCategoryForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_subcat_art = $requestF->input('id_subcat_art');
        $name_subcat = $requestF->input('name_subcat');
        $cat_art_sub = $requestF->input('cat_art_sub');
        $customerRequest = $requestF->input('customerRequest');


        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("subcategory_articles", $id_fu);
            $ref = $this->generateReferenceNumber->generate("SCA", $refNum);

            SubcategoryArticle::create([
                'reference_subcat_art' => $ref,
                'reference_number' => $refNum,
                'name_subcat_art' => $name_subcat,
                'id_cat' => $cat_art_sub,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_subcategory_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "article.added_a_new_article_subcategory_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_subcategory_article', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.article_subcategory_added_successfully'));
        }
        else
        {
            DB::table('subcategory_articles')
                ->where('id', $id_subcat_art)
                ->update([
                    'name_subcat_art' => $name_subcat,
                    'id_cat' => $cat_art_sub,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_subcategory_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
            $description = "article.updated_an_article_subcategory";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_subcategory_article', ['id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.item_subcategory_updated_successfully'));
        }
    }

    public function infoArticleSubCategory($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $subcategory_article = DB::table('users')
                    ->join('subcategory_articles', 'subcategory_articles.id_user', '=', 'users.id')
                    ->where('subcategory_articles.id', $id3)->first();

        return view('article.info_sub_categorie-article', compact('entreprise', 'functionalUnit', 'subcategory_article'));
    }

    public function deleteSubCategoryArticle()
    {
        $id_subcat_art = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('subcategory_articles')->where('id', $id_subcat_art)->delete();

        //Notification
        $url = route('app_subcategory_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "article.deleted_an_article_subcategory_in_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_subcategory_article', [
            'id' => $id_entreprise, 
            'id2' => $id_fu ])->with('success', __('article.article_subcategory_successfully_deleted'));
    }

    public function updateArticleSubCategory($id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $subcategory_article = DB::table('subcategory_articles')->where('id', $id3)->first();

        $category_articles = DB::table('category_articles')->where('id_fu', $functionalUnit->id)->orderByDesc('id')->get();

        $cat_art_get = DB::table('category_articles')->where('id', $subcategory_article->id_cat)->first();

        return view('article.update_subcategory-article', compact(
                        'entreprise', 
                        'functionalUnit', 
                        'category_articles',
                        'subcategory_article', 
                        'cat_art_get'
        ));
    }
}
