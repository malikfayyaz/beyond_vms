@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          @include('admin.layouts.partials.header')
          <div class="bg-white mx-4 my-8 rounded p-8">
          <div class="mb-4">
            <ul
              class="grid grid-flow-col text-center text-gray-500 bg-gray-100 rounded-lg p-1"
            >
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="w-full flex justify-center items-center gap-3 hover:bg-white hover:rounded-lg hover:shadow py-4"
                >
                  <i class="fa-regular fa-file-lines"></i>
                  <span class="capitalize">active jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">156</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center items-center">
                <a
                  href="#page2"
                  class="w-full flex justify-center items-center gap-3 bg-white rounded-lg shadow py-4"
                  :style="{'color': 'var(--primary-color)'}"
                  ><i class="fa-regular fa-registered"></i
                  ><span class="capitalize">Pending Release Job</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center text-white rounded-lg"
                    :style="{'background-color': 'var(--primary-color)'}"
                  >
                    <span class="text-[10px]">56</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-fill"></i>
                  <span class="capitalize">filled jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">20</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-lock"></i>
                  <span class="capitalize">closed jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">2957</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-spinner"></i>
                  <span class="capitalize">pending - PMO</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">0</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fas fa-drafting-compass"></i>
                  <span class="capitalize">draft</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">30</span>
                  </div>
                </a>
              </li>
              <li class="flex justify-center">
                <a
                  href="#page1"
                  class="flex justify-center items-center gap-3 py-4 w-full hover:bg-white hover:rounded-lg hover:shadow"
                >
                  <i class="fa-solid fa-briefcase"></i>
                  <span class="capitalize">all jobs</span>
                  <div
                    class="px-1 py-1 flex items-center justify-center bg-gray-500 text-white rounded-lg"
                  >
                    <span class="text-[10px]">4320</span>
                  </div>
                </a>
              </li>
            </ul>
          </div>
          <div class="flex w-full gap-4">
            <!-- Left Column -->
            <div
              class="w-1/3 p-[30px] rounded border"
              :style="{'border-color': 'var(--primary-color)'}"
            >
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
                          >Regular Hours Cost</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                      <span>$78,000.00</span>
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
                          >Single Resource Cost</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                      <span>$78,000.00</span>
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
                          >All Resources Cost</span
                        >
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                      <span>$78,000.00</span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Business Unit & Business Percentage -->
              <div class="mt-4">
                <div
                  class="flex py-4 px-2 rounded rounded-b-none"
                  :style="{'background-color': 'var(--primary-color)'}"
                >
                  <div class="w-3/5">
                    <span class="text-white">Business Unit</span>
                  </div>
                  <div class="w-2/5 text-center">
                    <span class="text-white">Budget Percentage</span>
                  </div>
                </div>
                <div
                  class="flex justify-between gap-2 py-4 px-2 border-x border-b"
                >
                  <div class="w-3/5 flex-wrap">
                    <span>708212 - 166 - St. Peters, MO - LPG</span>
                  </div>
                  <div class="w-2/5 text-center">
                    <span>100%</span>
                  </div>
                </div>
              </div>
              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">
                  Please list the preferred agency(s)/vendor(s) to utilize for
                  filling this position, and list any other relevant information
                  for the Program Office
                </p>
                <div class="mt-4">
                  <ul class="color-[#202124] font-light">
                    <li>PRG</li>
                    <li>Canon</li>
                    <li>Insight Global</li>
                    <li>Professional Staffing</li>
                  </ul>
                </div>
                <p class="mt-4">UPDATE HIRING MANAGER TO Suzanne Touch</p>
                <p class="mt-4">(Justin Stephenson Vacancy)</p>
              </div>
              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">Business Justification</p>
                <div class="mt-4">
                  <ul class="color-[#202124] font-light">
                    <li>Line 14724 approved 7/25/2024</li>
                    <li>(Justin Stephenson Vacancy)</li>
                  </ul>
                </div>
              </div>
              <div class="mt-4 rounded p-4 bg-[#F5F7FC]">
                <p class="color-[#202124] font-light">
                  Pre-Identified Candidate
                </p>
                <div
                  class="flex items-center mt-4 border rounded"
                  :style="{'border-color': 'var(--primary-color)'}"
                >
                  <div
                    class="py-4 w-2/4 pl-4 rounded rounded-r-none"
                    :style="{'background-color': 'var(--primary-color)'}"
                  >
                    <span class="text-white font-light"
                      >Pre-Identified Candidate?</span
                    >
                  </div>
                  <div class="w-2/4 pl-4">
                    <span class="color-[#202124] font-light">No</span>
                  </div>
                </div>
              </div>
            </div>
            <!-- Middle Column -->
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
                  >Job Info</span
                >
              </h3>
              <div class="flex flex-col">
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Job Title:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Senior Resolution Manager</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Hiring Manager:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Donna Stockton</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Job Title for Email Signature:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Senior Resolution Manager</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Work Location:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">
                      US Saint Peters 300 St. Peters Centre Blvd.-300 St. Peters
                      Centre Blvd.-Saint Peters-Missouri-63376
                    </p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Division:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Gallagher Bassett Services</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Region/Zone:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">North America Operations</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Branch:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Claims Services</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Job Code:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">100259</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Category:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Claims</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Travel Required:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">No</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Business Reason:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Replacement - Budgeted</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Time System:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Contractor Connect</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Client Billable:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">No</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Expenses Allowed?</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">No</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Remote Candidate:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Yes</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Number of Opening(s):</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">1</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Worker Type:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Eligible for Overtime Premium</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">Job Family:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">777007 - Contingent Worker-Claims</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">GL Account:</h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">507105 - Temporary Help</p>
                  </div>
                </div>
                <div class="flex items-center justify-between py-4 border-t">
                  <div class="w-2/4">
                    <h4 class="font-medium">
                      Will this Position Require the Worker to Work OT?:
                    </h4>
                  </div>
                  <div class="w-2/4">
                    <p class="font-light">Yes</p>
                  </div>
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
                  class="fa-regular fa-clock"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}"
                  >Job Duration</span
                >
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Work Days / Week:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">5</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Total Working Days:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">130</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Estimated Hours / Day:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">8</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Total Time:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">1040</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-y">
                <div class="w-2/4">
                  <h4 class="font-medium">Job Duration:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">07/29/2024 - 01/24/2025</p>
                </div>
              </div>
              <!-- Rates -->
              <h3 class="flex items-center gap-2 my-4">
                <i
                  class="fa-regular fa-money-bill-1"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}">Rates</span>
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Unit of Measure:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">Per Hour</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Currency:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">USD</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Minimum Bill Rate:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">$20.00</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Maximum Bill Rate:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">$75.00</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-y">
                <div class="w-2/4">
                  <h4 class="font-medium">Time Type:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">Full Time</p>
                </div>
              </div>
              <!-- Job Publish Info -->
              <h3 class="flex items-center gap-2 my-4">
                <i
                  class="fa-solid fa-upload"
                  :style="{'color': 'var(--primary-color)'}"
                ></i
                ><span :style="{'color': 'var(--primary-color)'}"
                  >Job Publish Info</span
                >
              </h3>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Job Created:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">07/25/2024 02:38 PM</p>
                </div>
              </div>
              <div class="flex items-center justify-between py-4 border-t">
                <div class="w-2/4">
                  <h4 class="font-medium">Job Created By:</h4>
                </div>
                <div class="w-2/4">
                  <p class="font-light">Donna Stockton</p>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    @endsection
