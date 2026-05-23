<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\PricingRule;
use App\Models\Route;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataResourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_branch_resource_pages(): void
    {
        $this->seed();

        $branch = Branch::query()->firstOrFail();
        $user = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin/branches')
            ->assertOk();

        $this->actingAs($user)
            ->get('/admin/branches/create')
            ->assertOk();

        $this->actingAs($user)
            ->get("/admin/branches/{$branch->id}/edit")
            ->assertOk();
    }

    public function test_admin_can_access_route_resource_pages(): void
    {
        $this->seed();

        $route = Route::query()->firstOrFail();
        $user = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin/routes')
            ->assertOk();

        $this->actingAs($user)
            ->get('/admin/routes/create')
            ->assertOk();

        $this->actingAs($user)
            ->get("/admin/routes/{$route->id}/edit")
            ->assertOk();
    }

    public function test_admin_can_access_pricing_rule_resource_pages(): void
    {
        $this->seed();

        $pricingRule = PricingRule::query()->firstOrFail();
        $user = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin/pricing-rules')
            ->assertOk();

        $this->actingAs($user)
            ->get('/admin/pricing-rules/create')
            ->assertOk();

        $this->actingAs($user)
            ->get("/admin/pricing-rules/{$pricingRule->id}/edit")
            ->assertOk();
    }
}
