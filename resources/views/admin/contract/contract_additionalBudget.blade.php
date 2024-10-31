<div>
                <div class="flex justify-between items-center p-4 bg-gray-800 text-white">
                    <div>
                        <h4 class="text-lg font-bold">{{ $contract->consultant->full_name }}</h4>
                        <!-- Career Opportunity Name (Smaller Text) -->
                        <div class="text-sm text-gray-300">{{ $contract->careerOpportunity->alternative_job_title }}</div>
                    </div>
                    <!-- Status -->
                    <div>Status: {{ $additionalBudget->status }}</div>
                </div>
            </div>
            @if($additionalBudget)
            <div class="p-4">
                            <table class="border w-full">
                                <thead>
                                <tr class="text-left border-b">
                                    <th class=" p-2">
                                    Additional Budget Request Details</th>
                                </tr>
                            </thead>
                                <tbody>
                                <tr>
                                    <td class="p-2">Request Amount  RT :</td>
                                    <td class="p-2 text-green-800 font-bold">${{ $additionalBudget->amount }}</td>
                                </tr>
                            </tbody>
                            </table>
                         </div>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <h5 class="text-md font-semibold">Additional Budget Request Details</h5>
                    <ul class="list-disc ml-4 mt-2 text-gray-600">
                        <li>Requested Amount: ${{ $additionalBudget->amount }}</li>
                        <li>Reason For Additional Budget: {{ getSettingTitleById($additionalBudget->additional_budget_reason) }}</li>
                        <li>Effective Date: {{ \Carbon\Carbon::parse($additionalBudget->effective_date)->format('m/d/Y') }}</li>
                        <li>Notes: {{ $additionalBudget->notes }}</li>
                    </ul>
                </div>
            @endif