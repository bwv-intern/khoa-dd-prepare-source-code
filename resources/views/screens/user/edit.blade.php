<x-app-layout title="User edit" :breadcrumbs="[
    'Top' => route('ADMIN_TOP'),
    'Users' => route('ADMIN_USER_SEARCH'),
    'User edit' => route('ADMIN_USER_EDIT_SUBMIT', ['id' => $user['id']]),
]">
    <form id="admin-user-add-form" method="POST"
        action="{{ route('ADMIN_USER_EDIT_SUBMIT', ['id' => $user['id']]) }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2><b>User edit</b></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Email" name="email"
                            :value="old('email') ?? ($user['email'] ?? null)" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Full name" name="name"
                            :value="old('name') ?? ($user['name'] ?? null)" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Password" name="password"
                            type="password" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Re-password"
                            name="repeat_password" type="password" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <label class="col-2">User Flag</label>
                            <div class="col-10">
                                <x-forms.select-group label="User flag"
                                    name="user_flg" :options="getList('user.user_flg')"
                                    :initialSelect="old('user_flg') ??
                                        ($user['user_flg'] ?? 1)" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        @php
                            try {
                                $dob =
                                    old('date_of_birth') ??
                                    $user['date_of_birth'];
                                $dob = formatDate($dob);
                            } catch (\Throwable $t) {
                                // only thrown exception here should be about unable to parse weird dt
                                // do nothing since it most likely is errorneous and is only here after
                                // being redirected back while keeping old inputs
                            }
                        @endphp
                        <x-forms.text-group label="Date of birth"
                            name="date_of_birth" :value="$dob"
                            placeholder="yyyy/mm/dd" icon="fas fa-calendar" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Phone" name="phone"
                            :value="old('phone') ?? ($user['phone'] ?? null)" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.textarea label="Address" name="address"
                            :value="old('address') ??
                                ($user['address'] ?? null)" />
                    </div>
                </div>
                <div class="text-center">
                    <x-button.base label="Update" />
                </div>
            </div>
        </div>
    </form>
    @push('styles')
        {{-- @vite(['resources/css/screens/admin/user/add.css']) --}}
    @endpush
    @push('scripts')
        @vite(['resources/js/screens/admin/user/add.js'], 'build')
    @endpush
</x-app-layout>
