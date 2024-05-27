<x-app-layout title="User List">
    <form id="usr01-form" method="POST" action="{{ route('user.handleUsr01') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group
                            label="ID"
                            name="user_id"
                            :value="$paramSession['user_id'] ?? null"
                        />
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <label class="col-2">User Flag</label>
                            <div class="col-10">
                                <x-forms.checkbox-group
                                    :label="null"
                                    name="user_flg"
                                    :options="getList('user.user_flg')"
                                    :valueChecked="$paramSession['user_flg'] ?? null"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <x-forms.text-group
                            label="User Name"
                            name="name"
                            :value="$paramSession['name'] ?? null"
                        />
                    </div>
                    <div class="col-6">
                        <x-forms.text-group
                            label="Email"
                            name="email"
                            :value="$paramSession['email'] ?? null"
                        />
                    </div>
                </div>
                <div class="text-center">
                    <x-button.base label="Search" />
                    <x-button.clear screen="usr01" />
                </div>
            </div>
        </div>
    </form>
    <div class="card">
        @if ($users->isNotEmpty())
            <div class="card-body">
                <div class="table-responsive table-hover">
                    <table id="user-table" class="table table-bordered table-hover dataTable dtr-inline">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">User Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">User Flag</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="text-center text-wrap">
                                        {{ $user->id }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->name }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ $user->email }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ getValueToText($user->user_flg ?? null, 'user.user_flg') }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ formatDate($user->created_at, 'Y/m/d H:i:s') }}
                                    </td>
                                    <td class="text-center text-wrap">
                                        {{ formatDate($user->updated_at, 'Y/m/d H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $users->links('common.pagination') }}
        @else
            <div class="text-center m-3">{{ getMessage('ICL015') }}</div>
        @endif
    </div>
</x-app-layout>
