<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_robots_txt_lists_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertSuccessful()
            ->assertSee('Sitemap:', false);
    }

    public function test_sitemap_xml_contains_home_url(): void
    {
        $this->get('/sitemap.xml')
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(url('/'), false);
    }

    public function test_homepage_includes_structured_data(): void
    {
        $this->get('/')
            ->assertSuccessful()
            ->assertSee('application/ld+json', false)
            ->assertSee('https://schema.org', false);
    }
}
