<div x-show="activePage === 'tab3'">
    <div class="bg-white mx-4 my-8 rounded p-8">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{translate('Contract BU')}}</h2>
            </div>
            <table class="min-w-full divide-y divide-gray-200" id="listing">
                <thead class="bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Business Unit')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Business Percentage')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Created Date')}}</th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                        {{translate('Contract Start Date')}}
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{translate('Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($contract->careerOpportunity->careerOpportunitiesbu as $record)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->buName->name     }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->percentage }}%
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $record->created_at }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $contract->start_date }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $contract->getContractStatus($contract->status)  }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div x-show="activePage === 'tab5'">
    <div class="w-2/4 bg-white h-[1024px] mx-4 rounded p-8">
        @if ($contract->submission->resume)
            @php $fileExtension = pathinfo($contract->submission->resume, PATHINFO_EXTENSION);
            @endphp
            <object
                data="{{ asset('storage/submission_resume/' . $contract->submission->resume) }}"
                type="application/{{$fileExtension}}"
                width="100%"
                height="100%"
            >
                <p>
                    {{translate('Alternative text - include a link')}}
                    <a href="{{ asset('storage/submission_resume/' . $contract->submission->resume) }}">to the PDF!</a>
                </p>
            </object>
        @else
            <p>{{translate('No resume available.')}}</p>
        @endif
    </div>
</div>
