<div class="rounded border p-[30px]">
    <div class="flex w-full m-[20px]">
        <p class="font-bold text-blue-400 flex items-center ">
            <i class="fa fa-user-circle mr-2"></i>
            {{$contract->consultant->full_name}}
        </p>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400 mb-2"></i>
                <div class="mt-2">
                    <span class="block text-2xl font-bold text-blue-400">$36,280.00</span>
                    <span class="text-gray-800">Approved Budget</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-3xl text-blue-400"></i>
                <div class="mt-2">
                    <span class="block text-xl font-bold text-blue-400">$0.00</span>
                    <span class="text-gray-800">Spend Pending Approval Amount</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400"></i>
                <div class="mt-2">
                    <span id="remaining_spend_amount" class="block text-2xl font-bold text-blue-400">$33,886.00</span>
                    <span class="text-gray-800">Remaining Spend Amount</span>
                </div>
            </div>
        </div>

        <div class="w-full sm:w-1/2 md:w-1/4 px-2 mb-4">
            <div class="data-card bg-white shadow-lg rounded-lg p-6 overflow-hidden transition-transform transform hover:scale-105">
                <i class="fas fa-dollar-sign text-4xl text-blue-400"></i>
                <div class="mt-2">
                    <span id="spend_to_date" class="block text-2xl font-bold text-blue-400">$2,394.00</span>
                    <span class="text-gray-800">Spend to Date</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- {{$contract->consultant->full_name}} -->
<!-- <pre>{{ json_encode($contract, JSON_PRETTY_PRINT) }}</pre>  -->