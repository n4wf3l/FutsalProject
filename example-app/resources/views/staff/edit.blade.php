<div class="container mx-auto mt-8 max-w-lg">
    <h1 class="text-3xl font-bold mb-6 text-center">Edit Staff Member</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('staff.update', $staff->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
            <input type="text" name="first_name" id="first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('first_name', $staff->first_name) }}" required>
        </div>
        <div class="mb-4">
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
            <input type="text" name="last_name" id="last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('last_name', $staff->last_name) }}" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-gray-700">Position:</label>
            <input type="text" name="position" id="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('position', $staff->position) }}" required>
        </div>
        <button type="submit" class="bg-green-500 text-white font-bold py-2 px-4 rounded">Update Staff Member</button>
    </form>
</div>