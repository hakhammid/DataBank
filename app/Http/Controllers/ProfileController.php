<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     try {
    //         $user = $request->user();
    //         $user->fill($request->validated());

    //         if ($user->isDirty('email')) {
    //             $user->email_verified_at = null;
    //         }

    //         // Handle profile photo upload
    //         if ($request->hasFile('profile_photo')) {
    //             $request->validate([
    //                 'profile_photo' => ['image', 'max:2048', 'mimes:jpeg,png,jpg,gif'], // 2MB max
    //             ]);

    //             // Delete old photo if exists
    //             if ($user->profile_picture) {
    //                 $oldImagePath = public_path('images/' . $user->profile_picture);
    //                 if (file_exists($oldImagePath)) {
    //                     unlink($oldImagePath);
    //                 }
    //             }

    //             // Generate unique filename
    //             $image     = $request->file('profile_photo');
    //             $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

    //             // Move image to public/images directory
    //             $image->move(public_path('images'), $imageName);

    //             // Update user's profile picture
    //             $user->profile_picture = $imageName;
    //         }

    //         $user->save();

    //         return back()->with('success', 'Profile updated successfully');

    //     } catch (\Exception $e) {
    //         return back()
    //             ->with('error', 'Failed to update profile. Please try again.')
    //             ->withInput();
    //     }
    // }


    /**
 * Update the user's profile information.
 */
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    try {
        $user = $request->user();
        
        // Validate the request (excluding email uniqueness check for now)
        $validated = $request->validated();

        // Handle email uniqueness separately to ignore current user
        if ($request->has('email') && $request->email !== $user->email) {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id]
            ]);
            $validated['email_verified_at'] = null;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => ['image', 'max:2048', 'mimes:jpeg,png,jpg,gif']
            ]);

            // Delete old photo if exists
            if ($user->profile_picture) {
                $oldImagePath = public_path('images/' . $user->profile_picture);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Generate unique filename
            $image = $request->file('profile_photo');
            $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

            // Move image to public/images directory
            $image->move(public_path('images'), $imageName);

            // Update user's profile picture
            $validated['profile_picture'] = $imageName;
        }

        // Update user data
        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Profile updated successfully');

    } catch (\Exception $e) {
        Log::error('Profile update failed: ' . $e->getMessage());
        return back()
            ->with('error', 'Failed to update profile. Please try again.')
            ->withInput();
    }
}

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        DB::beginTransaction();
        try {
            // Debugging: Log user ID and relationships
            Log::info('Starting account deletion for user ID: ' . $user->id);
            Log::info('User modules count: ' . $user->modules()->count());

            // 1. Delete related modules
            if (method_exists($user, 'modules')) {
                $modulesDeleted = $user->modules()->delete();
                Log::info("Deleted {$modulesDeleted} modules");
            }

            // 2. Delete sessions
            $sessionsDeleted = DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
            Log::info("Deleted {$sessionsDeleted} sessions");

            // 3. Delete password reset tokens if any
            $passwordResetsDeleted = DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->delete();
            Log::info("Deleted {$passwordResetsDeleted} password reset tokens");

            // 4. Finally delete the user
            $userDeleted = $user->delete();
            Log::info("User deletion result: " . ($userDeleted ? 'success' : 'failed'));

            // 5. Logout and clear session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            DB::commit();

            Log::info('Account deleted successfully for user ID: ' . $user->id);
            return Redirect::to('/')->with('success', 'Account deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Account deletion failed for user ID: ' . $user->id);
            Log::error('Error message: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()
                ->with('error', 'Failed to delete account: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            if ($user->profile_picture) {
                // Delete the photo from public/images directory
                $imagePath = public_path('images/' . $user->profile_picture);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Update user record
                $user->profile_picture = null;
                $user->save();

                return back()->with('success', 'Profile photo deleted successfully');
            }

            return back()->with('error', 'No profile photo found to delete');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete profile photo. Please try again.');
        }
    }
}
