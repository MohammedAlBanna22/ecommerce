<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Set an image as the main (primary) image
     */
    public function setMain(Media $media)
    {
        try {
            // Remove primary flag from all other images for this mediable
            Media::where('id', '!=', $media->id)
                ->where('mediable_id', $media->mediable_id)
                ->where('mediable_type', $media->mediable_type)
                ->update(['is_primary' => false]);

            // Set this one as primary
            $media->update(['is_primary' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Main image updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update image sort order
     */
    public function order(Request $request)
    {
        try {
            foreach ($request->images as $image) {
                Media::where('id', $image['id'])
                    ->update([
                        'sort_order' => $image['position']
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image order updated'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an image
     */
    public function destroy(Media $media)
    {
        try {
            // Delete file from storage if exists
            if ($media->path && Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }

            // If this was the primary image, set the next one as primary
            if ($media->is_primary) {
                $next = Media::where('id', '!=', $media->id)
                    ->where('mediable_id', $media->mediable_id)
                    ->where('mediable_type', $media->mediable_type)
                    ->orderBy('sort_order')
                    ->first();

                if ($next) {
                    $next->update(['is_primary' => true]);
                }
            }

            // Delete the media record
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}