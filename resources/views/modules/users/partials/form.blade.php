@php($selectedRole = old('role', isset($user) ? $user->roles->first()?->name : 'staff'))

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $user->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email ?? '')" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1" :value="old('phone', $user->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="designation" value="Designation" />
        <x-text-input id="designation" name="designation" type="text" class="mt-1" :value="old('designation', $user->designation ?? '')" />
        <x-input-error :messages="$errors->get('designation')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="role" value="Role" />
        <select id="role" name="role" class="form-select mt-1">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected($selectedRole === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    <div class="flex items-end">
        <label class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
            <input type="checkbox" name="is_active" value="1" class="form-checkbox" @checked(old('is_active', $user->is_active ?? true))>
            Active account
        </label>
    </div>

    <div>
        <x-input-label for="password" value="{{ isset($user) ? 'New Password' : 'Password' }}" />
        <x-text-input id="password" name="password" type="password" class="mt-1" :required="! isset($user)" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="password_confirmation" value="Confirm Password" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1" :required="! isset($user)" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary" type="submit">{{ $submitLabel }}</button>
</div>
