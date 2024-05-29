<x-app-layout title="User List" :breadcrumbs="['Top' => route('ADMIN_TOP'), 'Users' => route('ADMIN_USER_SEARCH')]">
    <form id="usr01-form" method="POST"
        action="{{ route('ADMIN_USER_SEARCH_SUBMIT') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2><b>User search</b></h2>
                    </div>
                    <div>
                        <x-button.link to="#" label="Add user" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Email" name="email"
                            :value="$paramSession['email'] ??
                                (old('email') ?? null)" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Full name" name="name"
                            :value="$paramSession['name'] ??
                                (old('name') ?? null)" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <label class="col-2">User Flag</label>
                            <div class="col-10">
                                <x-forms.checkbox-group :label="null"
                                    name="user_flg" :options="getList('user.user_flg')"
                                    :valueChecked="$paramSession['user_flg'] ??
                                        (old('user_flg') ?? null)" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group label="Date of birth"
                            name="date_of_birth" :value="$paramSession['date_of_birth'] ??
                                (old('date_of_birth') ?? null)"
                            icon="fas fa-calendar" />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group label="Phone" name="phone"
                            :value="$paramSession['phone'] ??
                                (old('phone') ?? null)" />
                    </div>
                </div>
                <div class="text-center">
                    <x-button.base label="Search" />
                    <x-button.clear screen="usr01" label="Clear" />
                    <x-button.link label="Export" type="button"
                        to="{{ route('ADMIN_USER_EXPORT') }}" />
                    <x-button.base label="Import" type="button" />
                </div>
            </div>
        </div>
    </form>
    <div class="card">
        @if ($users->isNotEmpty())
            <div class="card-body">
                <div class="table-responsive table-hover">
                    <table id="user-table"
                        class="table table-bordered table-hover dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th class="text-center op"></th>
                                <th class="text-center email">Email</th>
                                <th class="text-center name">Full name</th>
                                <th class="text-center user-flag">User flag</th>
                                <th class="text-center dob">Date of birth</th>
                                <th class="text-center phone">Phone</th>
                                <th class="text-center address">Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center text-wrap">
                                        <x-button.link to="#"
                                            label="Edit" />
                                        <x-button.base
                                            class="btn btn-danger delete-user-btn"
                                            label="Delete"
                                            data-user-id="{{ $user->id }}"
                                            data-link="{{ route('ADMIN_USER_DELETE', ['id' => $user->id]) }}" />
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->email }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->name }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ ucfirst(getValueToText($user->user_flg ?? null, 'user.user_flg')) }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ formatDate($user->date_of_birth, 'd/m/Y') }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->phone }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->address }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $users->links('common.pagination') }}
        @else
            <div class="text-center m-3">{{ getMessage('I005') }}</div>
        @endif
    </div>
    @push('styles')
        @vite(['resources/css/screens/admin/user/search.css'])
    @endpush
    @push('scripts')
        @vite(['resources/js/screens/admin/user/search.js'], 'build')
    @endpush
</x-app-layout>
