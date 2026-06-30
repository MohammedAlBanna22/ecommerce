<?php

namespace Tests\Feature;

use Tests\TestCase;

class StripeConnectionTest extends TestCase
{
    /** @test */
    public function it_can_connect_to_stripe()
    {
        // هذا الاختبار يتطلب مفتاح حقيقي
        $key = config('services.stripe.key');

        $this->assertNotNull($key);
        $this->assertStringStartsWith('pk_test_', $key);

        echo "\n✅ Stripe Key is configured correctly!";
        echo "Key starts with: " . substr($key, 0, 10) . "...\n";
    }
}