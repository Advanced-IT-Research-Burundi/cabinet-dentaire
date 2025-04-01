@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Dentist Details</h1>
        <div class="space-x-2">
            <a href="{{ route('dentists.edit', $dentist) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                Edit
            </a>
            <a href="{{ route('dentists.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Name</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $dentist->user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $dentist->user->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Specialty</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $dentist->specialty ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">License Number</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $dentist->license_number ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $dentist->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $dentist->available ? 'Available' : 'Unavailable' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Additional Information</h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Biography</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $dentist->biography ?? 'No biography available' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Calendar Color</h3>
                            <div class="mt-1 flex items-center">
                                <div class="w-6 h-6 rounded border" style="background-color: {{ $dentist->calendar_color }}"></div>
                                <span class="ml-2 text-sm text-gray-900">{{ $dentist->calendar_color }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-6">
                <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" 
                        onclick="return confirm('Are you sure you want to delete this dentist?')">
                        Delete Dentist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
