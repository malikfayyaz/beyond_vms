@extends('admin.layouts.app')
@section('content')
<!-- Sidebar -->
@include('admin.layouts.partials.dashboard_side_bar')
<div class="ml-16">
    @include('admin.layouts.partials.header')
    
    <div>
        <div x-data="interviewSchedulerWizard()" class="w-full mx-auto p-4">
        <!-- Step 1 -->
        <div x-show="step === 1" class="bg-white shadow-md rounded-lg p-6">
            <!-- Cards -->
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
                    <span class="font-bold text-[#28c76f]"
                        >Candidate Name</span
                    >
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
                    <span>{{$submission->careerOpportunity->title}} ({{$submission->careerOpportunity->id}}) </span>
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
                    <span class="font-bold text-[#ff9f43]"
                        >Job Duration</span
                    >
                    <span>{{$submission->careerOpportunity->date_range}}</span>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <h2
            class="text-2xl font-bold mb-4"
            :style="{'color': 'var(--primary-color)'}"
            >
            Interview Details
            </h2>
            <form @submit.prevent="nextStep" id="generalformwizard">
            <div class="space-y-4">
                <div>
                <label for="eventName" class="block mb-2"
                    >Event Name <span class="text-red-500">*</span></label
                >
                <input
                    type="text"
                    id="eventName"
                    x-model="formData.eventName"
                    class="w-full px-3 py-2 border rounded-md"
                    disabled
                />
                </div>
                <div>
                <label for="interviewDuration" class="block mb-2"
                    >Interview Duration
                    <span class="text-red-500">*</span></label
                >
                <select
                    id="interviewDuration"
                    x-model="formData.interviewDuration"
                    class="w-full px-3 py-2 border rounded-md"
                    :class="{'border-red-500': errors.interviewDuration}"
                >
                    <option value="">Select</option>
                    @foreach (checksetting(13) as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <p
                    class="text-red-500 text-sm mt-1"
                    x-text="errors.interviewDuration"
                ></p>
                </div>
                <div>
                <label for="timeZone" class="block mb-2"
                    >Time Zone <span class="text-red-500">*</span></label
                >
                <select
                    id="timeZone"
                    x-model="formData.timeZone"
                    class="w-full px-3 py-2 border rounded-md"
                    :class="{'border-red-500': errors.timeZone}"
                >
                    <option value="">Select</option>
                    @foreach (checksetting(14) as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <p
                    class="text-red-500 text-sm mt-1"
                    x-text="errors.timeZone"
                ></p>
                </div>
                <div>
                <label for="interviewType" class="block mb-2"
                    >Interview Type <span class="text-red-500">*</span></label
                >
                <select
                    id="interviewType"
                    x-model="formData.interviewType"
                    class="w-full px-3 py-2 border rounded-md"
                    :class="{'border-red-500': errors.interviewType}"
                >
                    <option value="">Select</option>
                    @foreach (checksetting(15) as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <p
                    class="text-red-500 text-sm mt-1"
                    x-text="errors.interviewType"
                ></p>
                </div>

                <div x-show="formData.interviewType" class="mt-4">
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
                    <p class="text-red-500 text-sm mt-1" x-text="errors.interview_detail"></p>
                </div>
            </div>
            <div class="bg-white my-8 rounded p-8">
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
                <div class="my-4">
                <div
                    class="p-[30px] rounded border"
                    :style="{'border-color': 'var(--primary-color)'}"
                >
                    <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label for="recommendedDate" class="block mb-2"
                        >Recommended Date:
                        <span class="text-red-500">*</span></label
                        >
                        <input
                        id="recommendedDate"
                        x-model="formData.recommendedDate"
                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                        :class="{'border-red-500': errors.recommendedDate}"
                        type="text"
                        placeholder="Select recommended date"
                        />
                        <p
                        class="text-red-500 text-sm mt-1"
                        x-text="errors.recommendedDate"
                        ></p>
                    </div>
                    <div class="flex-1">
                        <label for="otherDate1" class="block mb-2"
                        >Other Dates - 1</label
                        >
                        <input
                        id="otherDate1"
                        x-model="formData.otherDate1"
                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                        type="text"
                        placeholder="Select other date"
                        />
                    </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label for="otherDate2" class="block mb-2"
                        >Other Dates - 2</label
                        >
                        <input
                        id="otherDate2"
                        x-model="formData.otherDate2"
                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                        type="text"
                        placeholder="Select other date"
                        />
                    </div>
                    <div class="flex-1">
                        <label for="otherDate3" class="block mb-2"
                        >Other Dates - 3</label
                        >
                        <input
                        id="otherDate3"
                        x-model="formData.otherDate3"
                        class="w-full h-12 px-4 text-gray-500 border rounded-md shadow-sm focus:outline-none pl-7"
                        type="text"
                        placeholder="Select other date"
                        />
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
                <div class="my-4">
                <div
                    class="p-[30px] rounded border"
                    :style="{'border-color': 'var(--primary-color)'}"
                >
                    <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block mb-2"
                        >Where <span class="text-red-500">*</span></label
                        >
                        <select
                        class="w-full select2-single custom-style"
                        id="where"
                        x-model="formData.where"
                        :class="{'border-red-500': errors.where}"
                        >
                        @php $location = \App\Models\Location::byStatus();@endphp
                        <option value="">Select</option>
                        @foreach ($location as $key => $value)
                        <option value="{{ $value->id }}"
                        {{ $value->id == $submission->careerOpportunity->location_id ? 'selected' : '' }}>
                        {{ locationName($value->id) }}</option>
                        @endforeach
                        </select>
                        <p
                        class="text-red-500 text-sm mt-1"
                        x-text="errors.where"
                        ></p>
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
                            @change="formData.jobAttachment = $event.target.files[0]"
                        />
                        </div>
                    </div>
                    </div>
                    <div class="flex space-x-4 mt-4">
                    <div class="flex-1">
                        <label class="block mb-2"
                        >Interview Instructions</label
                        >
                        <textarea
                        class="w-full border rounded"
                        rows="5"
                        :style="{'border-color': 'var(--primary-color)'}"
                        x-model="formData.interviewInstructions"
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
                <label for="interviewMembers" class="block mb-2">
                    Select interview members (including yourself) to access
                    their calendars on the next page
                    <span class="text-red-500">*</span>
                </label>
                <select
                    class="w-full select2-single custom-style"
                    multiple="multiple"
                    id="interviewMembers"
                    x-model="formData.interviewMembers"
                    :class="{'border-red-500': errors.interviewMembers}"
                >
                    @php $clients = \App\Models\Client::byStatus();@endphp
                    @foreach ($clients as $key => $value)
                        <option value="{{ $value->id }}"
                       >
                        {{ $value->full_name }}</option>
                    @endforeach
                </select>
                <p
                    class="text-red-500 text-sm mt-1"
                    x-text="errors.interviewMembers"
                ></p>
                </div>
            </div>
            <div class="mt-6">
                <button
                type="submit"
                class="px-4 py-2 text-white rounded"
                :style="{'background-color': 'var(--primary-color)'}"
                >
                Schedule Time
                </button>
            </div>
            </form>
        </div>

        <div x-show="step === 2" class="bg-white shadow-md rounded-lg p-6">
            <h2
            class="text-2xl font-bold mb-4"
            :style="{'color': 'var(--primary-color)'}"
            >
            Schedule Interview
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <template x-for="(date, index) in selectedDates" :key="index">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-blue-500 text-white p-4">
                    <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 mr-2"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>
                    <div>
                        <h3
                        class="text-xl font-semibold"
                        x-text="getDayName(date)"
                        ></h3>
                        <p x-text="formatDate(date)"></p>
                    </div>
                    </div>
                </div>
                <div class="p-4 bg-green-100">
                    <template
                    x-for="(slot, slotIndex) in timeSlots"
                    :key="slotIndex"
                    >
                    <div class="mb-2">
                        <label
                        class="flex items-center space-x-2 cursor-pointer"
                        >
                        <input
                            type="radio"
                            :name="'slot-' + date"
                            class="form-radio text-blue-500"
                            @change="selectTimeSlot(date, slot)"
                            :checked="formData.selectedTimeSlots[date] === slot"
                        />
                        <span x-text="slot"></span>
                        </label>
                    </div>
                    </template>
                </div>
                </div>
            </template>
            </div>
            <div
            x-show="errors.timeSlots"
            class="text-red-500 mt-2"
            x-text="errors.timeSlots"
            ></div>
            <div class="mt-6 flex justify-between">
            <button
                @click="prevStep"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded"
            >
                Previous
            </button>
            <button
                @click="submitForm"
                class="px-4 py-2 text-white rounded"
                :style="{'background-color': 'var(--primary-color)'}"
            >
                <span x-text="editMode ? 'Update' : 'Submit'"></span>
            </button>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
      function interviewSchedulerWizard() {
        return {
            editMode: @json(isset($editMode) ? $editMode : false),
            step: 1,
            formData: {
                eventName: "Interview Schedule for {{$submission->consultant->full_name}}",
                interviewDuration: "{{ old('interviewDuration', $interview->interview_duration ?? '') }}",
                timeZone: "{{ old('timeZone', $interview->time_zone ?? '') }}",
                interviewType: "{{ old('interviewType', $interview->interview_type ?? '') }}",
                interview_detail: "{{ old('interview_detail', $interview->interview_detail ?? '') }}",
                recommendedDate: "{{ old('recommendedDate', $interview->recommended_date ?? '') }}",
                otherDate1: '{{ old('otherDate1', $interview->other_date_1 ?? '') }}',
                otherDate2: '{{ old('otherDate2', $interview->other_date_2 ?? '') }}',
                otherDate3: '{{ old('otherDate3', $interview->other_date_3 ?? '') }}',
                where: "{{ old('where', $interview->location_id ?? '') }}",
                jobAttachment: "{{ old('jobAttachment', $interview->job_attachment ?? '') }}",
                interviewInstructions: "{{ old('interviewInstructions', $interview->interview_instructions ?? '') }}",
                interviewMembers:  @json(isset($interview) ? $interview->interviewMembers->pluck('member_id') : []),
                selectedTimeSlots: @json($selectedTimeSlots ?? (object)[]),
                selectedDate: null,
            },
          errors: {},
          timeSlots: [],

            getInterviewLabel() {
                switch (this.formData.interviewType) {
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

          init() {
            this.initDatePickers();
            this.initSelect2();
          },

          initDatePickers() {
            const dateFields = [
              "recommendedDate",
              "otherDate1",
              "otherDate2",
              "otherDate3",
            ];
            dateFields.forEach((field) => {
              flatpickr(`#${field}`, {
                dateFormat: "Y-m-d",
                altFormat: "m/d/Y",  
                altInput: true,
                onChange: (selectedDates, dateStr) => {
                  this.formData[field] = dateStr;
                  if (field === "recommendedDate") {
                    this.errors.recommendedDate = "";
                  }
                },
              });
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
                  this.errors.interviewDuration = "";
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
                  this.errors.timeZone = "";
                })
                .on("select2:unselect", () => {
                  this.formData.timeZone = "";
                });

              $("#interviewType")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this.formData.interviewType = e.params.data.id;
                  this.errors.interviewType = "";
                })
                .on("select2:unselect", () => {
                  this.formData.interviewType = "";
                });

              $("#where")
                .select2({
                  width: "100%",
                })
                .on("select2:select", (e) => {
                  this.formData.where = e.params.data.id;
                  this.errors.where = "";
                })
                .on("select2:unselect", () => {
                  this.formData.where = "";
                });

              $("#interviewMembers")
                .select2({
                  width: "100%",
                })
                .on("select2:select select2:unselect", (e) => {
                  this.formData.interviewMembers = $(e.target).val();
                  this.errors.interviewMembers = "";
                });
            });
          },

          get selectedDates() {
            return ["recommendedDate", "otherDate1", "otherDate2", "otherDate3"]
              .map((field) => this.formData[field])
              .filter(Boolean);
          },
          
          formatDate(date) {
            const dateObj = new Date(date);
            const month = String(dateObj.getMonth() + 1).padStart(2, "0"); // getMonth() is 0-indexed
            const day = String(dateObj.getDate()).padStart(2, "0");
            const year = dateObj.getFullYear();
            return `${month}/${day}/${year}`;
          },

          getDayName(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString("en-US", { weekday: "long" });
          },

          generateTimeSlots() {
            let duration; 
            if(this.formData.interviewDuration == 50){
                duration = 15;
            }
            if(this.formData.interviewDuration == 51){
                duration = 30;
            }
            if(this.formData.interviewDuration == 52){
                duration = 45;
            }
            if(this.formData.interviewDuration == 53){
                duration = 60;
            }

            if (!duration) return [];

            const slots = [];
            let currentTime = new Date(2000, 0, 1, 9, 0); // 9:00 AM
            const endTime = new Date(2000, 0, 1, 18, 0); // 6:00 PM

            while (currentTime < endTime) {
              const slotEnd = new Date(
                currentTime.getTime() + duration * 60000
              );
              if (slotEnd <= endTime) {
                slots.push(
                  `${currentTime.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                  })} - ${slotEnd.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                  })}`
                );
              }
              currentTime = slotEnd;
             
              
             
            }

            return slots;
          },

          nextStep() {
            if (this.validateStep1()) {
              this.timeSlots = this.generateTimeSlots();
              this.step = 2;
            }
          },

          prevStep() {
            this.step = 1;
          },

          validateStep1() {
            this.errors = {};
            let isValid = true;

            if (!this.formData.interviewDuration) {
              this.errors.interviewDuration = "Interview duration is required";
              isValid = false;
            }

            if (!this.formData.timeZone) {
              this.errors.timeZone = "Time zone is required";
              isValid = false;
            }

            if (!this.formData.interviewType) {
              this.errors.interviewType = "Interview type is required";
              isValid = false;
            }

            if (!this.formData.recommendedDate) {
              this.errors.recommendedDate = "Recommended date is required";
              isValid = false;
            }

            if (!this.formData.where) {
              this.errors.where = "Where field is required";
              isValid = false;
            }
            
            if (!this.formData.interview_detail) {
              this.errors.interview_detail = "Interview detail field is required";
              isValid = false;
            }

            if (
              !this.formData.interviewMembers ||
              this.formData.interviewMembers.length === 0
            ) {
              this.errors.interviewMembers =
                "At least one interview member is required";
              isValid = false;
            }

            return isValid;
          },

          selectTimeSlot(date, slot) {
            console.log(date);
            
            this.formData.selectedTimeSlots[date] = slot;
          },

          submitForm() {
            if (this.validateStep2()) {
                const formElement = document.querySelector("#generalformwizard");
                const formData = new FormData(formElement);

                // Append additional fields manually if needed
                formData.append("eventName", this.formData.eventName);
                formData.append("interviewDuration", this.formData.interviewDuration);
                formData.append("interviewInstructions", this.formData.interviewInstructions);
                formData.append("interviewType", this.formData.interviewType);
                formData.append("timeZone", this.formData.timeZone);
                formData.append("interviewMembers", this.formData.interviewMembers);
                formData.append("jobAttachment", this.formData.jobAttachment);
                formData.append("recommendedDate", this.formData.recommendedDate);
                formData.append("otherDate1", this.formData.otherDate1);
                formData.append("otherDate2", this.formData.otherDate2);
                formData.append("otherDate3", this.formData.otherDate3);
                formData.append("where", this.formData.where);
                formData.append("interview_detail", this.formData.interview_detail);
                formData.append("submissionid", {{$submission->id}});
                formData.append("selectedTimeSlots", JSON.stringify(this.formData.selectedTimeSlots));
                
                let url = '';
                let method = 'POST'; // Default method is POST

                @if(isset($editMode) && $editMode)
                    url = '{{ route("admin.interview.update", $editIndex) }}';
                    formData.append('_method', 'PUT'); // For PUT requests
                @else
                    url = '{{ route("admin.interview.store") }}';
                @endif

                ajaxCall(url, method,  [[onSuccess, ['response']]], formData);
            }
          },

          validateStep2() {
            this.errors = {};
            let isValid = true;

            const selectedDatesCount = this.selectedDates.length;
            const selectedTimeSlotsCount = Object.keys(
              this.formData.selectedTimeSlots
            ).length;

            if (selectedTimeSlotsCount < selectedDatesCount) {
              this.errors.timeSlots =
                "Please select a time slot for each date.";
              isValid = false;
            }

            return isValid;
          },
        };
      }
    </script>
@endsection