<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-6">Edit U21 Player</h1>
    <form action="{{ route('playersu21.update', $playerU21->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" name="first_name" id="first_name" value="{{ $playerU21->first_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="{{ $playerU21->last_name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="photo" class="block text-sm font-medium text-gray-700">Photo</label>
            @if($playerU21->photo)
                <img src="{{ asset('storage/' . $playerU21->photo) }}" alt="Current Photo" class="w-20 h-20 object-cover mb-4">
            @endif
            <input type="file" name="photo" id="photo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <div class="mb-4">
            <label for="birthdate" class="block text-sm font-medium text-gray-700">Birthdate</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ $playerU21->birthdate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
            <input type="text" name="position" id="position" value="{{ $playerU21->position }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="number" class="block text-sm font-medium text-gray-700">Number</label>
            <input type="number" name="number" id="number" value="{{ $playerU21->number }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
            <input type="text" name="nationality" id="nationality" value="{{ $playerU21->nationality }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="mb-4">
            <label for="height" class="block text-sm font-medium text-gray-700">Height (in cm)</label>
            <input type="number" name="height" id="height" value="{{ $playerU21->height }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Update Player</button>
        </div>
    </form>
</div>