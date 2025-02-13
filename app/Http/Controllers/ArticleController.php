<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleForm;
use App\Http\Requests\CreateCategoryForm;
use App\Http\Requests\CreateSubCategoryForm;
use App\Models\Article;
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

    public function categoryArticle($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_articles = DB::table('category_articles')
                    ->where('id_fu', $functionalUnit->id)
                    ->whereNot('default', 1)
                    ->orderByDesc('id')
                    ->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.category-article', compact('entreprise', 'functionalUnit', 'category_articles', 'permission_assign'));
    }

    public function addNewCategoryArticle($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.add_new_category-article', compact('entreprise', 'functionalUnit', 'permission_assign'));
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

            $cat_art_saved = CategoryArticle::create([
                'reference_cat_art' => $ref,
                'reference_number' => $refNum,
                'name_cat_art' => $name_cat,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_info_article_category', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $cat_art_saved->id]);
            $description = "article.added_a_new_article_category_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
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
            $url = route('app_info_article_category', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_cat_art]);
            $description = "article.updated_an_article_category";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_category_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.item_category_updated_successfully'));
        }
    }

    public function infoArticleCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_article = DB::table('users')
                    ->join('category_articles', 'category_articles.id_user', '=', 'users.id')
                    ->where('category_articles.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.info_category-article', compact('entreprise', 'functionalUnit', 'category_article', 'permission_assign'));
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

    public function updateArticleCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $category_article = DB::table('category_articles')->where('id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.update_category-article', compact('entreprise', 'functionalUnit', 'category_article', 'permission_assign'));
    }


    /**
     * Subcategory article
     */

    public function subCategoryArticle($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $subcategory_articles = DB::table('category_articles')
                    ->join('subcategory_articles', 'subcategory_articles.id_cat', '=', 'category_articles.id')
                    ->where('subcategory_articles.id_fu', $functionalUnit->id)
                    ->whereNot('subcategory_articles.default', 1)
                    ->orderByDesc('subcategory_articles.id')
                    ->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.subcategory-article', compact('entreprise', 'functionalUnit', 'subcategory_articles', 'permission_assign'));
    }

    public function addNewSubCategoryArticle($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $category_articles = DB::table('category_articles')
                ->where('id_fu', $functionalUnit->id)
                ->orderByDesc('id')
                ->get();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.add_new_subcategory-article', compact('entreprise', 'functionalUnit', 'category_articles', 'permission_assign'));
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

            $sub_cat_art_saved = SubcategoryArticle::create([
                'reference_subcat_art' => $ref,
                'reference_number' => $refNum,
                'name_subcat_art' => $name_subcat,
                'id_cat' => $cat_art_sub,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_info_article_subcategory', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $sub_cat_art_saved->id]);
            $description = "article.added_a_new_article_subcategory_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_subcategory_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
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
            $url = route('app_info_article_subcategory', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_subcat_art]);
            $description = "article.updated_an_article_subcategory";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_subcategory_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.item_subcategory_updated_successfully'));
        }
    }

    public function infoArticleSubCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $subcategory_article = DB::table('users')
                    ->join('subcategory_articles', 'subcategory_articles.id_user', '=', 'users.id')
                    ->where('subcategory_articles.id', $id3)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.info_sub_categorie-article', compact('entreprise', 'functionalUnit', 'subcategory_article', 'permission_assign'));
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

    public function updateArticleSubCategory($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();
        $subcategory_article = DB::table('subcategory_articles')->where('id', $id3)->first();

        $category_articles = DB::table('category_articles')->where('id_fu', $functionalUnit->id)->orderByDesc('id')->get();

        $cat_art_get = DB::table('category_articles')->where('id', $subcategory_article->id_cat)->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.update_subcategory-article', compact(
                        'entreprise',
                        'functionalUnit',
                        'category_articles',
                        'subcategory_article',
                        'cat_art_get',
                        'permission_assign'
        ));
    }

    /**
     * Article
     */
    public function article($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $articles = DB::table('users')
                    ->join('articles', 'articles.id_user', '=', 'users.id')
                    ->where('articles.id_fu', $functionalUnit->id)
                    ->orderByDesc('articles.id')
                    ->get();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
                    ])->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.article', compact('entreprise', 'functionalUnit', 'articles', 'deviseGest', 'permission_assign'));
    }

    public function addNewArticle($group, $id, $id2)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $subcategory_articles = DB::table('subcategory_articles')
                ->where('id_fu', $functionalUnit->id)
                ->orderByDesc('id')
                ->get();

        $deviseGest = DB::table('devises')
                ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                ->where([
                    'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                    'devise_gestion_ufs.default_cur_manage' => 1,
            ])->first();


        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.add_new_article', compact('entreprise', 'functionalUnit', 'subcategory_articles', 'deviseGest', 'permission_assign'));
    }

    public function createArticle(CreateArticleForm $requestF)
    {
        $id_entreprise = $requestF->input('id_entreprise');
        $id_fu = $requestF->input('id_fu');
        $id_art = $requestF->input('id_art');
        $description_art = $requestF->input('description_art');
        $subcat_art = $requestF->input('subcat_art');
        $purchase_price_art = $requestF->input('purchase_price_art');
        $sale_prise_art = $requestF->input('sale_prise_art');
        $number_in_stock_art = $requestF->input('number_in_stock_art');
        $customerRequest = $requestF->input('customerRequest');


        if($customerRequest != "edit")
        {
            $refNum = $this->generateReferenceNumber->getReferenceNumber("articles", $id_fu);
            $ref = $this->generateReferenceNumber->generate("ART", $refNum);

            $article_saved = Article::create([
                'reference_art' => $ref,
                'reference_number' => $refNum,
                'description_art' => $description_art,
                'purchase_price' => $purchase_price_art,
                'sale_price' => $sale_prise_art,
                'number_in_stock' => $number_in_stock_art,
                'id_sub_cat' => $subcat_art,
                'id_fu' => $id_fu,
                'id_user' => Auth::user()->id,
            ]);

            //Notification
            $url = route('app_info_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $article_saved->id]);
            $description = "article.added_a_new_article_in_the_functional_unit";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.article_added_successfully'));
        }
        else
        {
            DB::table('articles')
                ->where('id', $id_art)
                ->update([
                    'description_art' => $description_art,
                    'purchase_price' => $purchase_price_art,
                    'sale_price' => $sale_prise_art,
                    'number_in_stock' => $number_in_stock_art,
                    'id_sub_cat' => $subcat_art,
                    'updated_at' => new \DateTimeImmutable,
            ]);

            //Notification
            $url = route('app_info_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu, 'id3' => $id_art]);
            $description = "article.updated_an_article";
            $this->notificationRepo->setNotification($id_entreprise, $description, $url);

            return redirect()->route('app_article', ['group' => 'stock', 'id' => $id_entreprise, 'id2' => $id_fu ])
                    ->with('success', __('article.article_updated_successfully'));
        }
    }

    public function infoArticle($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $article = DB::table('users')
                    ->join('articles', 'articles.id_user', '=', 'users.id')
                    ->where('articles.id', $id3)->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
                    ])->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.info_article', compact('entreprise', 'functionalUnit', 'article', 'deviseGest', 'permission_assign'));
    }

    public function deleteArticle()
    {
        $id_art = $this->request->input('id_element1');
        $id_entreprise = $this->request->input('id_element2');
        $id_fu = $this->request->input('id_element3');

        DB::table('articles')->where('id', $id_art)->delete();

        //Notification
        $url = route('app_article', ['id' => $id_entreprise, 'id2' => $id_fu]);
        $description = "article.deleted_an_article_in_the_functional_unit";
        $this->notificationRepo->setNotification($id_entreprise, $description, $url);

        return redirect()->route('app_article', [
            'id' => $id_entreprise,
            'id2' => $id_fu ])->with('success', __('article.article_successfully_deleted'));
    }

    public function updateArticle($group, $id, $id2, $id3)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        $functionalUnit = DB::table('functional_units')->where('id', $id2)->first();

        $article = DB::table('users')
                    ->join('articles', 'articles.id_user', '=', 'users.id')
                    ->where('articles.id', $id3)->first();

        $subcategory_articles = DB::table('subcategory_articles')
                    ->where('id_fu', $functionalUnit->id)
                    ->orderByDesc('id')
                    ->get();

        $subcategory_art = DB::table('subcategory_articles')
                    ->where('id', $article->id_sub_cat)
                    ->orderByDesc('id')
                    ->first();

        $deviseGest = DB::table('devises')
                    ->join('devise_gestion_ufs', 'devise_gestion_ufs.id_devise', '=', 'devises.id')
                    ->where([
                        'devise_gestion_ufs.id_fu' => $functionalUnit->id,
                        'devise_gestion_ufs.default_cur_manage' => 1,
                    ])->first();

        $edit_delete_contents = DB::table('permissions')->where('name', 'edit_delete_contents')->first();

        $permission_assign = DB::table('permission_assigns')
                ->where([
                    'id_user' => Auth::user()->id,
                    'id_fu' => $id2,
                    'id_perms' => $edit_delete_contents->id
                ])->first();

        return view('article.update_article', compact(
            'entreprise',
            'functionalUnit',
            'article',
            'deviseGest',
            'subcategory_articles',
            'subcategory_art',
            'permission_assign'
        ));
    }
}
