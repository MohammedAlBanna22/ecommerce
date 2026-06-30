<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{

  public function index()
    {
        $addresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
        return view('address.index', compact('addresses'));
    }
    /**
     * Store a newly created address
     * ✅ ترجع Redirect للـ Blade Forms
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'details' => 'nullable|string|max:500',
            'is_default' => 'sometimes|boolean',
            'from_checkout' => 'sometimes|in:1',  // ✅ لمعرفة إذا كان من صفحة Checkout
        ]);

        try {
            // إذا كان عنوان افتراضي، إلغاء الباقي
            if ($validated['is_default'] ?? false) {
                auth()->user()->addresses()->update(['is_default' => false]);
            }

            // إنشاء العنوان
            $address = auth()->user()->addresses()->create([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'area' => $validated['area'],
                'street' => $validated['street'],
                'details' => $validated['details'] ?? null,
                'is_default' => $validated['is_default'] ?? false,
            ]);

            // ✅ رسالة نجاح
            $message = '✅ Address saved successfully!';

            // ✅ إذا كان من Checkout، ارجع لصفحة Checkout
            if ($request->has('from_checkout')) {
                return redirect()
                    ->route('checkout.index')
                    ->with('success', $message . ' Please select it below to continue.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            // وإلا ارجع للصفحة السابقة
            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error saving address: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'street'       => 'required|string|max:255',
            'city'         => 'required|string|max:255',
            'state'        => 'nullable|string|max:255',
            'postal_code'  => 'nullable|string|max:20',
            'country'      => 'required|string|max:255',
        ]);

        if ($request->has('is_default')) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully!'
        ]);
    }


    /**
     * Set address as default
     */
    public function setDefault(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);

        try {
            auth()->user()->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            return back()->with('success', '✅ Default address updated!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating default address');
        }
    }

    /**
     * Delete address
     */
    public function destroy(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);

        try {
            $address->delete();
            return back()->with('success', '🗑️ Address deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting address');
        }
    }
}
