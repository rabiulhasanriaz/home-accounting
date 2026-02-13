<?php

namespace Tests\Unit;

use App\Http\Controllers\AccountController;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Tests\TestCase;

class AccountTest extends TestCase
{


    /** @test */
    public function it_stores_an_account_and_redirects_back_with_success_message()
    {
        // Arrange
        $payload = [
            'spender' => 1,
            'purpose' => 2,
            'date'    => '2026-02-05',
            'amount'  => 1500,
            'remarks' => 'Bought printer ink',
        ];

        // Create a Request instance (Unit test style: call controller directly)
        $request = Request::create('/accounts', 'POST', $payload);

        // Important: redirect()->back() relies on "referer" (previous URL)
        $request->headers->set('referer', '/previous-page');

        // Bind this request into the container so url()->previous() works
        $this->app->instance('request', $request);

        $controller = new AccountController();

        // Act
        $response = $controller->store($request);

        // Assert response
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringEndsWith('/previous-page', $response->getTargetUrl());

        // Assert DB saved
        $this->assertDatabaseHas('account', $payload);

        // Assert flash message
        $this->assertEquals(
            'Account Added Successfully',
            session('success')
        );
    }
}
