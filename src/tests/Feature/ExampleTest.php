<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_returns_a_successful_response(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_unknown_page_uses_custom_404_view(): void
    {
        $this->get('/page-qui-n-existe-pas')
            ->assertNotFound()
            ->assertSee("Oupss... Cette page n'existe plus !", false)
            ->assertSee(route('home'));
    }
}
