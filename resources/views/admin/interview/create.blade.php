@extends('admin.layouts.app')
@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    
    <div class="mx-4 my-8 rounded">
        <div class="flex gap-4 w-full mt-4">
        <div
            class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
        >
            <div class="flex gap-6 items-center">
            <div
                class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
            >
                <i class="fa-solid fa-people-group text-[#28c76f]"></i>
            </div>
            <div class="flex flex-col gap-2">
                <span class="font-bold text-[#28c76f]">Candidate Name</span>
                <span>{{$submission->consultant->full_name}}</span>
            </div>
            </div>
        </div>
        <div
            class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
        >
            <div class="flex gap-6 items-center">
            <div
                class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
            >
                <i class="fa-solid fa-briefcase text-[#28c76f]"></i>
            </div>
            <div class="flex flex-col gap-2">
                <span class="font-bold text-[#00bad1]">Job</span>
                <span> {{$submission->careerOpportunity->title}} ({{$submission->careerOpportunity->id}}) </span>
            </div>
            </div>
        </div>
        <div
            class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
        >
            <div class="flex gap-6 items-center">
            <div
                class="bg-[#FFF0E1] w-12 h-12 rounded-full flex items-center justify-center"
            >
                <i class="fa-regular fa-calendar text-[#ff9f43]"></i>
            </div>
            <div class="flex flex-col gap-2">
                <span class="font-bold text-[#ff9f43]">Job Duration</span>
                <span>{{$submission->careerOpportunity->date_range}}</span>
            </div>
            </div>
        </div>
        </div>
    </div>
    <div>
        <!-- Form -->
        <div x-data="createOffer">
            <form id="generalformwizard" @submit.prevent="validateForm">
                <div class="bg-white mx-4 my-8 rounded p-8">
                <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                >
                    Interview Details
                </h2>
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                    <label for="eventName" class="block mb-2"
                        >Event Name <span class="text-red-500">*</span></label
                    >
                    <input
                        type="text"
                        class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                        placeholder="Interview Schedule for {{$submission->consultant->first_name}} {{$submission->consultant->last_name}}"
                        disabled
                        id="eventName"
                    />
                    </div>
                    <div class="flex-1">
                    <label class="block mb-2"
                        >Interview Duration
                        <span class="text-red-500">*</span></label
                    >
                    <select
                        class="w-full select2-single custom-style"
                        id="interviewDuration"
                    >
                        <option value="">Select</option>
                        @foreach (checksetting(13) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p
                        class="text-red-500 text-sm mt-1"
                        x-text="interviewDurationError"
                    ></p>
                    </div>
                </div>
                <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                    <label class="block mb-2"
                        >Time Zone <span class="text-red-500">*</span></label
                    >
                    <select
                        class="w-full select2-single custom-style"
                        id="timeZone"
                    >
                        <option value="">Select</option>
                        @foreach (checksetting(14) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p
                        class="text-red-500 text-sm mt-1"
                        x-text="timeZoneError"
                    ></p>
                    </div>
                    <div class="flex-1">
                    <label class="block mb-2"
                        >Interview Type <span class="text-red-500">*</span></label
                    >
                    <select
                        class="w-full select2-single custom-style"
                        id="remote"
                    >
                        <option value="">Select</option>
                        @foreach (checksetting(15) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-sm mt-1"></p>
                    </div>
                </div>
                </div>
                <div class="bg-white mx-4 my-8 rounded p-8">
                <div class="mb-4 flex items-center gap-4">
                    <i
                    class="fa-regular fa-calendar"
                    :style="{'color': 'var(--primary-color)'}"
                    ></i>
                    <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                    >
                    Recommended Interviev Date
                    </h2>
                </div>
                <!--  Dates and Other Information -->
                <div class="my-4">
                    <div
                    class="p-[30px] rounded border"
                    :style="{'border-color': 'var(--primary-color)'}"
                    >
                    <!-- Date Picker-->
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label for="startDate" class="block mb-2"
                            >Recommended Date:
                            <span class="text-red-500">*</span></label
                        >
                        <input
                            id="startDate"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select start date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                        <div class="flex-1">
                        <label for="endDate" class="block mb-2"
                            >Other Dates - 1 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="endDate"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select end date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label for="startDate" class="block mb-2"
                            >Other Dates - 2 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="startDate"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select start date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                        <div class="flex-1">
                        <label for="endDate" class="block mb-2"
                            >Other Dates - 3 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="endDate"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select end date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                <div class="bg-white mx-4 my-8 rounded p-8">
                <div class="mb-4 flex items-center gap-4">
                    <i
                    class="fa-regular fa-calendar"
                    :style="{'color': 'var(--primary-color)'}"
                    ></i>
                    <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                    >
                    Other Details
                    </h2>
                </div>
                <!--  Dates and Other Information -->
                <div class="my-4">
                    <div
                    class="p-[30px] rounded border"
                    :style="{'border-color': 'var(--primary-color)'}"
                    >
                    <!-- Date Picker-->
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label class="block mb-2"
                            >Where <span class="text-red-500">*</span></label
                        >
                        @php $location = \App\Models\Location::byStatus();@endphp
                        <select x-model="location"
                            class="w-full select2-single border rounded mt-1 p-3"
                            id="location" x-ref="location"
                        >
                            <option value="">Select</option>
                            @foreach ($location as $key => $value)
                            <option value="{{ $value->id }}"
                            {{ $value->id == $submission->careerOpportunity->location_id ? 'selected' : '' }}>
                            {{ locationName($value->id) }}</option>
                            @endforeach
                        </select>
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                        <div class="flex-1">
                        <div class="mt-1.5">
                            <label
                            for="jobAttachment"
                            class="block text-sm font-medium text-gray-700 mb-2"
                            >Job Attachment</label
                            >
                            <input
                            type="file"
                            id="jobAttachment"
                            name="jobAttachment"
                            class="block w-full px-2 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            />
                        </div>
                        </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label class="block mb-2">Interviev Instructions</label>
                        <textarea
                            class="w-full border rounded"
                            rows="5"
                            :style="{'border-color': 'var(--primary-color)'}"
                        ></textarea>
                        </div>
                    </div>
                    </div>
                </div>
                </div>

                <div class="bg-white mx-4 my-8 rounded p-8">
                <div class="mb-4 flex items-center gap-4">
                    <i
                    class="fa-regular fa-calendar"
                    :style="{'color': 'var(--primary-color)'}"
                    ></i>
                    <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                    >
                    Other Details
                    </h2>
                </div>
                <div class="flex-1">
                    <label for="jobCode" class="block mb-2"
                    >Select interviev member (including yourself) to access
                    their calenders on the next page
                    <span class="text-red-500">*</span></label
                    >

                    <select
                    class="w-full select2-single custom-style"
                    multiple="multiple"
                    >
                        @php $clients = \App\Models\Client::byStatus();@endphp
                        @foreach ($clients as $key => $value)
                            <option value="{{ $value->id }}">
                            {{ $value->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <div class="mx-4 my-4">
                <button
                    type="submit"
                    class="px-4 py-2 text-white capitalize rounded"
                    :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
                >
                    create interview
                </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("alpine:init", () => {
    Alpine.data("createOffer", () => ({
        interviewDuration: "",
        timeZone: "",
        interviewDurationError: "",
        timeZoneError: "",
        //   endDate: "",
        //   approvingManager: "",
        //   location: "",
        //   remote: "",
        //   billRate: "",
        //   payRate: "",
        //   startDateError: "",
        //   endDateError: "",
        //   approvingManagerError: "",
        //   locationError: "",
        //   remoteError: "",
        //   billRateError: "",
        //   payRateError: "",

        init() {
        this.initDatePickers();
        this.initSelect2();
        },

        initDatePickers() {
        const startPicker = flatpickr("#startDate", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
            this.startDate = dateStr;
            this.startDateError = "";
            if (
                this.endDate &&
                new Date(dateStr) > new Date(this.endDate)
            ) {
                this.endDate = "";
                this.endDateError = "End date must be after start date";
                endPicker.clear();
            }
            endPicker.set("minDate", dateStr);
            },
        });

        const endPicker = flatpickr("#endDate", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
            this.endDate = dateStr;
            this.endDateError = "";
            },
        });
        },

        initSelect2() {
        this.$nextTick(() => {
            $("#interviewDuration")
            .select2({
                width: "100%",
            })
            .on("select2:select", (e) => {
                this.interviewDuration = e.params.data.id;
                this.interviewDurationError = "";
            })
            .on("select2:unselect", () => {
                this.interviewDuration = "";
            });

            $("#timeZone")
            .select2({
                width: "100%",
            })
            .on("select2:select", (e) => {
                this.timeZone = e.params.data.id;
                this.timeZoneError = "";
            })
            .on("select2:unselect", () => {
                this.timeZone = "";
            });

            $("#remote")
            .select2({
                width: "100%",
            })
            .on("select2:select", (e) => {
                this.remote = e.params.data.id;
                this.remoteError = "";
            })
            .on("select2:unselect", () => {
                this.remote = "";
            });
        });
        },

        validateForm(e) {
        let isValid = true;

        if (!this.interviewDuration) {
            this.interviewDurationError = "Please select interview duration.";
            isValid = false;
        }

        if (!this.timeZone) {
            this.timeZoneError = "Please select time zone.";
            isValid = false;
        }

        if (!this.endDate) {
            this.endDateError = "Please select an end date.";
            isValid = false;
        } else if (new Date(this.startDate) > new Date(this.endDate)) {
            this.endDateError = "End date must be after start date";
            isValid = false;
        }

        if (!this.approvingManager) {
            this.approvingManagerError =
            "Please select timesheet approving manager.";
            isValid = false;
        }

        if (!this.location) {
            this.locationError = "Please select a location.";
            isValid = false;
        }

        if (!this.remote) {
            this.remoteError = "Please select remote option.";
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            console.log("Form is valid. Submitting...");
            // Add your form submission logic here
        }
        },
    }));
    });
</script>
@endsection