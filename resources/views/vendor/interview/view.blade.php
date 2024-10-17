@extends('vendor.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('vendor.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('vendor.layouts.partials.header')

        <div>
          <div class="mx-4 rounded p-8">
            <div class="w-full flex justify-end items-center gap-4">

              <a href="{{ route('vendor.interview.index') }}">
                  <button
                      type="button"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                  >
                      Back to Interview
                  </button>
              </a>

            </div>
          </div>
          <div class="flex gap-8">
            <div class="w-2/4 bg-white mx-4 rounded p-8">
              <!-- Tabs -->
              <div
                x-data="{
  selectedId: null,
  init() {
      // Set the first available tab on the page on page load.
      this.$nextTick(() => this.select(this.$id('tab', 1)))
  },
  select(id) {
      this.selectedId = id
  },
  isSelected(id) {
      return this.selectedId === id
  },
  whichChild(el, parent) {
      return Array.from(parent.children).indexOf(el) + 1
  }
}"
                x-id="['tab']"
                class="w-full"
              >
                <!-- Tab List -->
                <ul
                  x-ref="tablist"
                  @keydown.right.prevent.stop="$focus.wrap().next()"
                  @keydown.home.prevent.stop="$focus.first()"
                  @keydown.page-up.prevent.stop="$focus.first()"
                  @keydown.left.prevent.stop="$focus.wrap().prev()"
                  @keydown.end.prevent.stop="$focus.last()"
                  @keydown.page-down.prevent.stop="$focus.last()"
                  role="tablist"
                  class="-mb-px flex items-center text-gray-500 bg-gray-100 py-1 px-1 rounded-t-lg gap-4"
                >
                  <!-- Tab -->
                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full  bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center gap-3 px-5 py-2.5 hover:rounded-lg hover:bg-white bg-transparent capitalize"
                      role="tab"
                    >
                      <i class="fa-regular fa-thumbs-up"></i>
                      <span class="capitalize">Summary</span>
                    </button>
                  </li>

                  <li>
                    <button
                      :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                      @click="select($el.id)"
                      @mousedown.prevent
                      @focus="select($el.id)"
                      type="button"
                      :tabindex="isSelected($el.id) ? 0 : -1"
                      :aria-selected="isSelected($el.id)"
                      :class="isSelected($el.id) ? 'w-full bg-white rounded-lg shadow' : 'border-transparent'"
                      class="flex justify-center items-center px-5 py-2.5 bg-transparent hover:rounded-lg hover:bg-white gap-3"
                      role="tab"
                    >
                      <i class="fa fa-calendar"></i>
                      <span class="capitalize"> Date & Time</span>
                    </button>
                  </li>
                </ul>

                <!-- Panels -->
                <div
                  role="tabpanels"
                  class="rounded-b-md border border-gray-200 bg-white"
                >
                  <!-- First Tab -->
                  <section
                  x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                  :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                  role="tabpanel"
                  class=""
              >
                  <div
                      class="bg-white shadow rounded-lg"
                  >
                      <div class="divide-y divide-gray-200">
                          <!-- Update each field accordingly -->
                        <div class="flex justify-between py-3 px-4">
                        <span class="text-gray-600">Status:</span>
                        <span
                            class="bg-green-500 text-white px-2 py-1 rounded-full text-sm"
                            >{{$interview->status}}</span>
                        </div>
                        @if($interview->status == 3)
                          <div class="flex justify-between py-3 px-4">
                            <p class="font-bold text-red-400">
                              <i class="fas fa-clipboard"></i> Cancel Reason
                            </p>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Reason for Cancel::</span>
                            <span class="font-semibold">{{$interview->reasonRejection->title}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Cancel Notes:</span>
                            <span class="font-semibold">{{$interview->notes}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Cancel By:</span>
                            <span class="font-semibold">{{$interview->rejectedBy->full_name}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Date & Time:</span>
                            <span class="font-semibold">{{$interview->formatted_interview_cancellation_date}}</span>
                          </div>
                        @endif
                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fas fa-user"></i> Candidate Info
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Candidate Name:</span>
                            <span class="font-semibold">{{$interview->consultant->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Vendor Name:</span>
                            <span
                            class="font-semibold"
                            >{{$interview->consultant->vendor->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Division</span>
                          <span
                            class="font-semibold"
                          >{{$interview->careerOpportunity->division->name}}</span>
                        </div>
                        @isset($interview->offer)
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Offer ID:</span
                          >
                          <span
                            class="font-semibold"
                          >{{$interview->offer->id}}</span>
                        </div>
                        @endisset
                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fas fa-comments"></i> Interview Details
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Type of Interview:</span
                          >
                          <span
                            class="font-semibold"
                          >{{$interview->interviewtype->title}}</span>
                        </div>
                        @if (!empty($interview->interview_instructions))
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-gray-600">Interview Instruction:</span>
                                <span class="font-semibold">{{ $interview->interview_instructions }}</span>
                            </div>
                        @endif

                        @if (!empty($interview->interview_detail))
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-gray-600">Interview Detail:</span>
                                <span class="font-semibold">{{ $interview->interview_detail }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Location:</span>
                          <span
                            class="font-semibold"
                          >{{ $interview->location->LocationDetails }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Job Profile:</span>
                          <span
                            class="font-semibold"
                          >{{ $interview->careerOpportunity->title }} ({{ $interview->careerOpportunity->id }})</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Timezone:</span>
                          <span
                            class="font-semibold"
                          >{{ $interview->timezone->title }}</span>
                        </div>

                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fa fa-calendar"></i> Interview Date
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Date of Interview:</span>
                          <span
                            class="font-semibold"
                          >
                          {{ $interview->interviewDates()->primaryData()->schedule_date }}</span>
                        </div>
                        @if (!empty($interview->job_attachment))
                          <div class="flex justify-between py-3 px-4">
                            <p class="font-bold text-blue-400">
                            <i class="fa fa-plus-square"></i> Additional Details
                            </p>
                          </div>

                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">Resume:</span>
                            <span
                              class="font-semibold"
                              >{{ $interview->job_attachment }} <a href="{{ asset('storage/interview_resume/' . $interview->job_attachment) }}" class="text-blue-500 hover:text-blue-700" download>
                                  <i class="fas fa-download"></i>
                              </a>
                            </span>
                          </div>
                        @endif
                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                          <i class="fa fa-user-plus"></i> Interviewer(s)
                          </p>
                        </div>
                        @if($interview->interviewMembers)
                        @foreach($interview->interviewMembers as $member)
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">Interviewer:</span>
                          <span
                            class="font-semibold"
                          >{{$member->member->full_name}}</span>
                        </div>
                        @endforeach
                        @endif
                      </div>
                    </div>
                  </section>
                  <!-- Second Tab-->
                  <section
                    x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                    :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                    role="tabpanel"
                    class=""
                  >
                    <div
                      class="bg-white shadow-md rounded-lg overflow-hidden w-full"
                    >
                      <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                          <span class="text-gray-600">Date & Time:</span>
                        </div>
                        @foreach($interview->interviewDates as $dates)
                        <div class="bg-blue-50 p-3 rounded-md">
                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">  {{$dates->formatted_schedule_date}} <br> <small>{{$dates->formatted_start_time}} - {{$dates->formatted_end_time}}</small></span>
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">{{$interview->status}}</span>
                            <span class="text-gray-600">{{ $interview->timezone->title }}</span>
                          </div>
                        </div>
                        @endforeach
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
            <div class=" w-2/4 bg-white mx-4 rounded p-8">
              <div x-data="{ activePage: 'tab1' }" class="mb-4">
                  <ul class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1">
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab1'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-2 mx-1"
                            :class="activePage === 'tab1' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab1' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-clock"></i>
                            <span class="capitalize">Time</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab1' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                    <li class="flex justify-center items-center">
                        <a
                            href="javascript:void(0)"
                            @click="activePage = 'tab2'"
                            class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-2 mx-1"
                            :class="activePage === 'tab2' ? 'bg-white rounded-lg shadow' : ''"
                            :style="{'color': activePage === 'tab2' ? 'var(--primary-color)' : ''}"
                        >
                            <i class="fa-regular fa-file-lines"></i>
                            <span class="capitalize">Resume</span>
                            <div class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                                :style="{'background-color': activePage === 'tab2' ? 'var(--primary-color)' : 'bg-gray-500'}"
                            >
                                <!-- <span class="text-[10px]"></span> -->
                            </div>
                        </a>
                    </li>
                  </ul>

                  <div class="mt-6">
                    <div x-show="activePage === 'tab1'">
                    <form x-data="acceptInterview()" @submit.prevent="submitData()" class="space-y-6">
                      @csrf
                      <div class="overflow-x-auto">
                              <table class="min-w-full bg-white border rounded-lg shadow-md text-sm">
                                  <thead class="bg-blue-400 text-white">
                                      <tr>
                                          <th class="py-3 px-4 text-left">Date <i class="fa fa-asterisk text-red-600"></i></th>
                                          <th class="py-3 px-4 text-left">Start Time</th>
                                          <th class="py-3 px-4 text-left">End Time</th>
                                          <th class="py-3 px-4 text-left" colspan="2">Timezone</th>
                                      </tr>
                                  </thead>
                                  <tbody x-data="{ status: {{ $interview->status }} }">
                                    @if($interview->status == 2)
                                      @foreach($interview->interviewDates as $interviewDate)
                                        @if($interviewDate->schedule_date == $interview->interview_acceptance_date)
                                        <tr class="bg-green-100">
                                            <td class="py-3 px-4">
                                              <div class="flex items-center" x-data="{ check: 'check' }">
                                                  <input type="radio" x-model="check" value="check" class="mr-2">
                                                  <p class="font-bold">{{ $interviewDate->formatted_schedule_date }} 
                                                    
                                                  </p>
                                              </div>
                                            </td>
                                            <td class="py-3 px-4 font-bold">{{ $interviewDate->formatted_start_time }}</td>
                                            <td class="py-3 px-4 font-bold">{{ $interviewDate->formatted_end_time }}</td>
                                            <td class="py-3 px-4 font-bold">{{$interview->timezone->title}}</td>
                                        </tr>
                                        @endif
                                      @endforeach
                                    @else
                                      @foreach($interview->interviewDates as $interviewDate)
                                      <tr class="">
                                          <td class="py-3 px-4">
                                              <div class="flex items-center">
                                                  <input type="radio" x-model="formData.interviewTiming" value="{{ $interviewDate->formatted_schedule_date }}" class="mr-2"
                                                  :disabled="status == 3" 
                                                  :class="{ 'opacity-50 pointer-events-none': status == 3 }"
                                                  >
                                                  <p class="font-bold">{{ $interviewDate->formatted_schedule_date }} 
                                                  @if($loop->first)
                                                      (Recommended)
                                                  @endif
                                                  </p>
                                              </div>
                                              
                                          </td>
                                          <td class="py-3 px-4 font-bold">{{ $interviewDate->formatted_start_time }}</td>
                                          <td class="py-3 px-4 font-bold">{{ $interviewDate->formatted_end_time }}</td>
                                          <td class="py-3 px-4 font-bold">{{$interview->timezone->title}}</td>
                                      </tr>
                                      @endforeach
                                    @endif
                                      <tr class="border-b">
                                        <td class="px-4">
                                          <p x-show="errors.interviewTiming" class="text-red-500 text-xs italic" x-text="errors.interviewTiming"></p>
                                        </td>
                                      </tr>
                                      <tr class="border-b">
                                          <td class="py-3 px-4">Candidate Phone Number:</td>
                                          <td class="py-3 px-4" colspan="3">
                                              <input type="tel" x-model="formData.can_phone" x-on:input="formatPhoneNumber($event.target)" name="can_phone" class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                                              placeholder="(XXXX) XXX-XXXX"
                                              :disabled="status == 3" 
                                              :class="{ 'opacity-50 pointer-events-none': status == 3 }"
                                              >
                                
                                          </td>
                                      </tr>
                                      <tr class="border-b">
                                          <td class="py-3 px-4">Ext No:</td>
                                          <td class="py-3 px-2">
                                              <input type="number" x-model="formData.phone_ext" name="phone_ext" max="99" min="0" class="w-full p-2 text-gray-500 border rounded-md shadow-sm focus:outline-none"
                                              :disabled="status == 3" 
                                              :class="{ 'opacity-50 pointer-events-none': status == 3 }"
                                              >
                                          </td>
                                      </tr>
                                      <tr class="border-b">
                                          <td class="py-3 px-4" colspan="5">
                                              <label for="vendor_note" class="block font-bold">Note <i class="fa fa-asterisk text-red-600"></i>:</label>
                                              <textarea x-model="formData.vendor_note" id="vendor_note" name="vendor_note" placeholder="Enter Note..." class="w-full p-2 mt-2 text-gray-500 border rounded-md shadow-sm focus:outline-none min-h-[150px]"
                                              :disabled="status == 3" 
                                              :class="{ 'opacity-50 pointer-events-none': status == 3 }"
                                              ></textarea>
                                              <p x-show="errors.vendor_note" class="text-red-500 text-xs italic" x-text="errors.vendor_note"
                                              ></p>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                          <div class="">
                            @if($interview->status == 1)
                              <button type="submit" name="acceptinterview" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-500 cursor-pointer">
                                Accept Interview
                              </button>
                            @endif
                          </div>
                      </form>
                      <div x-data="{ showModal: false, status: {{ $interview->status }} }">
                              <a href="javascript:void(0);" 
                                class="btn bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500" 
                                @click="showModal = true"
                                x-bind:disabled="status == 3"
                                :class="{ 'opacity-50 pointer-events-none': status == 3 }">
                                Reschedule/Cancel Interview
                              </a>
                              <!-- The Modal -->
                              <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" 
                                  @click.away="showModal = false">
                                  <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                                      <!-- Modal Header -->
                                      <div class="flex justify-between items-center p-4 border-b">
                                          <h4 class="text-lg font-semibold">Reschedule/Cancel Interview</h4>
                                          <button type="button" class="text-gray-500 hover:text-gray-700 bg-transparent" @click="showModal = false">&times;</button>
                                      </div>

                                      <!-- Modal Body -->
                                      <div class="p-4">
                                        <form x-data="rejectInterview()" @submit.prevent="submitData()" class="reject-form space-y-4">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700">Reschedule Reason:</label>
                                            <select 
                                                x-model="formData.reschedule_reason"
                                                id="reschedule_reason" 
                                                name="reschedule_reason"
                                                class="w-full px-3 py-2 border rounded-md"
                                                :class="{'border-red-500': errors.reschedule_reason}">
                                                <option value="">Select</option>
                                                @foreach (checksetting(20) as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            <p x-show="errors.reschedule_reason" class="text-red-500 text-xs italic" x-text="errors.reschedule_reason"></p>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Note <i class="fa fa-asterisk text-red-600"></i>:</label>
                                            <textarea 
                                                x-model="formData.rejection_note"
                                                name="rejection_note" 
                                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                                :class="{'border-red-500': errors.rejection_note}">
                                            </textarea>
                                            <p x-show="errors.rejection_note" class="text-red-500 text-xs italic" x-text="errors.rejection_note"></p>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="flex justify-end space-x-4 mt-2">
                                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-500">Submit</button>
                                            <button type="button" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-500" @click="showModal = false">Cancel</button>
                                        </div>
                                    </form>
                                      </div>
                                  </div>
                              </div>
                            </div>
                    </div>

                    <div class="h-[1024px]" x-show="activePage === 'tab2'">
                      @if ($interview->job_attachment)
                      @php $fileExtension = pathinfo($interview->job_attachment, PATHINFO_EXTENSION); @endphp
                          <object
                            data="{{ asset('storage/interview_resume/' . $interview->job_attachment) }}"
                            type="application/{{$fileExtension}}"
                            width="100%"
                            height="100%"
                          >
                            <p>
                              Alternative text - include a link
                              <a href="{{ asset('storage/interview_resume/' . $interview->job_attachment) }}">to the PDF!</a>
                            </p>
                          </object>
                        @else
                          <p>No resume available.</p>
                        @endif
                    </div>
                  </div>  
              </div>
            </div>
          </div>
        </div>
    </div>
    @endsection

<script>
function acceptInterview() {
    return {
        formData: {
            interviewTiming: '',
            can_phone: '',
            phone_ext: '',
            vendor_note: '',
        },
        errors: {},
        formatPhoneNumber(input) {
          let phoneNumber = input.value.replace(/\D/g, "");
          if (phoneNumber.length > 10) {
            phoneNumber = phoneNumber.slice(0, 10);
          }
          if (phoneNumber.length >= 6) {
            phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(
              4,
              7
            )}-${phoneNumber.slice(7)}`;
          } else if (phoneNumber.length >= 4) {
            phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(4)}`;
          }
          input.value = phoneNumber;
        },
        validateFields() {
            this.errors = {}; // Clear previous errors
            let errorCount = 0;

            if (this.formData.interviewTiming === "") {
                this.errors.interviewTiming = "Interview date is required";
                errorCount++;
            }

            if (this.formData.vendor_note.trim() === "") {
                this.errors.vendor_note = "Note is required";
                errorCount++;
            }

            return errorCount === 0;  // Return true if no errors
        },
        submitData() {
            if (this.validateFields()) {
                const formData = new FormData();
                formData.append('interviewTiming', this.formData.interviewTiming);
                formData.append('can_phone', this.formData.can_phone);
                formData.append('phone_ext', this.formData.phone_ext);
                formData.append('vendor_note', this.formData.vendor_note);

                const url = '{{ route("vendor.interview.saveTiming", $interview->id) }}';

                // Send AJAX request
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
            }
        },
        onSuccess(response) {
          window.location.href = response.redirect_url;
        }
    }
}

function rejectInterview() {
    return {
        formData: {
            reschedule_reason: '',
            rejection_note: ''
        },
        errors: {},

        validateFields() {
            this.errors = {}; // Reset errors

            let errorCount = 0;

            if (this.formData.reschedule_reason === "") {
                this.errors.reschedule_reason = "Reschedule reason is required";
                errorCount++;
            }

            if (this.formData.rejection_note.trim() === "") {
                this.errors.rejection_note = "Rejection note is required";
                errorCount++;
            }

            return errorCount === 0; // Returns true if no errors
        },

        submitData() {
            if (this.validateFields()) {
                const formData = new FormData();
                formData.append('reschedule_reason', this.formData.reschedule_reason);
                formData.append('rejection_note', this.formData.rejection_note);

                // Specify your form submission URL
                const url = '{{ route("vendor.interview.reject_interview", $interview->id) }}';

                // Send AJAX request using ajaxCall function
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
            }
        },

        onSuccess(response) {
          window.location.href = response.redirect_url;
        }
    }
}


</script>