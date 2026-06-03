<?php

namespace Tests\Feature\Api;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_banners_endpoint_is_public(): void
    {
        $response = $this->getJson('/api/banners');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data',
        ]);
    }

    public function test_api_banners_only_returns_active_banners_ordered_by_sequence(): void
    {
        // 1. Create banners in disordered fashion and some inactive
        Banner::create([
            'title' => 'Banner Order 2',
            'image' => 'banners/img2.jpg',
            'link' => 'https://example.com/2',
            'is_active' => true,
            'order' => 2,
        ]);

        Banner::create([
            'title' => 'Banner Inactive',
            'image' => 'banners/inactive.jpg',
            'link' => null,
            'is_active' => false,
            'order' => 1,
        ]);

        Banner::create([
            'title' => 'Banner Order 1',
            'image' => 'banners/img1.jpg',
            'link' => 'https://example.com/1',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->getJson('/api/banners');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data'); // Inactive banner must be excluded

        // Verify ordering: banner with order 1 must be first, order 2 second
        $data = $response->json('data');
        
        $this->assertEquals('Banner Order 1', $data[0]['title']);
        $this->assertEquals('Banner Order 2', $data[1]['title']);

        // Verify that JSON includes the appended image_url attribute
        $this->assertArrayHasKey('image_url', $data[0]);
        $this->assertNotNull($data[0]['image_url']);
        $this->assertStringContainsString('storage/banners/img1.jpg', $data[0]['image_url']);
    }
}
