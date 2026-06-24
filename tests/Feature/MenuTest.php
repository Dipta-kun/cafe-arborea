<?php

namespace Tests\Feature;

use App\Models\Kategori;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed database
        $this->seed();
    }

    public function test_can_create_menu_with_image()
    {
        Storage::fake('public');
        $user = User::where('email', 'mayang@gmail.com')->first();
        $kategori = Kategori::first();

        $response = $this->actingAs($user)->postJson(route('admin.menu.store'), [
            'kategori_id' => $kategori->id,
            'nama_menu'   => 'Teh Manis Hangat',
            'deskripsi'   => 'Teh manis hangat segar',
            'harga'       => 5000,
            'stok'        => 50,
            'is_tersedia' => true,
            'foto'        => UploadedFile::fake()->image('teh.jpg'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $menu = Menu::where('nama_menu', 'Teh Manis Hangat')->first();
        $this->assertNotNull($menu);
        $this->assertNotNull($menu->foto);
        
        // Assert file exists in fake public disk
        Storage::disk('public')->assertExists($menu->foto);
    }

    public function test_can_update_menu_image()
    {
        Storage::fake('public');
        $user = User::where('email', 'mayang@gmail.com')->first();
        $menu = Menu::first();

        // Check original foto path
        $originalFoto = $menu->foto;

        $response = $this->actingAs($user)->patchJson(route('admin.menu.update', $menu->id), [
            'kategori_id' => $menu->kategori_id,
            'nama_menu'   => $menu->nama_menu . ' Updated',
            'deskripsi'   => $menu->deskripsi,
            'harga'       => $menu->harga,
            'stok'        => $menu->stok,
            'is_tersedia' => $menu->is_tersedia,
            'foto'        => UploadedFile::fake()->image('updated_teh.png'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $menu->refresh();
        $this->assertNotEquals($originalFoto, $menu->foto);
        $this->assertNotNull($menu->foto);
        
        // Assert file exists in fake public disk
        Storage::disk('public')->assertExists($menu->foto);
    }

    public function test_updating_menu_without_image_keeps_original_image()
    {
        Storage::fake('public');
        $user = User::where('email', 'mayang@gmail.com')->first();
        $menu = Menu::first();

        // Set a dummy image
        $menu->update(['foto' => 'menu/original.jpg']);

        $response = $this->actingAs($user)->patchJson(route('admin.menu.update', $menu->id), [
            'kategori_id' => $menu->kategori_id,
            'nama_menu'   => $menu->nama_menu . ' No Image Update',
            'deskripsi'   => $menu->deskripsi,
            'harga'       => $menu->harga,
            'stok'        => $menu->stok,
            'is_tersedia' => $menu->is_tersedia,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $menu->refresh();
        $this->assertEquals('menu/original.jpg', $menu->foto);
    }
}
