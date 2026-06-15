<?php

namespace Tests\Feature\Admin;

use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LokasiAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Kategori $kategori;
    private Kecamatan $kecamatan;
    private Kelurahan $kelurahan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Restoran',
            'icon' => 'restaurant',
        ]);

        $this->kecamatan = Kecamatan::create([
            'nama_kecamatan' => 'Koto Tangah',
        ]);

        $this->kelurahan = Kelurahan::create([
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'nama_kelurahan' => 'Tabing',
        ]);
    }

    public function test_admin_can_store_location_with_existing_category(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.lokasi.store'), [
            'nama_tempat' => 'RM Sederhana',
            'id_kategori' => $this->kategori->id_kategori,
            'alamat' => 'Jl. Hamka No. 10',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.8923,
            'longitude' => 100.3541,
            'deskripsi' => 'Rumah makan Padang enak',
        ]);

        $response->assertRedirect(route('admin.lokasi.index'));
        $response->assertSessionHas('success', 'Lokasi berhasil ditambahkan');

        $this->assertDatabaseHas('lokasi', [
            'nama_tempat' => 'RM Sederhana',
            'id_kategori' => $this->kategori->id_kategori,
            'alamat' => 'Jl. Hamka No. 10',
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_store_location_with_new_category_on_the_fly(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.lokasi.store'), [
            'nama_tempat' => 'Cafe Sunyi',
            'id_kategori' => 'lainnya',
            'kategori_baru' => 'Kafe Hening',
            'alamat' => 'Jl. Veteran No. 5',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.9123,
            'longitude' => 100.3641,
        ]);

        $response->assertRedirect(route('admin.lokasi.index'));
        $response->assertSessionHas('success', 'Lokasi berhasil ditambahkan');

        $this->assertDatabaseHas('kategori', [
            'nama_kategori' => 'Kafe Hening',
        ]);

        $newKategori = Kategori::where('nama_kategori', 'Kafe Hening')->first();
        $this->assertNotNull($newKategori);

        $this->assertDatabaseHas('lokasi', [
            'nama_tempat' => 'Cafe Sunyi',
            'id_kategori' => $newKategori->id_kategori,
            'created_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_update_location_with_new_category_on_the_fly(): void
    {
        $this->actingAs($this->admin);

        $lokasi = Lokasi::create([
            'nama_tempat' => 'Tempat Lama',
            'id_kategori' => $this->kategori->id_kategori,
            'alamat' => 'Alamat Lama',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.9234,
            'longitude' => 100.3756,
            'status_verifikasi' => 'disetujui',
            'created_by' => $this->admin->id,
        ]);

        $response = $this->put(route('admin.lokasi.update', $lokasi->id_lokasi), [
            'nama_tempat' => 'Tempat Baru',
            'id_kategori' => 'lainnya',
            'kategori_baru' => 'Kategori Baru Update',
            'alamat' => 'Alamat Baru',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.9234,
            'longitude' => 100.3756,
        ]);

        $response->assertRedirect(route('admin.lokasi.index'));
        $response->assertSessionHas('success', 'Lokasi berhasil diperbarui');

        $this->assertDatabaseHas('kategori', [
            'nama_kategori' => 'Kategori Baru Update',
        ]);

        $newKategori = Kategori::where('nama_kategori', 'Kategori Baru Update')->first();
        $this->assertNotNull($newKategori);

        $this->assertDatabaseHas('lokasi', [
            'id_lokasi' => $lokasi->id_lokasi,
            'nama_tempat' => 'Tempat Baru',
            'id_kategori' => $newKategori->id_kategori,
        ]);
    }

    public function test_kategori_baru_is_required_when_id_kategori_is_lainnya(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('admin.lokasi.store'), [
            'nama_tempat' => 'RM Sederhana',
            'id_kategori' => 'lainnya',
            'kategori_baru' => '',
            'alamat' => 'Jl. Hamka No. 10',
            'id_kecamatan' => $this->kecamatan->id_kecamatan,
            'id_kelurahan' => $this->kelurahan->id_kelurahan,
            'latitude' => -0.8923,
            'longitude' => 100.3541,
        ]);

        $response->assertSessionHasErrors(['kategori_baru']);
    }
}
