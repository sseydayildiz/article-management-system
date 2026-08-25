<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SENARYO 1: Giriş yapmamış kullanıcı /articles sayfasına girmek isterse 
     * auth middleware'i onu login sayfasına yönlendirmeli.
     */
    public function test_unauthenticated_user_cannot_access_articles_index(): void
    {
        // 1. EYLEM (Act): Giriş yapmadan /articles sayfasına istek atıyoruz
        $response = $this->get(route('articles.index'));

        // 2. DOĞRULAMA (Assert): Login sayfasına yönlendirildiğini kontrol ediyoruz
        $response->assertRedirect(route('login'));
    }

    public function test_autodetect_articles_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('articles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('articles.index');
        $response->assertViewHas(['articles' , 'categories']);
    }

    public function test_user_only_sees_their_own_articles(): void
    {
        $ipekYildiz=User::factory()->create(['name'=> 'İpek']);
        $yigitYildiz = User::factory()->create(['name' => 'Yiğit Yıldız']);
        $kivancYildiz = User::factory()->create(['name' => 'Kıvanç Yıldız']);

        $category= Category::factory()->create();

        Article::factory()->create([
            'user_id' => $ipekYildiz->id,
            'category_id'=> $category->id,
            'title'=> "İpek'in Gizli Makalesi",
            'is_active' => 1,
        ]);
        Article::factory()->create([
            'user_id' => $yigitYildiz->id,
            'category_id' => $category->id,
            'title' => "Yiğit'in Gizli Makalesi",
            'is_active' => 1,
        ]);

        $response = $this->actingAs($ipekYildiz)->get(route('articles.index'));

        $response->assertStatus(200);
        $response->assertSee("İpek'in Gizli Makalesi");
        $response->assertDontSee("Yiğit'in Gizli Makalesi");
        }

        public function test_user_cannot_view_others_article_edit_page(): void
{
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($attacker)->get(route('articles.edit', $article));

    $response->assertStatus(403);
}

public function test_user_cannot_update_others_article(): void
{
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
        'title' => 'Orijinal Başlık',
    ]);

    $response = $this->actingAs($attacker)->put(route('articles.update', $article), [
        'title' => 'Hacklendi',
        'content' => 'İçerik değişti',
        'category_id' => $category->id,
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseHas('articles', ['id' => $article->id, 'title' => 'Orijinal Başlık']);
}

public function test_user_cannot_delete_others_article(): void
{
    $owner = User::factory()->create();
    $attacker = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($attacker)->delete(route('articles.destroy', $article));

    $response->assertStatus(403);
       $this->assertDatabaseHas('articles', ['id' => $article->id]);
}

public function test_owner_can_delete_their_own_article(): void
{
    $owner = User::factory()->create();
    $category = Category::factory()->create();
    $article = Article::factory()->create([
        'user_id' => $owner->id,
        'category_id' => $category->id,
    ]);

    $response = $this->actingAs($owner)->delete(route('articles.destroy', $article));

    $response->assertStatus(302); // başarılı silme sonrası genelde redirect
    $this->assertDatabaseMissing('articles', ['id' => $article->id]);
}

public function test_user_can_create_article(): void
{
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($user)->post(route('articles.store'), [
        'title' => 'Yeni Makale',
        'content' => 'İçerik',
        'category_id' => $category->id,
    ]);

    $response->assertRedirect(route('articles.index'));
    $this->assertDatabaseHas('articles', [
        'title' => 'Yeni Makale',
        'user_id' => $user->id,
    ]);
}
}