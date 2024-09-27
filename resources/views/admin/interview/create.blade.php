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
        <div x-data="createInterview">
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
                        value="Interview Schedule for {{$submission->consultant->full_name}}"
                        disabled
                        id="eventName"
                        x-model="eventName"
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
                        x-model="interviewDuration"
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
                        x-model="timeZone"
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
                    <label for="remote" class="block mb-2"
                        >Interview Type <span class="text-red-500">*</span></label
                    >
                    <select
                        class="w-full select2-single custom-style"
                        id="remote"
                        x-model="remote"
                    >
                        <option value="">Select</option>
                        @foreach (checksetting(15) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-sm mt-1" x-text="remoteError"></p>
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
                    Recommended Interview Date
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
                            x-model="startDate"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select recommended date"
                        />
                        <p class="text-red-500 text-sm mt-1" x-text="startDateError"></p>
                        </div>
                        <div class="flex-1">
                        <label for="otherDate1" class="block mb-2"
                            >Other Dates - 1 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="otherDate1"
                            x-model="otherDate1"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select other date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label for="otherDate2" class="block mb-2"
                            >Other Dates - 2 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="otherDate2"
                            x-model="otherDate2"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select other date"
                        />
                        <p class="text-red-500 text-sm mt-1"></p>
                        </div>
                        <div class="flex-1">
                        <label for="otherDate3" class="block mb-2"
                            >Other Dates - 3 <span class="text-red-500"></span
                        ></label>
                        <input
                            id="otherDate3"
                            x-model="otherDate3"
                            class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                            type="text"
                            placeholder="Select other date"
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
                        <p class="text-red-500 text-sm mt-1" x-text="locationError"></p>
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
                            @change="handleFileUpload"
                            />
                        </div>
                        </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                        <div class="flex-1">
                        <label class="block mb-2">Interview Instructions</label>
                        <textarea
                            class="w-full border rounded"
                            rows="5"
                            x-model="interviewInstructions"
                            id="interviewInstructions"
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
                    <label for="members" class="block mb-2"
                    >Select interviev member (including yourself) to access
                    their calenders on the next page
                    <span class="text-red-500">*</span></label
                    >

                    <select
                    class="w-full select2-single custom-style border"
                    multiple="multiple" id="members" x-model="members"
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
    Alpine.data("createInterview", () => ({
        eventName:'Interview Schedule for {{$submission->consultant->full_name}}',
        interviewDuration: "",
        timeZone: "",
        remote: "",
        interviewInstructions: '', // Initialize as an empty string
        members: [],
        location: "",
        startDate: "",
        otherDate1: '',
        otherDate2: '',
        otherDate3: '',

        interviewDurationError: "",
        timeZoneError: "",
        startDateError: "",
        locationError: "",
        remoteError: "",
       

        init() {
        this.initDatePickers();
        this.initSelect2();
        },

        initDatePickers() {
        const startPicker = flatpickr("#startDate", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
                this.startDate = dateStr; // Set the selected date to startDate
                this.startDateError = ""; // Clear the error message
            },
        });

        const otherDatePicker1 = flatpickr("#otherDate1", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
                this.otherDate1 = dateStr; // Set the selected date 
            },
        });

        const otherDatePicker2 = flatpickr("#otherDate2", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
                this.otherDate2 = dateStr; // Set the selected date 
            },
        });

        const otherDatePicker3 = flatpickr("#otherDate3", {
            dateFormat: "Y/m/d",
            onChange: (selectedDates, dateStr) => {
                this.otherDate3 = dateStr; // Set the selected date
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

        if (!this.startDate) {
        this.startDateError = "Please select a recomended date.";
            isValid = false;
        } else {
            this.startDateError = ""; // Clear the error if start date is valid
        }
        
        if (!this.location) {
            this.locationError = "Please select a location.";
            isValid = false;
        }

        if (!this.remote) {
            this.remoteError = "Please select interview type.";
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            console.log()
        } else {
            
            const formElement = document.querySelector("#generalformwizard");
            const formData = new FormData(formElement);

            // Append additional fields manually if needed
            formData.append("eventName", this.eventName);
            formData.append("interviewDuration", this.interviewDuration);
            formData.append("timeZone", this.timeZone);
            formData.append("remote", this.remote);
            formData.append("interviewInstructions", this.interviewInstructions);
            formData.append("members", this.members);
            formData.append("location", this.location);
            formData.append("startDate", this.startDate);
            formData.append("otherDate1", this.otherDate1);
            formData.append("otherDate2", this.otherDate2);
            formData.append("otherDate3", this.otherDate3);
            formData.append("submissionid", {{$submission->id}});
           
            url = '{{ route("admin.interview.store") }}';
            
            console.log(formData);
            // Use your custom ajaxCall function
            ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
        }
        },
    }));
    });
</script>
@endsection