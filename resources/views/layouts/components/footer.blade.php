<footer class="mt-8">
    <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;"
       class="block bg-[#37475A] text-white text-center py-3 text-[13px] hover:bg-[#232F3E] transition-colors">
        Back to top
    </a>
    <div class="bg-[#232F3E] text-white">
        <div class="max-w-[1200px] mx-auto px-6 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Get to Know Us</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">About {{ config('app.name', 'MyShop') }}</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Careers</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Make Money with Us</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Sell products</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Become an Affiliate</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Payment</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Business Card</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Shop with Points</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Let Us Help You</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('orders.index') }}" class="text-[13px] text-gray-300 hover:underline hover:text-white">Your Orders</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Returns & Replacements</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline hover:text-white">Help</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-[#131921] text-center py-4 border-t border-white/10">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-1.5 mb-3">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-orange-400"></i>
            <span class="text-white font-bold text-base">{{ config('app.name', 'MyShop') }}</span>
        </a>
        <p class="text-[11px] text-gray-500">© {{ date('Y') }} {{ config('app.name', 'MyShop') }}. All rights reserved.</p>
    </div>
</footer>
