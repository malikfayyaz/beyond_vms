@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('client.layouts.partials.header')

        <div>
          <div class="mx-4 rounded p-8">
            <div class="w-full flex justify-end items-center gap-4">

              <a href="{{ route('client.interview.index') }}">
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
                        <div class="flex justify-between py-3 px-4">
                          <span class="text-gray-600"
                            >Offer ID:</span
                          >
                          <span
                            class="font-semibold"
                          >{{$offer->id}}</span>
                        </div>
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

                        <div class="bg-blue-50 p-3 rounded-md">

                          <div class="flex justify-between items-center">
                            <span class="text-gray-600">  {{$interview->interviewDates()->primaryData()->schedule_date}} <br> <small>{{$interview->interviewDates()->primaryData()->formatted_start_time}} - {{$interview->interviewDates()->primaryData()->formatted_end_time}}</small></span>
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">{{$interview->status}}</span>
                            <span class="text-gray-600">{{ $interview->timezone->title }}</span>
                          </div>

                        </div>
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
              <p>No resume available.</p>
            @endif
            </div>
          </div>
        </div>

    </div>
    @endsection
