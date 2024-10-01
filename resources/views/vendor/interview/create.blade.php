@extends('vendor.layouts.app')
@section('content')
<!-- Sidebar -->
@include('vendor.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('vendor.layouts.partials.header')
    
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
                        x-model="formData.eventName"
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
                        x-model="formData.interviewDuration"
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
                        x-model="formData.timeZone"
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
                        x-model="formData.remote"
                    >
                        <option value="">Select</option>
                        @foreach (checksetting(15) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-sm mt-1" x-text="remoteError"></p>
                    </div>
                </div>
                <div x-show="formData.remote" class="mt-4">
                        <label for="interview_detail" class="block mb-2">
                            <span x-text="getInterviewLabel()"></span> <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            class="w-full border rounded p-3"
                            rows="5"
                            id="interview_detail"
                            x-model="formData.interview_detail"
                            placeholder="Enter interview detail"
                        />
                        </textarea>
                        <p class="text-red-500 text-sm mt-1" x-text="interview_detailError"></p>
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
                            x-model="formData.startDate"
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
                            x-model="formData.otherDate1"
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
                            x-model="formData.otherDate2"
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
                            x-model="formData.otherDate3"
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
                        <select x-model="formData.location"
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
                            x-model="formData.interviewInstructions"
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
                    multiple="multiple" id="members" x-model="formData.members"
                    >
                        @php $clients = \App\Models\Client::byStatus();@endphp
                        @foreach ($clients as $key => $value)
                            <option value="{{ $value->id }}">
                            {{ $value->full_name }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500 text-sm mt-1" x-text="membersError"></p>
                </div>
                </div>
                <div class="mx-4 my-4">
                <button
                    type="submit"
                    class="px-4 py-2 text-white capitalize rounded"
                    :style="{'background-color': 'var(--primary-color)', 'background-color:hover': 'var(--primary-hover)'}"
                >
                    <span x-text="editMode ? 'Update Interview' : 'Create Interview'"></span>
                </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("alpine:init", () => {
    Alpine.data("createInterview", () => ({
        editMode: @json(isset($editMode) ? $editMode : false), 
        formData: {
            eventName: 'Interview Schedule for {{$submission->consultant->full_name}}',
            interviewDuration: "{{ old('interviewDuration', $interview->interview_duration ?? '') }}",
            startDate: "{{ old('startDate', $interview->recommended_date ?? '') }}",
            timeZone: "{{ old('timeZone', $interview->time_zone ?? '') }}",
            remote: "{{ old('remote', $interview->interview_type ?? '') }}",
            interviewInstructions: '{{ old('interviewInstructions', $interview->interview_instructions ?? '') }}', // Initialize as an empty string
            members: @json(old('members', $interview->interview_members ?? [])),
            location: "{{ old('location', $interview->location_id ?? '') }}",
            otherDate1: '{{ old('otherDate1', $interview->other_date_1 ?? '') }}',
            otherDate2: '{{ old('otherDate2', $interview->other_date_2 ?? '') }}',
            otherDate3: '{{ old('otherDate3', $interview->other_date_3 ?? '') }}',
            interview_detail: '{{ old('interview_detail', $interview->interview_detail ?? '') }}',
        },

        getInterviewLabel() {
            switch (this.formData.remote) {
                case '58':
                    return 'In Person Interview Detail';
                case '59':
                    return 'Phone Interview Detail';
                case '60':
                    return 'Virtual Interview Detail';
                default:
                    return 'Interview Detail';
            }
        },

        interviewDurationError: "",
        timeZoneError: "",
        startDateError: "",
        locationError: "",
        remoteError: "",
        membersError: "",
        interview_detailError:"",
       

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
                this.formData.interviewDuration = e.params.data.id;
                this.interviewDurationError = "";
            })
            .on("select2:unselect", () => {
                this.formData.interviewDuration = "";
            });

            $("#timeZone")
            .select2({
                width: "100%",
            })
            .on("select2:select", (e) => {
                this.formData.timeZone = e.params.data.id;
                this.timeZoneError = "";
            })
            .on("select2:unselect", () => {
                this.formData.timeZone = "";
            });

            $("#remote")
            .select2({
                width: "100%",
            })
            .on("select2:select", (e) => {
                this.formData.remote = e.params.data.id;
                this.remoteError = "";
            })
            .on("select2:unselect", () => {
                this.formData.remote = "";
            });
        });
        },

        validateForm(e) {
        let isValid = true;

        if (!this.formData.interviewDuration) {
            this.interviewDurationError = "Please select interview duration.";
            isValid = false;
        }

        if (!this.formData.timeZone) {
            this.timeZoneError = "Please select time zone.";
            isValid = false;
        }

        if (!this.formData.startDate) {
        this.startDateError = "Please select a recomended date.";
            isValid = false;
        } else {
            this.startDateError = ""; // Clear the error if start date is valid
        }
        
        if (!this.formData.location) {
            this.locationError = "Please select a location.";
            isValid = false;
        }

        if (!this.formData.remote) {
            this.remoteError = "Please select interview type.";
            isValid = false;
        }

        if (!this.formData.members || this.formData.members.length === 0) {
            this.membersError = "Please select member(s).";
            isValid = false;
        }else {
            this.membersError = "";
        }

        if (!this.formData.interview_detail) {
            this.interview_detailError = "Please type details here.";
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            console.log()
        } else {
            
            const formElement = document.querySelector("#generalformwizard");
            const formData = new FormData(formElement);

            // Append additional fields manually if needed
            formData.append("eventName", this.formData.eventName);
            formData.append("interviewDuration", this.formData.interviewDuration);
            formData.append("timeZone", this.formData.timeZone);
            formData.append("remote", this.formData.remote);
            formData.append("interviewInstructions", this.formData.interviewInstructions);
            formData.append("members", this.formData.members);
            formData.append("location", this.formData.location);
            formData.append("startDate", this.formData.startDate);
            formData.append("otherDate1", this.formData.otherDate1);
            formData.append("otherDate2", this.formData.otherDate2);
            formData.append("otherDate3", this.formData.otherDate3);
            formData.append("interview_detail", this.formData.interview_detail);
            formData.append("submissionid", {{$submission->id}});

            let url = '';
            @if(isset($editMode) && $editMode)
                url = '{{ route("vendor.interview.update", $editIndex) }}';
                formData.append('_method', 'PUT'); // Required for PUT requests in forms
            @else
                url = '{{ route("vendor.interview.store") }}';
            @endif

            // Use your custom ajaxCall function
            ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
        }
        },
        onSuccess(response) {
            if (response.success) {
                // Display a success message
                // alert(response.message); // Replace with a custom notification if needed
                if (response.redirect_url) {
                    window.location.href = response.redirect_url; // Redirect the user
                }
            } else {
                console.log("Unexpected response:", response);
            }
        }
    }));
    });
</script>
@endsection