# Article Management System

Laravel 10 ile geliştirilmiş, kullanıcı bazlı yetkilendirme ve kapsamlı feature test kapsamına sahip bir makale yönetim sistemi.

## Özellikler

- Kullanıcı bazlı makale CRUD işlemleri (oluştur, listele, güncelle, sil)
- Kategoriye göre filtreleme
- Kullanıcı kimlik doğrulama (auth middleware)
- Sahiplik (ownership) tabanlı yetkilendirme — kullanıcılar yalnızca kendi makalelerini görüntüleyip yönetebilir
- Sayfalama (pagination)

## Güvenlik: Geliştirme Sürecinde Bulunan ve Düzeltilen Açık

Bu proje geliştirilirken, `edit`, `update` ve `destroy` işlemlerinde bir **IDOR (Insecure Direct Object Reference)** açığı tespit edildi: kimliği doğrulanmış herhangi bir kullanıcı, URL üzerindeki makale ID'sini değiştirerek başka kullanıcılara ait makaleleri görüntüleyebilir, güncelleyebilir veya silebiliyordu.

**Tespit ve doğrulama süreci:**
1. Açığı kanıtlamak için feature testler yazıldı (`test_user_cannot_update_others_article`, `test_user_cannot_delete_others_article` vb.)
2. Testler başlangıçta **başarısız (red)** çıktı, açığın gerçekten var olduğu doğrulandı
3. Controller'a sahiplik kontrolü eklendi:
   ```php
   if ($article->user_id !== Auth::id()) {
       abort(403);
   }
   ```
4. Testler tekrar çalıştırıldı, **başarılı (green)** sonuç alındı

Bu süreç, "test önce kırmızı, sonra düzelt, sonra yeşil" (red-green) yaklaşımının gerçek bir güvenlik açığı üzerinde nasıl uygulandığını gösteriyor.

## Test Kapsamı

8 feature test, 19 assertion — kimlik doğrulama, veri izolasyonu ve yetkilendirme senaryolarını kapsıyor:

- Giriş yapmamış kullanıcı korumalı sayfalara erişemiyor
- Kullanıcı yalnızca kendi makalelerini listede görüyor
- Kullanıcı başkasının makalesini düzenleme sayfasını açamıyor
- Kullanıcı başkasının makalesini güncelleyemiyor
- Kullanıcı başkasının makalesini silemiyor
- Makale sahibi kendi makalesini silebiliyor
- Kullanıcı yeni makale oluşturabiliyor (ve makale doğru kullanıcıya atanıyor)

Testler, SQLite in-memory veritabanı ile izole bir ortamda çalışıyor — gerçek geliştirme veritabanına dokunmuyor.

```bash
php artisan test --filter=ArticleManagementTest
```

## Teknoloji

- PHP 8.x
- Laravel 10
- Eloquent ORM
- PHPUnit (feature testing)
- SQLite (test ortamı) / MySQL (geliştirme ortamı)
- Blade

## Kurulum

```bash
git clone <repo-url>
cd laravel10
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Öğrenme Süreci

Bu proje, Laravel'i sıfırdan öğrenirken geliştirildi — HTTP request lifecycle, Eloquent ilişkileri, route model binding, middleware ve feature testing kavramları kavram-öncelikli (concept-first) bir yaklaşımla çalışılarak inşa edildi.
