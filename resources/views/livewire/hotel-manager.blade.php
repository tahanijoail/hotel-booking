<div class="p-4 max-w-3xl mx-auto">
    <h2 class="text-xl font-bold mb-4">إدارة الفنادق</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}">
        <input wire:model="name" type="text" placeholder="اسم الفندق" class="border p-2 w-full mb-2">
        <input wire:model="location" type="text" placeholder="الموقع" class="border p-2 w-full mb-2">
        <textarea wire:model="description" placeholder="الوصف" class="border p-2 w-full mb-2"></textarea>
        <input wire:model="number_of_rooms" type="number" placeholder="عدد الغرف" class="border p-2 w-full mb-2">
        <textarea wire:model="contacts" placeholder='مثال: {"phone":"123456","email":"x@y.com"}' class="border p-2 w-full mb-2"></textarea>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
            {{ $isEditMode ? 'تحديث' : 'إضافة' }}
        </button>
    </form>

    <hr class="my-6">

    <table class="w-full border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">#</th>
                <th class="border px-2 py-1">الاسم</th>
                <th class="border px-2 py-1">الموقع</th>
                <th class="border px-2 py-1">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hotels as $hotel)
                <tr>
                    <td class="border px-2 py-1">{{ $hotel->id }}</td>
                    <td class="border px-2 py-1">{{ $hotel->name }}</td>
                    <td class="border px-2 py-1">{{ $hotel->location }}</td>
                    <td class="border px-2 py-1">
                        <button wire:click="edit({{ $hotel->id }})" class="text-blue-500">تعديل</button>
                        <button wire:click="delete({{ $hotel->id }})" class="text-red-500 ml-2">حذف</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
