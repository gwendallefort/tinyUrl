<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_accessible_and_contains_structured_data(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('application/ld+json', false)
            ->assertSee('https://schema.org', false);
    }

    public function test_robots_txt_is_accessible(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function test_sitemap_xml_contains_home_url(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(url('/'), false);
    }

    public function test_health_endpoint_is_accessible(): void
    {
        $this->get('/up')->assertOk();
    }
}
