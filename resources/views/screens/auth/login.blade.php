<x-login-layout title="Login">
    <div class="login-box">
        <div class="login-logo mb-5">
            <h2>Login Page</h2>
        </div>
        @if ($errors->any())
                <x-alert :messages="$errors->all()" type="danger" />
            @endif
        <div class="card">
            <div class="card-body login-card-body">
                <form method="post" action="{{ route('auth.handleLogin') }}" id="login-form">
                    @csrf
                    <div class="form-group">
                        <x-forms.label for="email" label="Email" :isRequired="true" />
                        <x-forms.text
                            name="email"
                            label="Email"
                            idSelector="email"
                            placeholder="Enter email"
                            :value="old('email')"
                        />
                    </div>
                    <div class="form-group">
                        <x-forms.label for="password" label="Password" :isRequired="true" />
                        <x-forms.text
                            type="password"
                            name="password"
                            label="Password"
                            idSelector="password"
                            placeholder="Enter password"
                            :value="old('password')"
                        />
                    </div>
                    <div class="row text-center">
                        <div class="col-12">
                            <x-button.base type="submit" label="Login" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        @vite(['resources/js/screens/auth/login.js'], 'build')
    @endpush
</x-login-layout>
