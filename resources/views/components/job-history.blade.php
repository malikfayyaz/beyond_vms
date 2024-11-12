<div
    x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="transform translate-x-full"
    x-transition:enter-end="transform translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="transform translate-x-0"
    x-transition:leave-end="transform translate-x-full"
    @click.outside="isOpen = false"
    class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24"
>
                            <!-- Top Bar -->
    <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
    <h2 class="text-lg font-semibold">Activity Log ID ({{$log->id}})</h2>
        <button
        @click="isOpen = false"
        class="text-gray-500 hover:text-gray-700">
        <i class="fas fa-times"></i>
        </button>
        </div>
                                    <!-- Extension Details -->
                                    <div class="bg-white border rounded-lg">
                                        <div class="border-b p-4">
                                            <h3 class="text-gray-700 font-medium">{{$log->careerOpportunity->title}}
                                            ({{$log->careerOpportunity->id}})
                                            </h3>
                                        </div>
                                    </div>

    @php
        $logProperties = json_decode($log->properties, true);
        $oldValues = $logProperties['old'] ?? [];
        $newValues = $logProperties['attributes'] ?? [];
    @endphp
    <table class="min-w-full border-collapse table-auto">
        <thead>
            <tr>
                <th class="border px-4 py-2">Field Name</th>
                <th class="border px-4 py-2">Old Value</th>
                <th class="border px-4 py-2">New Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($oldValues as $field => $oldValue)
                @if(array_key_exists($field, $newValues) && $newValues[$field] !== $oldValue)
                    <tr>
                        <td class="border px-4 py-2">{{ $field }}</td>
                        <td class="border px-4 py-2">
                            @if(is_array($oldValue) || is_object($oldValue))
                                <pre>{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                            @else
                                {{ $oldValue }}
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            @if(is_array($newValues[$field]) || is_object($newValues[$field]))
                                <pre>{{ json_encode($newValues[$field], JSON_PRETTY_PRINT) }}</pre>
                            @else
                                {{ $newValues[$field] }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
