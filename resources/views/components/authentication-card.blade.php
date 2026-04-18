<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color: #f1f5f9;">
    <!-- Logo -->
    <div class="mb-6">
        {{ $logo }}
    </div>

    <!-- Card -->
    <div class="w-full sm:max-w-md">
        <div class="bg-white shadow-xl sm:rounded-2xl overflow-hidden" style="border: 1px solid #e2e8f0;">
            <!-- Brand accent bar -->
            <div class="h-1 w-full" style="background: linear-gradient(to right, #F5C200, #1B2878);"></div>
            <div class="px-8 py-8">
                {{ $slot }}
            </div>
        </div>
        <p class="mt-6 text-center text-xs" style="color: #94a3b8;">
            &copy; {{ date('Y') }} dot.engage &middot; All rights reserved
        </p>
    </div>
</div>
