<div class="flex w-full gap-4">
    <!-- Left Column -->
    <div
        class="w-1/3 p-[30px] rounded border"
        :style="{'border-color': 'var(--primary-color)'}"
    >
        <h3 class="flex items-center gap-2 mb-4 bg-">
            <i
                class="fa-solid fa-inbox"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}"
            >{{translate('Contract Info')}}</span
            >
        </h3>
        <div class="flex flex-col">
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Contract ID:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->id }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Contract Status:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->getContractStatus($contract->status) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Contract Start Date:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->start_date}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Contract End Date:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->end_date}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Vendor Name:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->vendor->full_name}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Job Title(ID):')}}</h4>
                </div>
                <div class="w-2/4" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
                    <p class="font-light">
                        <a class="text-blue-400 font-semibold cursor-pointer"
                            onclick="openJobDetailsModal({{ $contract->careerOpportunity->id }})"
                            >{{$contract->careerOpportunity->title}} ({{$contract->careerOpportunity->id}})</a
                        >

                    </p>
                    <x-job-details />
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Job Code:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->careerOpportunity->job_code}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Worker Type:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->careerOpportunity->workerType->title}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Hiring Manager:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->HiringManager->full_name}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Labour Type:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{getSettingTitleById($contract->careerOpportunity->labour_type)}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Country Tax:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->country_tax}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Timesheet Approving Manager:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->HiringManager->full_name}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Work Location:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">
                        {{ locationName($contract->location_id)}}
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Total Budget:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{$contract->total_estimated_cost}}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Division:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ getGenericTitleById($contract->careerOpportunity->division_id) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Region/Zone:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ getGenericTitleById($contract->careerOpportunity->region_zone_id) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Branch:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ getGenericTitleById($contract->careerOpportunity->branch_id) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('GL Account:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ getGenericTitleById($contract->careerOpportunity->gl_code_id) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Travel Required:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->careerOpportunity->travel_required ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Business Reason:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ getSettingTitleById($contract->careerOpportunity->hire_reason_id) }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Client Billable:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->careerOpportunity->client_billable ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Expenses Allowed?')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->careerOpportunity->expenses_allowed ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Remote Candidate:')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->careerOpportunity->remote_option ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                    <h4 class="font-medium">{{translate('Number of Opening(s):')}}</h4>
                </div>
                <div class="w-2/4">
                    <p class="font-light">{{ $contract->careerOpportunity->num_openings ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Middle Column -->
    <div
        class="w-1/3 p-[30px] rounded border"
        :style="{'border-color': 'var(--primary-color)'}"
    >
        <h3 class="flex items-center gap-2 mb-4">
            <i
                class="fa-regular fa-money-bill-1"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}"
            >{{translate('Contract Rates')}}</span
            >
        </h3>
        <!-- Cards -->
        <div>
            <div class="flex gap-4 w-full">
                <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                >
                    <div class="flex flex-col gap-2 items-center">
                        <div
                            class="bg-[#ddf6e8] w-8 h-8 rounded-full flex items-center justify-center"
                        >
                            <i class="fa-solid fa-dollar-sign text-[#28c76f]"></i>
                        </div>
                        <div class="text-center">
        <span
            class="font-bold text-sm font-normal text-[#28c76f]"
        >{{translate('Bill Rate For Vendor')}}</span
        >
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <span>${{ $contract->contractRates->vendor_bill_rate ?? 'N/A' }}</span>
                    </div>
                </div>
                <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                >
                    <div class="flex flex-col gap-2 items-center">
                        <div
                            class="bg-[#D6F4F8] w-8 h-8 rounded-full flex items-center justify-center"
                        >
                            <i class="fa-solid fa-dollar-sign text-[#00bad1]"></i>
                        </div>
                        <div class="text-center">
        <span
            class="font-bold text-sm font-normal text-[#00bad1]"
        >{{translate('Bill Rate For Client')}}</span
        >
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <span>${{ $contract->contractRates->client_bill_rate ?? 'N/A' }}</span>
                    </div>
                </div>
                <div
                    class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full px-2 py-4"
                >
                    <div class="flex flex-col gap-2 items-center">
                        <div
                            class="bg-[#FFF0E1] w-8 h-8 rounded-full flex items-center justify-center"
                        >
                            <i class="fa-solid fa-dollar-sign text-[#ff9f43]"></i>
                        </div>
                        <div class="text-center">
        <span
            class="font-bold text-sm font-normal text-[#ff9f43]"
        >{{translate('All Resources Cost')}}</span
        >
                        </div>
                    </div>
                    <div class="mt-2 text-center">
                        <span>${{ $contract->careerOpportunity->all_resources_total_cost }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div
                class="flex py-4 px-2 rounded rounded-b-none"
                :style="{'background-color': 'var(--primary-color)'}"
            >
                <div class="w-3/5">
                    <span class="text-white">{{translate('Business Unit')}}</span>
                </div>
                <div class="w-2/5 text-center">
                    <p class="font-light">%</p>
                </div>
            </div>
            @foreach($contract->careerOpportunity->careerOpportunitiesBu as $bu)
                <div
                    class="flex justify-between gap-2 py-4 px-2 border-x border-b"
                >
                    <div class="w-3/5 flex-wrap">
                        <span><p class="font-light">{{ $bu->buName->name ?? 'N/A' }}</p></span>
                    </div>
                    <div class="w-2/5 text-center">
                        <span>{{ $bu->percentage ?? 'N/A' }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Bill Rate (For Vendor):')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->contractRates->vendor_bill_rate ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Bill Rate (For Client):')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->contractRates->client_bill_rate ?? 'N/A' }}</p>
            </div>
        </div>
        <!-- Rates -->
        <h3 class="flex items-center gap-2 my-4">
            <i
                class="fa-regular fa-clock"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}">{{translate('Other Information')}}</span>
        </h3>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Location Tax(%):')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{$contract->workOrder->location_tax}}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Currency:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ getGenericTitleById($contract->careerOpportunity->currency_id) }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Source Type:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ getSettingTitleById($contract->workOrder->sourcing_type)  }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-y">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Timesheet Type:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ getSettingTitleById($contract->type_of_timesheet) }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-y">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Expense Allowed:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->careerOpportunity->expenses_allowed }}</p>
            </div>
        </div>
        <!-- Job Publish Info -->
        <h3 class="flex items-center gap-2 my-4">
            <i
                class="fa-solid fa-upload"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}"
            >{{translate('Onboarding Info')}}</span
            >
        </h3>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Onboarding Start Date:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->workOrder->onboard_change_start_date ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Onboarding End Date:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->workOrder->onboard_changed_end_date ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Onboarded status:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->workOrder->on_board_status ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <!-- Right Column -->
    <div
        class="w-1/3 p-[30px] rounded border"
        :style="{'border-color': 'var(--primary-color)'}"
    >

        <h3 class="flex items-center gap-2 mb-4">
            <i
                class="fa-regular fa-money-bill-1"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}"
            >{{translate('Contractor Information')}}</span
            >
        </h3>
        <!-- Cards -->
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Unique ID:')}}</h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->consultant->unique_id ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Contractor Portal ID:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->consultant->candidate_id ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Contractor Name:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->consultant->full_name ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Contractor Login ID:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->consultant->user->email ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Contractor Phone Number:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->consultant->phone ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Vendor Name:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->vendor->full_name ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Vendor Email:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ $contract->vendor->user->email ?? 'N/A' }}</p>
            </div>
        </div>
        <!-- SCREENING DOC -->
        <h3 class="flex items-center gap-2 my-4">
            <i
                class="fa-regular fa-clock"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}">
                {{translate('Onboarding Document Background Screening')}}
            </span>
        </h3>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Code of Conduct:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ optional($contract->workorderBackground)->code_of_conduct == 1 ? 'Yes' : 'No' ?? 'N/A' }}
                </p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Data Privacy:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ optional($contract->workorderBackground)->data_privacy == 1 ? 'Yes' : 'No' ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Non-Disclosure:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ optional($contract->workorderBackground)->non_disclosure  == 1 ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between py-4 border-t">
            <div class="w-2/4">
                <h4 class="font-medium">{{translate('Criminal Background:')}}<h4>
            </div>
            <div class="w-2/4">
                <p class="font-light">{{ optional($contract->workorderBackground)->criminal_background   == 1 ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        <h3 class="flex items-center gap-2 my-4">
            <i
                class="fa-regular fa-clock"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}">
                <a href="{{ route('client.workorder.show', $contract->workorder_id) }}" class="text-blue-500">
                    {{translate('WorkOrder')}}
                            ({{ $contract->careerOpportunity->id }})
                        </a>
            </span>
        </h3>
        <!-- 0FFER Info -->
        <h3 class="flex items-center gap-2 my-4">
            <i
                class="fa-solid fa-upload"
                :style="{'color': 'var(--primary-color)'}"
            ></i
            ><span :style="{'color': 'var(--primary-color)'}"
            ><a href="{{ route('client.offer.show', $contract->offer_id) }}" class="text-blue-500">
                   {{translate('Offer')}}
                    ({{ $contract->offer_id }})
                </a></span>
        </h3>
    </div>
</div>

<script>
    function openJobDetailsModal(jobId) {
    fetch(`/job-details/${jobId}`)
        .then(response => response.json())
        .then(data => {
            const event = new CustomEvent('job-details-updated', {
                    detail: data,
                    bubbles: true,
                    composed: true
                });
                // console.log(event.detail.data);

                document.dispatchEvent(event);
        })
        .catch(error => console.error('Error:', error));
    }
</script>
