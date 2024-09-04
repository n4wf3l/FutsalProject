<div class="container">
    <h1 class="text-3xl font-bold mb-6">Edit Sponsor</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sponsors.update', $sponsor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('name', $sponsor->name) }}" required>
        </div>

        <div class="mb-4">
            <label for="logo" class="block text-sm font-medium text-gray-700">Logo:</label>
            @if($sponsor->logo)
                <img src="{{ asset('storage/' . $sponsor->logo) }}" alt="{{ $sponsor->name }}" class="h-12 w-12 mb-4">
            @endif
            <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="website" class="block text-sm font-medium text-gray-700">Website:</label>
            <input type="url" name="website" id="website" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('website', $sponsor->website) }}">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Sponsor</button>
            <a href="{{ route('sponsors.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>