@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('admin.layouts.partials.header')

        <div>
          <div class="mx-4 rounded p-8">
            <div class="w-full flex justify-end items-center gap-4">

              <div x-data="{ showModal: false, status: {{ $interview->status }} }">
                  <a href="javascript:void(0);"
                    class="btn bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500"
                    @click="showModal = true"
                    x-bind:disabled="status == 3"
                    :class="{ 'opacity-50 pointer-events-none': status == 3  || status == 5}">
                      {{translate('Reschedule/Cancel Interview')}}
                  </a>
                  <!-- The Modal -->
                  <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                      @click.away="showModal = false">
                      <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                          <!-- Modal Header -->
                          <div class="flex justify-between items-center p-4 border-b">
                              <h4 class="text-lg font-semibold">Reschedule/Cancel Interview')}}</h4>
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
                                    <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500">Submit</button>
                                    <button type="button" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-500" @click="showModal = false">Cancel</button>
                                </div>
                            </form>
                          </div>
                      </div>
                  </div>
              </div>

              <div x-data="{ showModal: false, status: {{ $interview->status }} }">
                <a href="javascript:void(0);"
                  class="btn bg-red-600 text-white py-2 px-4 rounded hover:bg-red-400"
                  @click="showModal = true"
                  x-bind:disabled="status == 3 || status == 1"
                  :class="{ 'opacity-50 pointer-events-none': status == 3 || status == 1}">
                    {{translate('Reject Candidate')}}
                </a>
                <!-- The Modal -->
                <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                    @click.away="showModal = false">
                    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center p-4 border-b">
                            <h4 class="text-lg font-semibold ">Reject Candidate</h4>
                            <button type="button" class="text-gray-500 hover:text-gray-700 bg-transparent" @click="showModal = false">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-4">
                          <form x-data="rejectCandidate()" @submit.prevent="submitData()" class="complete-form space-y-4">
                          @csrf
                          <div class="mb-4">
                              <label class="block text-sm font-medium text-gray-700">Reason for Candidate Rejection:</label>
                              <select
                                  x-model="formData.cand_rej_reason"
                                  id="cand_rej_reason"
                                  name="cand_rej_reason"
                                  class="w-full px-3 py-2 border rounded-md"
                                  :class="{'border-red-500': errors.cand_rej_reason}">
                                  <option value="">Select</option>
                                  @foreach (checksetting(24) as $key => $value)
                                      <option value="{{ $key }}">{{ $value }}</option>
                                  @endforeach
                              </select>
                              <p x-show="errors.cand_rej_reason" class="text-red-500 text-xs italic" x-text="errors.cand_rej_reason"></p>
                          </div>

                          <div>
                              <label class="block text-sm font-medium text-gray-700">Note <i class="fa fa-asterisk text-red-600"></i>:</label>
                              <textarea
                                  x-model="formData.cand_rej_note"
                                  name="cand_rej_note"
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                  :class="{'border-red-500': errors.cand_rej_note}">
                              </textarea>
                              <p x-show="errors.cand_rej_note" class="text-red-500 text-xs italic" x-text="errors.cand_rej_note"></p>
                          </div>

                          <!-- Submit Button -->
                          <div class="flex justify-end space-x-4 mt-2">
                              <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-500">Submit</button>
                              <button type="button" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-500" @click="showModal = false">Cancel</button>
                          </div>
                      </form>
                        </div>
                    </div>
                </div>
              </div>

              <a href="{{ route('admin.interview.index') }}">
                  <button
                      type="button"
                      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 capitalize"
                  >
                      {{translate('Back to Interview')}}
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
                      <span class="capitalize">{{translate('Summary')}}</span>
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
                      <span class="capitalize"> {{translate('Date & Time')}}</span>
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
                        <span class="text-gray-600">{{translate('Status:')}}</span>
                        <span
                            class="bg-green-500 text-white px-2 py-1 rounded-full text-sm"
                            >{{$interview->getInterviewStatus($interview->status)}}</span>
                        </div>
                        @if($interview->status == 3)
                          <div class="flex justify-between py-3 px-4">
                            <p class="font-bold text-red-400">
                              <i class="fas fa-clipboard"></i> {{translate('Cancel Reason')}}
                            </p>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Reason for Cancel:')}}</span>
                            <span class="font-semibold">{{$interview->reasonRejection->title}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Cancel Notes:')}}</span>
                            <span class="font-semibold">{{$interview->notes}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Cancel By:')}}</span>
                            <span class="font-semibold">{{$interview->rejectedBy->name}}</span>
                          </div>
                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Date & Time:')}}</span>
                            <span class="font-semibold">{{$interview->formatted_interview_cancellation_date}}</span>
                          </div>
                        @endif
                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fas fa-user"></i> {{translate('Candidate Info')}}
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Candidate Name:')}}</span>
                            <span class="font-semibold">{{$interview->consultant->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Vendor Name:')}}</span>
                            <span
                            class="font-semibold"
                            >{{$interview->consultant->vendor->full_name}}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Division')}}</span>
                          <span
                            class="font-semibold"
                          >{{$interview->careerOpportunity->division->name}}</span>
                        </div>
                        @isset($interview->offer)
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Offer ID:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{$interview->offer->id}}</span>
                        </div>
                        @endisset
                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fas fa-comments"></i> {{translate('Interview Details')}}
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >{{translate('Type of Interview:')}}</span
                          >
                          <span
                            class="font-semibold"
                          >{{$interview->interviewtype->title}}</span>
                        </div>
                        @if (!empty($interview->interview_instructions))
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-gray-600">{{translate('Interview Instruction:')}}</span>
                                <span class="font-semibold">{{ $interview->interview_instructions }}</span>
                            </div>
                        @endif

                        @if (!empty($interview->interview_detail))
                            <div class="flex justify-between py-3 px-4">
                                <span class="text-gray-600">{{translate('Interview Detail:')}}</span>
                                <span class="font-semibold">{{ $interview->interview_detail }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Location:')}}</span>
                          <span
                            class="font-semibold"
                          >{{ $interview->location->LocationDetails }}</span>
                        </div>
                        <div class="flex justify-between py-3 px-4" x-data="{ jobDetails: null}" @job-details-updated.window="jobDetails = $event.detail">
                          <span class="text-gray-600">{{translate('Job Profile:')}}</span>
                          <span
                            class="font-semibold"
                          >
                          <a class="text-blue-400 font-semibold cursor-pointer"
                            onclick="openJobDetailsModal({{ $interview->careerOpportunity->id }})"
                            >{{$interview->careerOpportunity->title}} ({{$interview->careerOpportunity->id}})</a
                          >
                          </span>
                          <x-job-details />
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Timezone:')}}</span>
                          <span
                            class="font-semibold"
                          >{{ $interview->timezone->title }}</span>
                        </div>

                        <div class="flex justify-between py-3 px-4">
                          <p class="font-bold text-blue-400">
                              <i class="fa fa-calendar"></i> {{translate('Interview Date')}}
                          </p>
                        </div>
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Date of Interview:')}}</span>
                          <span
                            class="font-semibold"
                          >
                          {{ $interview->interviewDates()->primaryData()->schedule_date }}</span>
                        </div>
                        @if (!empty($interview->job_attachment))
                          <div class="flex justify-between py-3 px-4">
                            <p class="font-bold text-blue-400">
                            <i class="fa fa-plus-square"></i> {{translate('Additional Details')}}
                            </p>
                          </div>

                          <div class="flex justify-between py-3 px-4">
                            <span class="text-gray-600">{{translate('Resume:')}}</span>
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
                          <i class="fa fa-user-plus"></i> {{translate('Interviewer(s)')}}
                          </p>
                        </div>
                        @if($interview->interviewMembers)
                        @foreach($interview->interviewMembers as $member)
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600">{{translate('Interviewer:')}}</span>
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
                          <span class="text-gray-600">{{translate('Date & Time:')}}</span>
                        </div>
                        @foreach($interview->interviewDates as $dates)
                        <div class="bg-blue-50 p-3 rounded-md">

                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">  {{$dates->formatted_schedule_date}} <br> <small>{{$dates->formatted_start_time}} - {{$dates->formatted_end_time}}</small></span>
{{--                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">{{$interview->status}}</span>--}}
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
            <div class="w-2/4 bg-white h-[1024px] mx-4 rounded p-8">
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
              <p>{{translate('No resume available.')}}</p>
            @endif
            </div>
          </div>
        </div>

    </div>

<script>
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
                const url = '{{ route("interview.reject_interview", $interview->id) }}';

                // Send AJAX request using ajaxCall function
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
            }
        },

        onSuccess(response) {
          window.location.href = response.redirect_url;
        }
      }
  }
  function rejectCandidate() {
    return {
        formData: {
          cand_rej_reason: '',
          cand_rej_note: ''
        },
        errors: {},

        validateFields() {
            this.errors = {}; // Reset errors

            let errorCount = 0;

            if (this.formData.cand_rej_reason === "") {
                this.errors.cand_rej_reason = "Candidate rejection reason is required";
                errorCount++;
            }

            if (this.formData.cand_rej_note.trim() === "") {
                this.errors.cand_rej_note = "Candidate rejection note is required";
                errorCount++;
            }

            return errorCount === 0; // Returns true if no errors
        },

        submitData() {
            if (this.validateFields()) {
                const formData = new FormData();
                formData.append('cand_rej_reason', this.formData.cand_rej_reason);
                formData.append('cand_rej_note', this.formData.cand_rej_note);

                // Specify your form submission URL
                const url = '{{ route("interview.rejectCandidate", $interview->id) }}';

                // Send AJAX request using ajaxCall function
                ajaxCall(url, 'POST', [[this.onSuccess, ['response']]], formData);
            }
        },

        onSuccess(response) {
          window.location.href = response.redirect_url;
        }
      }
  }

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
    @endsection
