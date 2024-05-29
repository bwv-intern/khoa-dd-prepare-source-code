<x-app-layout title="User add" :breadcrumbs="[
    'Top' => route('ADMIN_TOP'),
    'Users' => route('ADMIN_USER_SEARCH'),
    'User add' => route('ADMIN_USER_ADD'),
]">
    <form id="admin-user-add-form" method="POST"
        action="{{ route('ADMIN_USER_ADD_SUBMIT') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2><b>User add</b></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Email" name="email"
                            :value="old('email') ?? null" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Full name" name="name"
                            :value="old('name') ?? null" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Password" name="password"
                            type="password" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Re-password"
                            name="repeat_password" type="password"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <label class="col-2">User Flag</label>
                            <div class="col-10">
                                <x-forms.select-group label="User flag"
                                    name="user_flg" :options="getList('user.user_flg')"
                                    :initialSelect="(old('user_flg') ?? 1)" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Date of birth"
                            name="date_of_birth" :value="old('date_of_birth')" placeholder="yyyy/mm/dd"
                            icon="fas fa-calendar" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Phone" name="phone"
                            :value="old('phone') ?? null" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.textarea label="Address" name="address"
                            :value="old('address') ?? null" />
                    </div>
                </div>
                <div class="text-center">
                    <x-button.base label="Add" />
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
