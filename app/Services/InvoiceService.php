<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;

class InvoiceService
{
    public function checkInvoiceLimitExceeded($userId)
    {
        // Get all active subscriptions for the user
        $subscriptions = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('created_at', 'asc') // Process oldest first
            ->get();

        // Get base plan (Starter/Pro/Enterprise)
        $basePlan = $subscriptions->where('type', 'plan')->first();
        if (!$basePlan) {
            return true; // No active plan, can't create invoices
        }

        // Get all volume add-ons sorted by purchase date
        $volumeAddOns = $subscriptions->where('type', 'volume');

        // Get invoice count for current billing period
        $invoiceCount = $this->getCurrentBillingPeriodInvoiceCount($userId, $basePlan);

        // Calculate total available invoices
        $totalAvailable = $this->calculateAvailableInvoices($basePlan, $volumeAddOns);

        return $invoiceCount >= $totalAvailable;
    }

    public function getRemainingInvoices($userId)
    {
        $subscriptions = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        $basePlan = $subscriptions->where('type', 'plan')->first();
        if (!$basePlan) {
            return 0;
        }

        $volumeAddOns = $subscriptions->where('type', 'volume');
        $invoiceCount = $this->getCurrentBillingPeriodInvoiceCount($userId, $basePlan);
        $totalAvailable = $this->calculateAvailableInvoices($basePlan, $volumeAddOns);

        return max(0, $totalAvailable - $invoiceCount);
    }

    protected function getCurrentBillingPeriodInvoiceCount($userId, $basePlan)
    {
        $startDate = $this->getBillingPeriodStartDate($basePlan);
        $endDate = $this->getBillingPeriodEndDate($basePlan);

        return Invoice::where('agent_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    protected function calculateAvailableInvoices($basePlan, $volumeAddOns)
    {
        // Base plan invoices
        $baseInvoices = $this->getPlanInvoiceLimit($basePlan->plan_name);
        $available = $baseInvoices;

        // Process volume add-ons in chronological order
        foreach ($volumeAddOns as $addOn) {
            $available += $this->getAddOnInvoiceLimit($addOn->plan_name);
        }

        return $available;
    }

    protected function getBillingPeriodStartDate($subscription)
    {
        $today = Carbon::now();
        $startDate = Carbon::parse($subscription->start_date);

        if ($subscription->plan_duration === 'monthly') {
            // Find how many full months have passed since subscription started
            $monthsPassed = $startDate->diffInMonths($today);
            return $startDate->copy()->addMonths($monthsPassed);
        } else { // annual
            $yearsPassed = $startDate->diffInYears($today);
            return $startDate->copy()->addYears($yearsPassed);
        }
    }

    protected function getBillingPeriodEndDate($subscription)
    {
        $startDate = $this->getBillingPeriodStartDate($subscription);

        if ($subscription->plan_duration === 'monthly') {
            return $startDate->copy()->addMonth();
        } else {
            return $startDate->copy()->addYear();
        }
    }

    protected function getPlanInvoiceLimit($planName)
    {
        $limits = [
            'starter' => Plan::where('name', 'starter')->first()->invoices_per_month,
            'pro' => Plan::where('name', 'pro')->first()->invoices_per_month,
            'enterprise' => Plan::where('name', 'enterprise')->first()->invoices_per_month
        ];

        return $limits[$planName] ?? 0;
    }

    protected function getAddOnInvoiceLimit($addOnName)
    {
        $limits = [
            'Volume Mini' => 500,
            'Volume Medium' => 1500,
            'Volume Max' => 4000,
            'Unlimited' => PHP_INT_MAX // Unlimited
        ];

        return $limits[$addOnName] ?? 0;
    }
}
