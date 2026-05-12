<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                    'confirmed'
                ],
            ], [
                'current_password.required' => 'Current password is required',
                'current_password.current_password' => 'The current password is incorrect',
                'password.required' => 'New password is required',
                'password.confirmed' => 'Password confirmation does not match',
                'password.min' => 'Password must be at least 8 characters long',
                'password.letters' => 'Password must contain at least one letter',
                'password.mixedCase' => 'Password must contain both uppercase and lowercase letters',
                'password.numbers' => 'Password must contain at least one number',
                'password.symbols' => 'Password must contain at least one special character',
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('success', 'Your password has been updated successfully!');
        } catch (ValidationException $e) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors($e->errors(), 'updatePassword');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['updatePassword' => ['Failed to update password. Please try again.']]);
        }
    }
}
