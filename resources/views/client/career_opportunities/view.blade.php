@extends('admin.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('admin.layouts.partials.dashboard_side_bar')
      <div class="ml-16">
          <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/alpine.min.js" defer></script>
          @include('admin.layouts.partials.header')
          <div class="ml-16">
              <div x-data="{ activeTab: 'activeJobs' }" class="py-6 sm:px-6 lg:px-8">
                  <!-- Tabs -->
                  <div class="mb-4 border-b border-gray-200">
                      <ul class="flex flex-wrap -mb-px">
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'activeJobs'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'activeJobs'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Active Jobs
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'pendingReleaseJobs'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'pendingReleaseJobs'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Pending Release Jobs
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'filledJobs'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'filledJobs'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Filled Jobs
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'closedJobs'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'closedJobs'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Closed Jobs
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'pendingPMO'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'pendingPMO'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Pending - PMO
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'draft'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'draft'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  Draft
                              </a>
                          </li>
                          <li class="mr-2">
                              <a
                                  href="#"
                                  @click.prevent="activeTab = 'allJobs'"
                                  :class="{'border-blue-500 text-blue-600': activeTab === 'allJobs'}"
                                  class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                              >
                                  All Jobs
                              </a>
                          </li>
                      </ul>
                  </div>

                  <!-- Tab Contents -->
                  <div class="bg-white shadow overflow-hidden sm:rounded-lg py-6">
                      <!-- Active Jobs Tab Start from here -->
                      <div x-show="activeTab === 'activeJobs'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              selectedUser: null,
              toggleSidebar(id) {
                this.selectedUserId = this.selectedUserId === id ? null : id;
              },
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500 cursor-pointer"
                                                      @click="selectedUser = user"
                                                  >
                                                      <span x-text="user.id"></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>

                                  <!-- Overlay -->
                                  <div
                                      x-show="selectedUser !== null"
                                      @click="selectedUser = null"
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0"
                                      x-transition:enter-end="opacity-100"
                                      x-transition:leave="transition ease-in duration-300"
                                      x-transition:leave-start="opacity-100"
                                      x-transition:leave-end="opacity-0"
                                      class="fixed inset-0 bg-black bg-opacity-50"
                                  ></div>
                                  <!-- Slide-in Window -->
                                  <div
                                      x-show="selectedUser !== null"
                                      @click.stop
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="transform translate-x-full"
                                      x-transition:enter-end="transform translate-x-0"
                                      x-transition:leave="transition ease-in duration-300"
                                      x-transition:leave-start="transform translate-x-0"
                                      x-transition:leave-end="transform translate-x-full"
                                      class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24"
                                  >
                                      <template x-if="selectedUser">
                                          <div>
                                              <div
                                                  class="flex justify-between items-center p-4 bg-gray-800 text-white"
                                              >
                                                  <h2 class="text-lg font-semibold">
                                                      Job:
                                                      <span x-text="selectedUser.title"></span> (<span
                                                          x-text="selectedUser.id"
                                                      ></span
                                                      >)
                                                  </h2>

                                                  <button
                                                      @click="selectedUser = null"
                                                      class="text-gray-500 hover:text-gray-700"
                                                  >
                                                      <i class="fas fa-times"></i>
                                                  </button>
                                              </div>

                                              <div class="p-4 bg-gray-200">
                                                  <p>
                                                      Job Created by
                                                      <span x-text="selectedUser.hiringManager"></span> on
                                                      <span x-text="selectedUser.jobDuration"></span>
                                                  </p>
                                              </div>
                                              <div class="p-4">
                                                  <ul class="flex w-full items-center gap-2">
                                                      <li
                                                          class="py-2 px-4 border border-blue-800 bg-white w-1/5 shadow-md flex flex-col items-center rounded"
                                                      >
                                                          <h4 class="text-blue-800 font-bold">
                                                              Submission
                                                          </h4>
                                                          <div
                                                              class="flex mt-2 w-full items-center justify-between"
                                                          >
                                                              <span class="text-blue-800 font-bold">0</span>
                                                              <i
                                                                  class="fa-solid fa-graduation-cap text-blue-800"
                                                              ></i>
                                                          </div>
                                                      </li>
                                                      <li
                                                          class="py-2 px-4 border border-red-800 bg-white w-1/5 shadow-md flex flex-col items-center rounded"
                                                      >
                                                          <h4 class="text-red-800 font-bold">Interviews</h4>
                                                          <div
                                                              class="flex mt-2 w-full items-center justify-between"
                                                          >
                                                              <span class="text-red-800 font-bold">0</span>
                                                              <i
                                                                  class="fa-solid fa-graduation-cap text-red-800"
                                                              ></i>
                                                          </div>
                                                      </li>
                                                      <li
                                                          class="py-2 px-4 border border-yellow-800 bg-white w-1/5 shadow-md flex flex-col items-center rounded"
                                                      >
                                                          <h4 class="text-yellow-800 font-bold">Offers</h4>
                                                          <div
                                                              class="flex mt-2 w-full items-center justify-between"
                                                          >
                                                              <span class="text-yellow-800 font-bold">0</span>
                                                              <i
                                                                  class="fa-solid fa-graduation-cap text-yellow-800"
                                                              ></i>
                                                          </div>
                                                      </li>
                                                      <li
                                                          class="py-2 px-4 border border-purple-800 bg-white w-1/5 shadow-md flex flex-col items-center rounded"
                                                      >
                                                          <h4 class="text-purple-800 font-bold">
                                                              Workorders
                                                          </h4>
                                                          <div
                                                              class="flex mt-2 w-full items-center justify-between"
                                                          >
                                                              <span class="text-purple-800 font-bold">0</span>
                                                              <i
                                                                  class="fa-solid fa-graduation-cap text-purple-800"
                                                              ></i>
                                                          </div>
                                                      </li>
                                                      <li
                                                          class="py-2 px-4 border border-green-800 bg-white w-1/5 shadow-md flex flex-col items-center rounded"
                                                      >
                                                          <h4 class="text-green-800 font-bold">Hired</h4>
                                                          <div
                                                              class="flex mt-2 w-full items-center justify-between"
                                                          >
                                                              <span class="text-green-800 font-bold">0</span>
                                                              <i
                                                                  class="fa-solid fa-graduation-cap text-green-800"
                                                              ></i>
                                                          </div>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <!-- Job Info Table-->
                                              <div class="p-4 mb-2">
                                                  <div class="container mx-auto px-4">
                                                      <!-- Table Title -->
                                                      <div
                                                          class="flex items-center p-4 bg-gray-800 rounded-t-lg"
                                                      >
                                                          <i
                                                              class="fas fa-info-circle text-white text-xl mr-2"
                                                          ></i>
                                                          <h2 class="text-xl font-semibold text-white">
                                                              Job Info
                                                          </h2>
                                                      </div>

                                                      <!-- Table -->
                                                      <div class="overflow-x-auto">
                                                          <table
                                                              class="w-full bg-white shadow-white shadow-md rounded-b-lg overflow-hidden"
                                                          >
                                                              <tbody>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Job Status:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">
                                      <span
                                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          :class="{
                                                      'bg-green-100 text-green-800': selectedUser.status === 'Active',
                                                      'bg-red-100 text-red-800': selectedUser.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': selectedUser.status === 'Pending'
                                                  }"
                                          x-text="selectedUser.status"
                                      ></span>
                                                                  </td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Location:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">
                                                                      US Saint Peters 300 St. Peters Centre
                                                                      Blvd.
                                                                  </td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Number of Opening(s):
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">1</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">Category</td>
                                                                  <td class="px-4 py-3 border-b">Claims</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3">Expenses Allowed?</td>
                                                                  <td class="px-4 py-3">No</td>
                                                              </tr>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                              <!-- Job Duration Table-->
                                              <div class="p-4 mb-2">
                                                  <div class="container mx-auto px-4">
                                                      <!-- Table Title -->
                                                      <div
                                                          class="flex items-center p-4 bg-gray-800 rounded-t-lg"
                                                      >
                                                          <i
                                                              class="fa-solid fa-calendar-days text-white text-xl mr-2"
                                                          ></i>
                                                          <h2 class="text-xl font-semibold text-white">
                                                              Job Duration
                                                          </h2>
                                                      </div>

                                                      <!-- Table -->
                                                      <div class="overflow-x-auto">
                                                          <table
                                                              class="w-full bg-white shadow-white shadow-md rounded-b-lg overflow-hidden"
                                                          >
                                                              <tbody>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">Days:</td>
                                                                  <td class="px-4 py-3 border-b">130</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Total Hours:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">1040</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Job Duration:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">
                                      <span
                                          x-text="selectedUser.jobDuration"
                                      ></span>
                                                                  </td>
                                                              </tr>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                              <!-- Job Rates Table-->
                                              <div class="p-4 mb-2">
                                                  <div class="container mx-auto px-4">
                                                      <!-- Table Title -->
                                                      <div
                                                          class="flex items-center p-4 bg-gray-800 rounded-t-lg"
                                                      >
                                                          <i
                                                              class="fa-solid fa-money-bill-wave text-white text-xl mr-2"
                                                          ></i>
                                                          <h2 class="text-xl font-semibold text-white">
                                                              Rates
                                                          </h2>
                                                      </div>

                                                      <!-- Table -->
                                                      <div class="overflow-x-auto">
                                                          <table
                                                              class="w-full bg-white shadow-white shadow-md rounded-b-lg overflow-hidden"
                                                          >
                                                              <tbody>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Rate Type:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">Per Hour</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Minimum Bill Rate:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">$20.00</td>
                                                              </tr>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      Maximum Bill Rate:
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">$75.00</td>
                                                              </tr>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                              <!-- Business Unit & Budget Percentage Table -->
                                              <div class="p-4 mb-2">
                                                  <div class="container mx-auto px-4">
                                                      <!-- Table -->
                                                      <div class="overflow-x-auto">
                                                          <table
                                                              class="w-full bg-white shadow-md rounded-lg overflow-hidden"
                                                          >
                                                              <thead class="bg-gray-800">
                                                              <tr>
                                                                  <th class="px-4 py-2 text-left text-white">
                                                                      Business Unit
                                                                  </th>
                                                                  <th class="px-4 py-2 text-left text-white">
                                                                      Budget Percentage
                                                                  </th>
                                                              </tr>
                                                              </thead>
                                                              <tbody>
                                                              <tr class="hover:bg-gray-100">
                                                                  <td class="px-4 py-3 border-b">
                                                                      708212 - 166 - St. Peters, MO - LPG
                                                                  </td>
                                                                  <td class="px-4 py-3 border-b">100%</td>
                                                              </tr>
                                                              </tbody>
                                                          </table>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </template>
                                      <!-- Add more user details here -->
                                      <button
                                          class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded fixed bottom-12 right-8"
                                      >
                                          View Full Job Details
                                          <i class="fa-solid fa-arrow-right ml-2"></i>
                                      </button>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Active Jobs Tab End from here -->
                      <!-- Pending Release Jobs Tab start from here -->
                      <div x-show="activeTab === 'pendingReleaseJobs'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Pending Release Jobs Tab end from here -->
                      <!-- Filled Jobs Tab start from here -->
                      <div x-show="activeTab === 'filledJobs'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Filled Jobs Tab end from here -->
                      <!-- Closed Jobs Tab start from here -->
                      <div x-show="activeTab === 'closedJobs'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },
              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Closed Jobs Tab end from here -->
                      <!-- Pending PMO Tab start from here -->
                      <div x-show="activeTab === 'pendingPMO'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Pending PMO Tab end from here -->
                      <!-- Draft Tab start from here -->
                      <div x-show="activeTab === 'draft'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- Draft Tab end from here -->
                      <!-- All Jobs Tab start from here -->
                      <div x-show="activeTab === 'allJobs'">
                          <div
                              x-data="{
              users: [
                  { id: 1, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Donna Stockton', status: 'Active', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' } },
                  { id: 2, title: 'Senior Resolution Manager', emailSignature: 'Senior Resolution Manager', jobDuration: '07/29/2024 - 01/24/2025', hiringManager: 'Don Soto', status: 'Inactive', position: '1', submission: '0', workerType: 'CW', action: { icon: 'fa-regular fa-eye', link: '#' }   },
                  { id: 3, title: 'Claims Services Representative', emailSignature: 'Claims Service Representative', jobDuration: '07/29/2024 - 01/24/2025	', hiringManager: 'Julie Hommowun', status: 'Pending', position: '1', submission: '0', workerType: 'CW' , action: { icon: 'fa-regular fa-eye', link: '#' }  },

              ],
              currentPage: 1,
              itemsPerPage: 20,
              search: '',
              filteredUsers() {
                  return this.users.filter(user =>
                      user.title.toLowerCase().includes(this.search.toLowerCase()) ||
                      user.emailSignature.toLowerCase().includes(this.search.toLowerCase())
                  );
              },
              paginatedUsers() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                const end = start + this.itemsPerPage;
                return this.filteredUsers().slice(start, end);
            },
            totalPages() {
                return Math.ceil(this.filteredUsers().length / this.itemsPerPage);
            }
          }"
                          >
                              <!-- Main Content -->
                              <main class="py-6 sm:px-6 lg:px-8">
                                  <!-- Search and Add User -->
                                  <div class="mb-4 flex justify-between">
                                      <div class="relative">
                                          <input
                                              type="text"
                                              x-model="search"
                                              placeholder="Search users"
                                              class="pl-10 pr-4 py-2 border rounded-md"
                                          />
                                          <div
                                              class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                          >
                                              <i class="fas fa-search text-gray-400"></i>
                                          </div>
                                      </div>
                                      <button
                                          class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                      >
                                          Export Sheet
                                      </button>
                                  </div>

                                  <!-- User Table -->
                                  <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                      <table class="min-w-full divide-y divide-gray-200">
                                          <thead class="bg-gray-50">
                                          <tr>
                                              <!-- Status -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Status
                                              </th>
                                              <!-- User -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job ID
                                              </th>
                                              <!-- job -->
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job title
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Title for Email Signature
                                              </th>

                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Job Duration
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hiring Manager
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Position
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Submission
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Hired
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Worker Type
                                              </th>
                                              <th
                                                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                              >
                                                  Action
                                              </th>
                                          </tr>
                                          </thead>
                                          <tbody class="bg-white divide-y divide-gray-200">
                                          <template
                                              x-for="user in paginatedUsers()"
                                              :key="user.id"
                                          >
                                              <tr>
                                                  <!-- Status -->
                                                  <td class="px-6 py-4 text-center whitespace-nowrap">
                              <span
                                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                  :class="{
                                                      'bg-green-100 text-green-800': user.status === 'Active',
                                                      'bg-red-100 text-red-800': user.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': user.status === 'Pending'
                                                  }"
                                  x-text="user.status"
                              ></span>
                                                  </td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.id"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.title"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.emailSignature"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.jobDuration"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.hiringManager"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.position"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.submission"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                      x-text="user.workerType"
                                                  ></td>
                                                  <td
                                                      class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-500"
                                                  >
                                                      <a
                                                          :href="user.action.link"
                                                          class="hover:text-indigo-900"
                                                      >
                                                          <i :class="user.action.icon"></i>
                                                      </a>
                                                  </td>
                                              </tr>
                                          </template>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div>
                                      <div class="mt-4 flex justify-between items-center">
                                          <div>
                        <span class="text-sm text-gray-700">
                          Showing
                          <span
                              class="font-medium"
                              x-text="((currentPage - 1) * itemsPerPage) + 1"
                          ></span>
                          to
                          <span
                              class="font-medium"
                              x-text="Math.min(currentPage * itemsPerPage, filteredUsers().length)"
                          ></span>
                          of
                          <span
                              class="font-medium"
                              x-text="filteredUsers().length"
                          ></span>
                          results
                        </span>
                                          </div>
                                          <div>
                                              <nav
                                                  class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                  aria-label="Pagination"
                                              >
                                                  <button
                                                      @click="currentPage = Math.max(1, currentPage - 1)"
                                                      :disabled="currentPage === 1"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Previous</span>
                                                      <i class="fas fa-chevron-left"></i>
                                                  </button>
                                                  <template x-for="pageNumber in totalPages()">
                                                      <button
                                                          @click="currentPage = pageNumber"
                                                          :class="{'bg-blue-50 border-blue-500 text-blue-600': currentPage === pageNumber, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== pageNumber}"
                                                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                      >
                                                          <span x-text="pageNumber"></span>
                                                      </button>
                                                  </template>
                                                  <button
                                                      @click="currentPage = Math.min(totalPages(), currentPage + 1)"
                                                      :disabled="currentPage === totalPages()"
                                                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                                  >
                                                      <span class="sr-only">Next</span>
                                                      <i class="fas fa-chevron-right"></i>
                                                  </button>
                                              </nav>
                                          </div>
                                      </div>
                                  </div>
                              </main>
                          </div>
                      </div>
                      <!-- All Jobs Tab end from here -->
                  </div>
              </div>
          </div>
      </div>
    </div>
@endsection
