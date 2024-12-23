<div class="mx-4 rounded p-8">
    <div class="mx-auto relative">
        <!-- Main Timeline -->
        <div class="relative">
            <!-- Vertical line -->
            <div class="absolute left-4 top-4 bottom-4 w-[1px] bg-gray-300"></div>
            @foreach($activityLogs as $log)
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
                        <div class="text-sm text-gray-500 mb-1">{{ formatDateTime($log['created_at'])}}</div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="font-medium text-gray-900">{{translate('Job History')}}</div>
                            <div class="font-medium text-gray-900">{{ $log['title'] }}</div>
                            <div x-data="{ isOpen: false }">
                                <a href="javascript:void(0)"
                                   @click="isOpen = true;"
                                   class="bg-blue-100 text-blue-800 text-xs px-2.5 py-0.5 rounded hidden group-hover:inline-block">
                                    <span class="text-blue-800 font-semibold text-xs">{{translate('View')}}</span>
                                </a>
                                <!-- Modal backdrop -->
                                <div
                                    x-show="isOpen"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    @click="isOpen = false"
                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                </div>
                                <!-- Modal content -->
                            <x-job-history :log="$log" />
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span>{{ translate($log['description']) }}</span>
                            <span>{{ translate('by')}}</span>
                            <span>{{ $log->createdBy->name }}</span>.
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
