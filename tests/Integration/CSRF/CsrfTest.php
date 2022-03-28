<?php

namespace Tests\Integration\CSRF;


class CsrfTest extends \Tests\TestCase
{

    /**
     * Check, if csrf protection working correctly
     *
     * @return void
     */
    public function test_csrf_protection()
    {
        $this->post(route('csrf.get', ['_token' => '']))->assertStatus(419);
    }

}
