@extends('vendor.layouts.app')

@section('content')
      <!-- Sidebar -->
      @include('vendor.layouts.partials.dashboard_side_bar') <!-- Include the partial view -->
      
      
      <div class="ml-16">
      @include('vendor.layouts.partials.header')
       <div
         class="bg-white mx-4 my-8 rounded p-8"
         x-data="addSubWizarForm()"
         x-init="mounted()"
       >
         <!-- Success Notification -->
        
         @include('vendor.layouts.partials.alerts')
        
         <!-- Progress bar -->
         <div class="mb-8">
           <div class="flex mb-2">
             <nav aria-label="Progress" class="w-full">
               <ol
                 role="list"
                 class="flex w-full items-center border border-gray-300"
               >
                 <template x-for="(step, index) in steps" :key="index">
                   <li class="relative flex-1 flex items-center">
                     <div
                       class="group flex items-center w-full"
                       :class="{
                       'cursor-pointer': !formSubmitted && index + 1 <= highestStepReached,
                       'cursor-not-allowed': formSubmitted || index + 1 > highestStepReached
                     }"
                       @click="!formSubmitted && index + 1 <= highestStepReached && goToStep(index + 1)"
                     >
                       <span
                         class="flex items-center px-6 py-4 text-sm font-medium"
                       >
                         <span
                           class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full"
                           :class="{
                           'bg-blue-600 group-hover:bg-blue-800': currentStep > index + 1,
                           'border-2 border-blue-600': currentStep === index + 1,
                           'border-2 border-gray-300': currentStep < index + 1
                         }"
                         >
                           <!-- Check icon for completed steps -->
                           <svg
                             x-show="currentStep > index + 1"
                             class="h-6 w-6 text-white"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                           >
                             <path
                               stroke-linecap="round"
                               stroke-linejoin="round"
                               stroke-width="2"
                               d="M5 13l4 4L19 7"
                             />
                           </svg>

                           <!-- Current or future step number -->
                           <span
                             x-show="currentStep <= index + 1"
                             :class="currentStep === index + 1 ? 'text-blue-600' : 'text-gray-500'"
                             x-text="index + 1"
                           ></span>
                         </span>
                         <span
                           class="ml-4 text-sm font-medium"
                           :class="{
                           'text-blue-600': currentStep > index + 1,
                           'text-gray-900': currentStep === index + 1,
                           'text-gray-500': currentStep < index + 1
                         }"
                           x-text="step"
                         ></span>
                       </span>
                     </div>
                     <div
                       x-show="index !== steps.length - 1"
                       class="absolute top-0 right-0 h-full flex items-center"
                       aria-hidden="true"
                     >
                       <svg
                         class="h-full w-5"
                         :class="{
                         'text-blue-600': currentStep > index + 1,
                         'text-gray-300': currentStep <= index + 1
                       }"
                         viewBox="0 0 22 80"
                         fill="none"
                         preserveAspectRatio="none"
                       >
                         <path
                           d="M0 -2L20 40L0 82"
                           vector-effect="non-scaling-stroke"
                           stroke="currentcolor"
                           stroke-linejoin="round"
                         />
                       </svg>
                     </div>
                   </li>
                 </template>
               </ol>
             </nav>
           </div>
         </div>
         <!-- Cards -->
         <div class="mb-8">
           <div class="flex gap-4 w-full">
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
             >
               <div class="flex gap-6 items-center">
                 <div
                   class="bg-[#ddf6e8] w-12 h-12 rounded-full flex items-center justify-center"
                 >
                   <i class="fa-solid fa-user text-[#28c76f]"></i>
                 </div>
                 <div class="flex flex-col gap-2">
                   <span class="font-bold text-[#28c76f]">Client</span>
                   <span>{{ $career_opportunity->hiringManager->full_name }}</span>
                 </div>
               </div>
             </div>
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
             >
               <div class="flex gap-6 items-center">
                 <div
                   class="bg-[#D6F4F8] w-12 h-12 rounded-full flex items-center justify-center"
                   x-init="formData.jobId = '<?= $career_opportunity->id ?>';"
                 >
                   <i class="fa-solid fa-briefcase text-[#00bad1]"></i>
                 </div>
                 <div class="flex flex-col gap-2">
                   <span class="font-bold text-[#00bad1]">Job</span>
                   <span>{{ $career_opportunity->title }} ({{$career_opportunity->id}})</span>
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
                   <i class="fa-regular fa-clock text-[#ff9f43]"></i>
                 </div>
                 <div class="flex flex-col gap-2">
                   <span class="font-bold text-[#ff9f43]">Job Duration</span>
                   <span>{{ $career_opportunity->date_range }}</span>
                 </div>
               </div>
             </div>
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6"
             >
               <div class="flex gap-6 items-center">
                 <div
                   class="bg-[#E9E7FD] w-12 h-12 rounded-full flex items-center justify-center"
                 >
                   <i class="fa-regular fa-clock text-[#7367f0]"></i>
                 </div>
                 <div class="flex flex-col gap-2">
                   <span class="font-bold text-[#7367f0]">Client</span>
                   <span>Contractor Connect</span>
                 </div>
               </div>
             </div>
           </div>
         </div>
         <!-- Wizard form steps -->
         <form @submit.prevent="submitForm" id="generalformwizard">
           <!-- Step 1: Basic Info -->
           <div x-show="currentStep === 1">
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <!-- Step 1: First Card -->
               <div class="flex items-center mb-4 gap-2">
                 <i
                   class="fa-solid fa-circle-info"
                   :style="{'color': 'var(--primary-color)'}"
                 ></i>
                 <h2
                   class="text-xl font-bold"
                   :style="{'color': 'var(--primary-color)'}"
                 >
                   New Candidate/Existing
                 </h2>
               </div>
              <input type="hidden" value ="<?= $career_opportunity->id ?>" id="jobid">
              <input type="hidden" value ="0.00" id="over_time">
              <input type="hidden" value ="0.00" id="double_time_rate">
              <input type="hidden" value ="0.00" id="client_over_time_rate">
              <input type="hidden" value ="0.00" id="client_double_time_rate">
              <input type="hidden" value ="0.00" id="vendor_bill_rate_new">
              <input type="hidden" value ="0.00" id="vendor_over_time_rate_new">
              <input type="hidden" value ="0.00" id="vendor_double_time_rate_new">
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >New Candidate/Existing
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-ref="candidateType"
                     x-model="formData.candidateType"
                     class="w-full select2-single custom-style"
                     data-field="candidateType"
                     id="candidateType"
                   >
                     <option value="">Select</option>
                     <option value="1">New</option>
                     <option value="2">Existing</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('candidateType')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('candidateType')"
                   ></p>
                 </div>
                 <!-- Form Fields for Client New or Existing -->
                  @php 
                  $allcandidates = \App\Models\Consultant::where('vendor_id', \Auth::id())
                        ->get();
                        
                        @endphp
                 <div class="flex-1">
                   <div x-show="formData.candidateType === '2'">
                     <label class="block mb-2"
                       >Candidates <span class="text-red-500">*</span></label
                     >
                     <select
                       x-model="formData.candidateSelection"
                       class="w-full select2-single custom-style"
                       data-field="candidateSelection"
                       id="candidateSelection"
                     >
                       <option value="">Select</option>
                       @foreach($allcandidates as $candidate)
                       <option value="{{$candidate->user_id}}">{{ $candidate->full_name }}</option>
                        @endforeach
                      
                     </select>
                     <p
                       x-show="showErrors && !isFieldValid('candidateSelection')"
                       class="text-red-500 text-sm mt-1"
                       x-text="getErrorMessageById('candidateSelection')"
                     ></p>
                   </div>
                 </div>
                 <div class="flex-1"></div>
               </div>
             </div>
             <!-- Step 1: Second Card -->
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <div class="flex items-center mb-4 gap-2">
                 <i
                   class="fa-solid fa-circle-info"
                   :style="{'color': 'var(--primary-color)'}"
                 ></i>
                 <h2
                   class="text-xl font-bold"
                   :style="{'color': 'var(--primary-color)'}"
                 >
                   Personal Information
                 </h2>
               </div>

               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >First Name <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.candidateFirstName"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter first name"
                     id="candidateFirstName"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('candidateFirstName')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('candidateFirstName')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2">Middle Name</label>
                   <input
                     type="text"
                     id="candidateMiddleName"
                     x-model="formData.candidateMiddleName"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter middle name"
                   />
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Last Name <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.candidateLastName"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter last name"
                     id="candidateLastName"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('candidateLastName')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('candidateLastName')"
                   ></p>
                 </div>
               </div>
               <div
                 class="flex space-x-4 mt-4"
                 x-data="{
                      dobDate: '',
                      init() {
                          let dobPicker = flatpickr(this.$refs.dobPicker, {
                              dateFormat: 'auto',
                              altInput: true,
                              altFormat: 'F j, Y',
                              onChange: (selectedDates, dateStr) => {
                                this.formData.dobDate = dateStr;
                              }
                          });
                          this.$watch('dobDate', value => dobPicker.setDate(value));
                      }
                  }"
                  >
                 <div class="flex-1">
                   <label for="dobDate" class="block mb-2"
                     >Date of Birth (YYYY/MM/DD):
                     <span class="text-red-500">*</span></label
                   >
                   <input
                     id="dobDate"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none pl-7"
                     x-ref="dobPicker"
                     type="text"
                     x-model="formData.dobDate"
                     placeholder="Select DOB date"
                     readonly
                   />
                   <p
                     x-show="showErrors && !isFieldValid('dobDate')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('dobDate')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label for="lastFourNationalId" class="block mb-2">
                     Last 4 Numbers of National ID
                     <span class="text-red-500">*</span>
                   </label>
                   <input
                     type="text"
                     x-model="formData.lastFourNationalId"
                     x-on:input="formatNationalId"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter last 4 digits"
                     id="lastFourNationalId"
                     maxlength="4"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('lastFourNationalId')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('lastFourNationalId')"
                   ></p>
                 </div>
                 <div class="flex-1"></div>
               </div>
             </div>
           </div>
           <!-- Step 2: Duration and Description -->
           <div x-show="currentStep === 2">
             <!-- Step 2: First Card -->
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <div class="flex items-center mb-4 gap-2">
                 <i
                   class="fa-solid fa-circle-info"
                   :style="{'color': 'var(--primary-color)'}"
                 ></i>
                 <h2
                   class="text-xl font-bold"
                   :style="{'color': 'var(--primary-color)'}"
                 >
                   Rates Information
                 </h2>
               </div>
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label for="vendorMarkup" class="block mb-2"
                     >Vendor Markup</label
                   >
                   <div class="relative" >
                     <div
                       class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                       x-init="formData.vendorMarkup = '<?= $markup ?>';">
                       <i class="fas fa-percentage text-gray-400"></i>
                     </div>
                     <input
                       type="text"
                       x-model="formData.vendorMarkup"
                       id="vendorMarkup"
                       name="vendorMarkup"
                       value="formData.vendorMarkup"
                       disabled
                       class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     />
                   </div>
                 </div>
                 <div class="flex-1">
                   <label for="adjustedMarkup" class="block mb-2"
                     >Adjusted Markup</label
                   >
                   <div class="relative">
                     <div
                       class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                       x-init="formData.adjustedMarkup = '<?= $markup ?>';">
                       <i class="fas fa-percentage text-gray-400"></i>
                     </div>
                     <input
                       type="text"
                       x-model="formData.adjustedMarkup"
                       id="adjustedMarkup"
                       name="adjustedMarkup"
                        value="<?= $markup ?>"
                       disabled
                       class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     />
                   </div>
                 </div>
                 <div class="flex-1" x-init="formData.category_id = '<?= $career_opportunity->cat_id ?>';">
                   <label for="category" class="block mb-2">Category</label>
                   <input
                     type="text"
                     id="category"
                     x-model="formData.category"
                     name="category"
                     value="{{$career_opportunity->category->title}}"
                     disabled
                     class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   />
                 </div>
                 <div class="flex-1" x-init="formData.rateType = '<?= $career_opportunity->paymentType->title ?>';">
                   <label for="rateType" class="block mb-2">Rate Type</label>
                   <input
                     type="text"
                     id="rateType"
                     name="rateType"
                     value="{{$career_opportunity->paymentType->title}}"
                     x-model="formData.rateType"
                     disabled
                     class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   />
                 </div>
               </div>
               <div class="flex flex-col space-x-4 mb-4 mt-2">
                 <div
                   class="flex items-center mb-4 gap-2 border-b pb-2 w-full"
                 >
                   <i
                     class="fa-solid fa-circle-info"
                     :style="{'color': 'var(--primary-color)'}"
                   ></i>
                   <h4
                     class="text-lg font-bold"
                     :style="{'color': 'var(--primary-color)'}"
                   >
                     Candidate Rates
                   </h4>
                 </div>
                 <div class="flex w-full space-x-4">
                   <div class="flex-1">
                     <label for="rateType" class="block mb-2"
                       >Not to Exceed Bill Rate</label
                     >
                     <div class="relative" x-init="formData.exceedBillRate = '<?= $career_opportunity->min_bill_rate ?>';">
                       <span
                         class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                         >$</span
                       >
                       <input
                         type="text"
                         id="exceedBillRate"
                         value="<?= $career_opportunity->min_bill_rate ?>"
                         x-model="formData.exceedBillRate"
                         @input="formatExceedBillRate($event.target.value)"
                         @focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                         class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                         placeholder="75.00"
                       />
                     </div>
                   </div>
                   <div class="flex-1">
                     <label class="block mb-2">
                       Pay Rate <span class="text-red-500">*</span>
                     </label>
                     <div class="relative">
                       <div class="relative">
                         <span
                           class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                           >$</span
                         >
                         <input
                           type="text"
                           id="payRate"
                           x-model="formData.payRate"
                           @input="formatPayRate($event.target.value)"
                           @focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                           class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="0.00"
                         />
                         <p
                           x-show="errors.payRate"
                           x-text="errors.payRate"
                           class="text-red-500 text-sm mt-1"
                         ></p>
                       </div>
                     </div>
                   </div>
                   <div class="flex-1">
                     <label class="block mb-2">
                       Bill Rate <span class="text-red-500">*</span>
                     </label>
                     <div class="relative">
                       <div class="relative">
                         <span
                           class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"
                           >$</span
                         >
                         <input
                           type="text"
                           id="billRate"
                           x-model="formData.billRate"
                           @input="formatBillRate($event.target.value)"
                           @focus="$event.target.setSelectionRange(0, $event.target.value.length)"
                           class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="0.00"
                         />
                         <p
                           x-show="errors.billRate"
                           x-text="errors.billRate"
                           class="text-red-500 text-sm mt-1"
                         ></p>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
           <!-- Step 3: Additional Information -->
           <div x-show="currentStep === 3">
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <h2 class="text-2xl font-bold mb-4">Additional Information</h2>
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2">Candidate Home City/State</label>
                   <input
                     type="text"
                     x-model="formData.candidateHomeCity"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter home city/state"
                     id="candidateHomeCity"
                   />
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Preferred Name<span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.preferredName"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter preferred name"
                     id="preferredName"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('preferredName')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('preferredName')"
                   ></p>
                 </div>
               
               </div>
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Gender <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.gender"
                     class="w-full select2-single custom-style"
                     data-field="gender"
                     id="gender"
                   >
                     <option value="">Select Gender</option>
                     @foreach (checksetting(12) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('gender')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('gender')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Race <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.race"
                     class="w-full select2-single custom-style"
                     data-field="race"
                     id="race"
                   >
                     <option value="">Select Race</option>
                     @foreach (checksetting(11) as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('race')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('race')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Physical Work Location
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.workLocation"
                     class="w-full select2-single custom-style"
                     data-field="workLocation"
                     id="workLocation"
                   >
                     <option value="">Select Work Location</option>
                     @foreach ($location as $location)
                            <option value="{{ $location->id }}">{{ $location->location_details; }}</option>
                        @endforeach
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('workLocation')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('workLocation')"
                   ></p>
                 </div>
               </div>
               <!-- Add this after the existing fields in Step 3 -->
               <div class="flex-1">
                   <label class="block mb-2"
                     >Candidate Email Address
                     <span class="text-red-500">*</span></label
                   >
                   <input
                     type="email"
                     x-model="formData.candidateEmail"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter email address"
                     id="candidateEmail"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('candidateEmail')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('candidateEmail')"
                   ></p>
                 </div>
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2">Phone Number</label>
                   <input
                     type="tel"
                     x-model="formData.phoneNumber"
                     @input="formatPhoneNumber"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter phone number"
                     id="phoneNumber"
                   />
                   <p
                     x-show="showErrors && formData.phoneNumber && !isFieldValid('phoneNumber')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('phoneNumber')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Supplier Account Manager
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.supplierAccountManager"
                     class="w-full select2-single custom-style"
                     data-field="supplierAccountManager"
                     id="supplierAccountManager"
                   >
                     <option value="">Select Supplier Account Manager</option>
                    
                            <option value="{{ $vendor->user_id }}">{{ $vendor->full_name }}</option>
                     
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('supplierAccountManager')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('supplierAccountManager')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <!-- This empty div is to maintain the layout consistency -->
                 </div>
               </div>
             </div>
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <div class="flex items-center mb-4 gap-2 border-b pb-2 w-full">
                 <i
                   class="fa-solid fa-circle-info"
                   :style="{'color': 'var(--primary-color)'}"
                 ></i>
                 <h4
                   class="text-lg font-bold"
                   :style="{'color': 'var(--primary-color)'}"
                 >
                   Candidate Documents
                 </h4>
               </div>
               <!-- Add this notification at the top of Step 3, before the existing fields -->
               <div
                 class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4"
                 role="alert"
               >
                 <p class="font-bold">Note:</p>
                 <p>
                   Upload resume without logo/ candidate contact information.
                 </p>
               </div>

               <!-- Add this new row after the existing fields in Step 3 -->
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Resume / CV Upload
                     <span class="text-red-500">*</span></label
                   >
                   <input
                     type="file"
                     @change="handleResumeUpload"
                     class="w-full px-4 py-2 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     id="resumeUpload"
                     accept=".pdf,.doc,.docx"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('resumeUpload')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('resumeUpload')"
                   ></p>
                   <p
                     x-show="formData.resumeUpload"
                     class="text-sm text-gray-500 mt-1"
                     x-text="formData.resumeUpload ? formData.resumeUpload.name : ''"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2">Additional Document</label>
                   <input
                     type="file"
                     @change="handleAdditionalDocUpload"
                     class="w-full px-4 py-2 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     id="additionalDocUpload"
                     accept=".pdf,.doc,.docx"
                   />
                   <p
                     x-show="formData.additionalDocUpload"
                     class="text-sm text-gray-500 mt-1"
                     x-text="formData.additionalDocUpload ? formData.additionalDocUpload.name : ''"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <!-- This empty div is to maintain the layout consistency -->
                 </div>
               </div>
             </div>
           </div>
           <!-- Step 4: Other Details -->
           <div x-show="currentStep === 4">
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <div class="flex items-center mb-4 gap-2">
                 <i
                   class="fa-solid fa-circle-info"
                   :style="{'color': 'var(--primary-color)'}"
                 ></i>
                 <h2
                   class="text-xl font-bold"
                   :style="{'color': 'var(--primary-color)'}"
                 >
                   Personal Information
                 </h2>
               </div>
               <!-- Add this new row after the existing fields -->
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Available Date <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.availableDate"
                     x-ref="availableDatePicker"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Select available date"
                     readonly
                   />
                   <p
                     x-show="showErrors && !isFieldValid('availableDate')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('availableDate')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Is This Worker or Will This Worker Need Sponsorship Now
                     or In The Future?
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.needSponsorship"
                     class="w-full select2-single custom-style"
                     data-field="needSponsorship"
                     id="needSponsorship"
                   >
                     <option value="">Select</option>
                     <option value="yes">Yes</option>
                     <option value="no">No</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('needSponsorship')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('needSponsorship')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Has this candidate ever worked for any Gallagher company
                     in any capacity?
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.workedForGallagher"
                     class="w-full select2-single custom-style"
                     data-field="workedForGallagher"
                     id="workedForGallagher"
                   >
                     <option value="">Select</option>
                     <option value="yes">Yes</option>
                     <option value="no">No</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('workedForGallagher')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('workedForGallagher')"
                   ></p>
                 </div>
               </div>

               <!-- Conditional fields for Gallagher work history -->
               <div
                 x-show="formData.workedForGallagher === 'yes'"
                 class="flex space-x-4 mb-4"
               >
                 <div class="flex-1">
                   <label class="block mb-2"
                     >In What Capacity
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.gallagherCapacity"
                     class="w-full select2-single custom-style"
                     data-field="gallagherCapacity"
                     id="gallagherCapacity"
                   >
                     <option value="">Select</option>
                     <option value="previous_employee">
                       Previous Employee
                     </option>
                     <option value="previous_contingent">
                       Previous Contingent
                     </option>
                     <option value="retiree">Retiree</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('gallagherCapacity')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('gallagherCapacity')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Start Date <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.gallagherStartDate"
                     x-ref="gallagherStartDatePicker"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Select start date"
                     readonly
                   />
                   <p
                     x-show="showErrors && !isFieldValid('gallagherStartDate')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('gallagherStartDate')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Last Date <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.gallagherLastDate"
                     x-ref="gallagherLastDatePicker"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Select last date"
                     readonly
                   />
                   <p
                     x-show="showErrors && !isFieldValid('gallagherLastDate')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('gallagherLastDate')"
                   ></p>
                 </div>
               </div>

             

               <!-- Conditional fields for Virtual/Remote Candidate -->
            

               <!-- Add this new row after the existing fields in Step 3 -->
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Is this Candidate willing to Commute to Office?
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.willingToCommute"
                     class="w-full select2-single custom-style"
                     data-field="willingToCommute"
                     id="willingToCommute"
                   >
                     <option value="">Select</option>
                     <option value="yes">Yes</option>
                     <option value="no">No</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('willingToCommute')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('willingToCommute')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Virtual/Remote Candidate?
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.virtualRemoteCandidate"
                     class="w-full select2-single custom-style"
                     data-field="virtualRemoteCandidate"
                     id="virtualRemoteCandidate"
                   >
                     <option value="">Select</option>
                     <option value="yes">Yes</option>
                     <option value="no">No</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('virtualRemoteCandidate')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('virtualRemoteCandidate')"
                   ></p>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Do you have the right to represent?
                     <span class="text-red-500">*</span></label
                   >
                   <select
                     x-model="formData.rightToRepresent"
                     class="w-full select2-single custom-style"
                     data-field="rightToRepresent"
                     id="rightToRepresent"
                   >
                     <option value="">Select</option>
                     <option value="yes">Yes</option>
                     <option value="no">No</option>
                   </select>
                   <p
                     x-show="showErrors && !isFieldValid('rightToRepresent')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('rightToRepresent')"
                   ></p>
                 </div>
               </div>

               <!-- Conditional fields for Virtual/Remote Candidate -->
               <div
                 x-show="formData.virtualRemoteCandidate === 'yes'"
                 class="flex space-x-4 mb-4"
               >
                 <div class="flex-1">
                   <label class="block mb-2"
                     >Virtual City <span class="text-red-500">*</span></label
                   >
                   <input
                     type="text"
                     x-model="formData.virtualCity"
                     class="w-full h-12 px-4 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     placeholder="Enter virtual city"
                     id="virtualCity"
                   />
                   <p
                     x-show="showErrors && !isFieldValid('virtualCity')"
                     class="text-red-500 text-sm mt-1"
                     x-text="getErrorMessageById('virtualCity')"
                   ></p>
                 </div>
                
                 <div class="flex-1">
                   <!-- This empty div is to maintain the layout consistency -->
                 </div>
               </div>
             </div>
             <div
               class="shadow-[0_3px_10px_rgb(0,0,0,0.2)] bg-white rounded w-full p-6 mb-8"
             >
               <!-- Add this new row after the existing fields in Step 3 -->
               <div class="flex space-x-4 mb-4">
                 <div class="flex-1">
                   <label class="block mb-2">Avail to Interview Notes</label>
                   <textarea
                     x-model="formData.availToInterviewNotes"
                     class="w-full px-4 py-2 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     rows="3"
                     placeholder="Enter notes about availability for interview (optional)"
                     id="availToInterviewNotes"
                   ></textarea>
                 </div>
                 <div class="flex-1">
                   <label class="block mb-2">Comment</label>
                   <textarea
                     x-model="formData.comment"
                     class="w-full px-4 py-2 text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none"
                     rows="3"
                     placeholder="Enter any additional comments (optional)"
                     id="comment"
                   ></textarea>
                 </div>
               </div>
             </div>
           </div>
           <!-- Navigation buttons -->
           <div class="flex justify-between mt-6">
             <button
               x-show="currentStep > 1 && !formSubmitted"
               @click="goToStep(currentStep - 1)"
               type="button"
               class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400"
             >
               Previous
             </button>
             <button
               x-show="currentStep < 4 && !formSubmitted"
               @click="nextStep"
               type="button"
               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
             >
               Next
             </button>
             <button
               x-show="currentStep === 4"
               type="submit"
               class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
             >
               Submit
             </button>
           </div>
         </form>
       </div>
     </div>
      @endsection
   
