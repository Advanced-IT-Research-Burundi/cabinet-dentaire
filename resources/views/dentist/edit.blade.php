@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Edit Dentist</h1>
        <a href="{{ route('dentists.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('dentists.update', $dentist) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (old('user_id', $dentist->user_id) == $user->id) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="specialty" class="block text-sm font-medium text-gray-700">Specialty</label>
                <input type="text" name="specialty" id="specialty" value="{{ old('specialty', $dentist->specialty) }}" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('specialty')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="license_number" class="block text-sm font-medium text-gray-700">License Number</label>
                <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $dentist->license_number) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('license_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="biography" class="block text-sm font-medium text-gray-700">Biography</label>
                <textarea name="biography" id="biography" rows="4" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('biography', $dentist->biography) }}</textarea>
                @error('biography')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="calendar_color" class="block text-sm font-medium text-gray-700">Calendar Color</label>
                <input type="color" name="calendar_color" id="calendar_color" value="{{ old('calendar_color', $dentist->calendar_color) }}"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('calendar_color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="available" class="block text-sm font-medium text-gray-700">Availability Status</label>
                <select name="available" id="available" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ old('available', $dentist->available) ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ old('available', $dentist->available) ? '' : 'selected' }}>Unavailable</option>
                </select>
                @error('available')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Update Dentist
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
