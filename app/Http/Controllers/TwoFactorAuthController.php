<?php

namespace App\Http\Controllers;

use App\Models\TwoFactorAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorAuthController extends Controller
{
    /**
     * Show the two-factor authentication setup page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        
        if ($user->hasTwoFactorEnabled()) {
            return view('auth.two-factor.show', [
                'enabled' => true,
                'recoveryCodes' => $user->twoFactorAuth->getRecoveryCodesArray(),
            ]);
        }
        
        // Generate new secret key if not already set up
        $google2fa = new Google2FA();
        $secretKey = $google2fa->generateSecretKey();
        
        // Store the secret key temporarily
        $user->enableTwoFactorAuth($secretKey);
        
        // Generate QR code
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secretKey
        );
        
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);
        
        return view('auth.two-factor.show', [
            'enabled' => false,
            'qrCode' => $qrCodeSvg,
            'secretKey' => $secretKey,
        ]);
    }
    
    /**
     * Enable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        
        // Verify the code
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey(
            $user->twoFactorAuth->secret_key,
            $request->code
        );
        
        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided two-factor authentication code was invalid.'],
            ]);
        }
        
        // Confirm and generate recovery codes
        $recoveryCodes = $user->confirmTwoFactorAuth();
        
        return redirect()->route('profile.two-factor.show')
            ->with('status', 'Two-factor authentication has been enabled.')
            ->with('recoveryCodes', $recoveryCodes);
    }
    
    /**
     * Disable two-factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }
        
        $user->disableTwoFactorAuth();
        
        return redirect()->route('profile.two-factor.show')
            ->with('status', 'Two-factor authentication has been disabled.');
    }
    
    /**
     * Generate new recovery codes for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasTwoFactorEnabled()) {
            return redirect()->route('profile.two-factor.show')
                ->with('error', 'Two-factor authentication is not enabled.');
        }
        
        $recoveryCodes = $user->twoFactorAuth->generateRecoveryCodes();
        
        return redirect()->route('profile.two-factor.show')
            ->with('status', 'Recovery codes have been regenerated.')
            ->with('recoveryCodes', $recoveryCodes);
    }
    
    /**
     * Show the two-factor authentication challenge page.
     *
     * @return \Illuminate\View\View
     */
    public function showChallenge()
    {
        return view('auth.two-factor.challenge');
    }
    
    /**
     * Verify the two-factor authentication code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Check if it's a recovery code
        $recoveryCodes = $user->twoFactorAuth->getRecoveryCodesArray();
        
        if (in_array($request->code, $recoveryCodes)) {
            // Remove the used recovery code
            $recoveryCodes = array_filter($recoveryCodes, function ($code) use ($request) {
                return $code !== $request->code;
            });
            
            $user->twoFactorAuth->setRecoveryCodesArray($recoveryCodes);
            $user->twoFactorAuth->save();
            
            session(['auth.two_factor.verified' => true]);
            
            return redirect()->intended(route('dashboard'));
        }
        
        // Verify the code
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey(
            $user->twoFactorAuth->secret_key,
            $request->code
        );
        
        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => ['The provided two-factor authentication code was invalid.'],
            ]);
        }
        
        session(['auth.two_factor.verified' => true]);
        
        return redirect()->intended(route('dashboard'));
    }
}
