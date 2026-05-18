<x-app-layout>
    <div style="max-width: 480px; margin: 40px auto;">
        
        <x-card title="Sign In // EcoTrace">
            
            @if($errors->any())
                <div style="background-color: #ffdad6; color: #ba1a1a; padding: 12px; border: 2px solid #1a1c1b; border-radius: 6px; margin-bottom: 20px; font-family: var(--font-mono); font-size: 13px;">
                    <strong style="text-transform: uppercase;">Validation Failure:</strong>
                    <ul style="margin-top: 5px; padding-left: 15px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <x-form-input 
                    name="email" 
                    label="Email Address" 
                    type="email" 
                    placeholder="name@company.com" 
                    required 
                />

                <x-form-input 
                    name="password" 
                    label="Password" 
                    type="password" 
                    placeholder="••••••••" 
                    required 
                />

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 13px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: var(--primary);"> Remember Me
                    </label>
                    <a href="#" style="text-decoration: underline; color: var(--near-black); font-weight: 600;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-action" style="width: 100%; justify-content: center; background-color: var(--primary); color: var(--pure-white);">
                    Access Account
                </button>
            </form>

            <div style="margin-top: 24px; text-align: center; font-size: 14px; border-top: 2px solid var(--near-black); padding-top: 20px;">
                Don't have an account? 
                <a href="{{ route('register') }}" style="font-weight: bold; text-decoration: underline;">Create one now</a>
            </div>

        </x-card>

    </div>
</x-app-layout>
