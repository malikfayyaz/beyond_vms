<div class="mx-4 rounded p-8">
    <div class="mx-auto relative" x-data="timeline">
        <!-- Main Timeline -->
        <div class="relative">
            <!-- Vertical line -->
            <div class="absolute left-4 top-4 bottom-4 w-[1px] bg-gray-300"></div>

            <!-- Activity logs passed into Alpine.js -->
            <template x-for="(item, index) in items" :key="index">
                <div class="flex gap-4 mb-8 relative group">
                    <!-- Timeline dot -->
                    <div class="flex-shrink-0 relative z-10">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="text-sm text-gray-500 mb-1" x-text="formatDate(item.date)"></div>
                        <div class="flex items-center gap-2 mb-1">
                        <div class="font-medium text-gray-900">Job History</div>                            <div class="font-medium text-gray-900" x-text="item.title"></div>
                            <button @click="openSidebar(item)"
                                class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded hidden group-hover:inline-block">
                                View
                            </button>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span x-text="item.description"></span>
                            <span>by</span>
                            <span x-text="item.author"></span>
                            <span x-text="item.causer_id"></span>.
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('timeline', () => ({
            items: @json($activityLogs),
            formatDate(date) {
                return new Date;
            },
            openSidebar(item) {
                // Handle opening the sidebar or viewing details
                alert('View details for ' + item.title);
            }
        }))
    })
</script>