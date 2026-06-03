<?php

namespace Tests\Feature\Admin;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BannerAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $kontributor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Create a non-admin user
        $this->kontributor = User::factory()->create([
            'role' => 'kontributor',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_banner_crud(): void
    {
        $this->get(route('admin.banners.index'))->assertRedirect(route('login'));
        $this->get(route('admin.banners.create'))->assertRedirect(route('login'));
        $this->post(route('admin.banners.store'), [])->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_banner_crud(): void
    {
        $this->actingAs($this->kontributor);

        $this->get(route('admin.banners.index'))->assertStatus(403);
        $this->get(route('admin.banners.create'))->assertStatus(403);
        $this->post(route('admin.banners.store'), [])->assertStatus(403);
    }

    public function test_admin_can_view_banners_list(): void
    {
        $this->actingAs($this->admin);

        $banner1 = Banner::create([
            'title' => 'Banner Promosi 1',
            'image' => 'banners/promo1.png',
            'link' => 'https://example.com/1',
            'is_active' => true,
            'order' => 1,
        ]);

        $banner2 = Banner::create([
            'title' => 'Banner Promosi 2',
            'image' => 'banners/promo2.png',
            'link' => 'https://example.com/2',
            'is_active' => false,
            'order' => 2,
        ]);

        $response = $this->get(route('admin.banners.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Promosi 1');
        $response->assertSee('Banner Promosi 2');
        $response->assertSee('Aktif');
        $response->assertSee('Nonaktif');
    }

    public function test_admin_can_view_create_page(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.banners.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Banner');
    }

    public function test_store_validation_requires_title_and_image_and_valid_order(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.banners.store'), [
            'title' => '',
            'image' => '',
            'order' => -1,
        ]);

        $response->assertSessionHasErrors(['title', 'image', 'order']);
    }

    public function test_admin_can_store_banner(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $image = UploadedFile::fake()->image('banner_hero.jpg');

        $response = $this->post(route('admin.banners.store'), [
            'title' => 'Hero Banner',
            'image' => $image,
            'link' => 'https://padang.go.id',
            'order' => 5,
            'is_active' => 'on',
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $response->assertSessionHas('success', 'Banner berhasil ditambahkan');

        $this->assertDatabaseHas('banner', [
            'title' => 'Hero Banner',
            'link' => 'https://padang.go.id',
            'order' => 5,
            'is_active' => true,
        ]);

        $banner = Banner::first();
        Storage::disk('public')->assertExists($banner->image);
    }

    public function test_admin_can_view_edit_page(): void
    {
        $this->actingAs($this->admin);

        $banner = Banner::create([
            'title' => 'Banner Edit',
            'image' => 'banners/edit.png',
            'link' => 'https://example.com/edit',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->get(route('admin.banners.edit', $banner->id_banner));

        $response->assertStatus(200);
        $response->assertSee('Edit Banner');
        $response->assertSee('Banner Edit');
    }

    public function test_admin_can_update_banner_details_without_replacing_image(): void
    {
        $this->actingAs($this->admin);

        $banner = Banner::create([
            'title' => 'Original Title',
            'image' => 'banners/original.png',
            'link' => 'https://original.com',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->put(route('admin.banners.update', $banner->id_banner), [
            'title' => 'Updated Title',
            'link' => 'https://updated.com',
            'order' => 2,
            // image omitted
        ]);

        $response->assertRedirect(route('admin.banners.index'));
        $response->assertSessionHas('success', 'Banner berhasil diperbarui');

        $this->assertDatabaseHas('banner', [
            'id_banner' => $banner->id_banner,
            'title' => 'Updated Title',
            'link' => 'https://updated.com',
            'order' => 2,
            'image' => 'banners/original.png', // image remains unchanged
            'is_active' => false, // unchecked is_active defaults to false
        ]);
    }

    public function test_admin_can_update_banner_image_and_deletes_old_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        // Upload original image first
        $originalFile = UploadedFile::fake()->image('original.jpg');
        $originalPath = $originalFile->store('banners', 'public');

        $banner = Banner::create([
            'title' => 'Original',
            'image' => $originalPath,
            'link' => 'https://original.com',
            'is_active' => true,
            'order' => 1,
        ]);

        Storage::disk('public')->assertExists($originalPath);

        // Upload new image
        $newFile = UploadedFile::fake()->image('new.jpg');

        $response = $this->put(route('admin.banners.update', $banner->id_banner), [
            'title' => 'Updated',
            'image' => $newFile,
            'link' => 'https://original.com',
            'order' => 1,
            'is_active' => 'on',
        ]);

        $response->assertRedirect(route('admin.banners.index'));

        // Check original image is deleted and new image exists
        Storage::disk('public')->assertMissing($originalPath);

        $banner->refresh();
        Storage::disk('public')->assertExists($banner->image);
        $this->assertNotEquals($originalPath, $banner->image);
    }

    public function test_admin_can_delete_banner_and_deletes_its_image(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->image('delete_me.jpg');
        $path = $file->store('banners', 'public');

        $banner = Banner::create([
            'title' => 'To Delete',
            'image' => $path,
            'link' => 'https://delete.com',
            'is_active' => true,
            'order' => 1,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->delete(route('admin.banners.destroy', $banner->id_banner));

        $response->assertRedirect(route('admin.banners.index'));
        $response->assertSessionHas('success', 'Banner berhasil dihapus');

        $this->assertDatabaseMissing('banner', [
            'id_banner' => $banner->id_banner,
        ]);

        Storage::disk('public')->assertMissing($path);
    }
}
