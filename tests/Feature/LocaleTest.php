<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    public function test_home_defaults_to_english_without_locale_cookie(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Short links', false);
        $response->assertSee('Get started', false);
        $response->assertSee('lang="en"', false);
    }

    public function test_home_uses_french_when_locale_cookie_is_fr(): void
    {
        $response = $this->withUnencryptedCookie('locale', 'fr')->get('/');

        $response->assertOk();
        $response->assertSee('Liens courts', false);
        $response->assertSee('Commencer', false);
        $response->assertSee('lang="fr"', false);
    }

    public function test_invalid_locale_cookie_falls_back_safely(): void
    {
        $response = $this->withUnencryptedCookie('locale', 'de')->get('/');

        $response->assertOk();
        $response->assertSee('lang="en"', false);
        $response->assertSee('Get started', false);
    }
}
