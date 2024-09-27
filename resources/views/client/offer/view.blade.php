@extends('client.layouts.app')

@section('content')
    <!-- Sidebar -->
    @include('client.layouts.partials.dashboard_side_bar')

    <div class="ml-16">
        @include('client.layouts.partials.header')
        <div class="bg-white mx-4 my-8 rounded p-8">
          <div class="mb-8">
            <div class="w-full">
              <!-- <div class="w-full space-y-4">
                <div class="shadow-[0_3px_10px_rgb(248,113,113,0.2)] bg-red-400 rounded w-full p-6">
                  <div class="flex gap-6 items-center">
                        <ul class="text-white">
                        <li>
                            <span class="font-bold">Reason for Withdrawn:</span>
                            Rate is Incorrect
                        </li>
                        <li>
                            <span class="font-bold">Notes:</span>
                            Testing notes
                        </li>
                        <li>
                            <span class="font-bold">Withdrawn By:</span>
                            System Admin
                        </li>
                        <li>
                            <span class="font-bold">Withdrawn Date & Time:</span>
                            08/15/2024 03:52 PM
                        </li>
                        </ul>
                  </div>
                </div>
              </div> -->
              <div
                class="p-[30px] rounded border mt-4"
                :style="{'border-color': 'var(--primary-color)'}"
              >
                <div class="mb-4 flex items-center gap-2">
                  <i
                    class="fa-regular fa-square-check"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>
                  <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                  >
                    Offer Workflow
                  </h2>
                </div>
                <div class="bg-white shadow rounded-lg">
                  <div class="overflow-hidden">
                    <table class="w-full">
                      <thead>
                        <tr class="bg-gray-50 text-left">
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            S.NO
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approver Name
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approver Type
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approved/Rejected By
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approved/Rejected Date & Time
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approval Notes
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Approval Document
                          </th>
                          <th
                            class="py-4 px-4 text-center font-semibold text-sm text-gray-600"
                          >
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-gray-200">
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">1</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">2</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">3</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">4</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">5</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">6</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                        <tr>
                          <td class="py-4 px-4 text-center text-sm">7</td>
                          <td class="py-4 px-4 text-center text-sm">
                            A James Jardanowski
                          </td>
                          <td class="py-4 px-4 text-center text-sm">
                            Hiring Manager
                          </td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                          <td class="py-4 px-4 text-center text-sm"></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="flex w-full gap-4 mt-4">
                <!-- Left Column -->
                <div
                  class="w-1/2 p-[30px] rounded border"
                  :style="{'border-color': 'var(--primary-color)'}"
                >
                  <h3 class="flex items-center gap-2 mb-4">
                    <i
                      class="fa-regular fa-address-card"
                      :style="{'color': 'var(--primary-color)'}"
                    ></i
                    ><span :style="{'color': 'var(--primary-color)'}"
                      >Offer Details (Offer ID:4713)</span
                    >
                  </h3>
                  <div class="flex flex-col">
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Status:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">
                          <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-white bg-purple-400"
                            >{{$offer->status}}</span
                          >
                        </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Contractor Name:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light capitalize font-semibold">
                          <a href="#" class="text-blue-400">{{$offer->consultant->full_name}}</a>
                        </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Hiring Manager:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->hiringManager->fullname}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">
                          Timesheet Approving Manager:
                        </h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->hiringManager->full_name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Vendor:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">
                          {{$offer->vendor->full_name}}
                        </p>
                      </div>
                    </div>
                    
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Division:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->division->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Region:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->regionZone->name}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Remote:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->remote_option}}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Job Profile:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">
                          <a href="#" class="text-blue-400 font-semibold"
                            >{{$offer->careerOpportunity->title}} ({{$offer->careerOpportunity->id}})</a
                          >
                        </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Job Duration:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">{{$offer->careerOpportunity->date_range }}</p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">
                          Job Budget (All Resources Cost):
                        </h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light">${{$offer->careerOpportunity->all_resources_total_cost}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Client Billable:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->careerOpportunity->client_billable}} </p>
                      </div>
                    </div>
                    <!-- Offer Dates and Location -->
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <h3 class="flex items-center gap-2">
                        <i
                          class="fa-regular fa-address-card"
                          :style="{'color': 'var(--primary-color)'}"
                        ></i
                        ><span :style="{'color': 'var(--primary-color)'}"
                          >Offer Dates and Location</span
                        >
                      </h3>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Start Date:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->start_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">End Date:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->end_date}} </p>
                      </div>
                    </div>
                    <div
                      class="flex items-center justify-between py-4 border-t"
                    >
                      <div class="w-2/4">
                        <h4 class="font-medium">Location:</h4>
                      </div>
                      <div class="w-2/4">
                        <p class="font-light"> {{$offer->CareerOpportunity->location->LocationDetails}}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Right Column -->
                <div
                  class="w-1/2 p-[30px] rounded border"
                  :style="{'border-color': 'var(--primary-color)'}"
                >
                  <h3 class="flex items-center gap-2 mb-4">
                    <i
                      class="fa-regular fa-money-bill-1"
                      :style="{'color': 'var(--primary-color)'}"
                    ></i
                    ><span :style="{'color': 'var(--primary-color)'}"
                      >Offer Rates</span
                    >
                  </h3>
                  <div class="flex items-center justify-between py-4 border-t">
                    <h3 class="flex items-center gap-2">
                      <i
                        class="fa-solid fa-cash-register"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i
                      ><span :style="{'color': 'var(--primary-color)'}"
                        >Bill Rate (For Vendor)</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">Bill Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->offer_bill_rate}} </p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <div class="w-2/4">
                      <h4 class="font-medium">Over Time Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->over_time}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-t">
                    <h3 class="flex items-center gap-2">
                      <i
                        class="fa-solid fa-cash-register"
                        :style="{'color': 'var(--primary-color)'}"
                      ></i
                      ><span :style="{'color': 'var(--primary-color)'}"
                        >Bill Rate (For Vendor)</span
                      >
                    </h3>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Bill Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_bill_rate}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Over Time Rate:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->vendor_overtime}}</p>
                    </div>
                  </div>
                  <div class="flex items-center justify-between py-4 border-y">
                    <div class="w-2/4">
                      <h4 class="font-medium">Regular Hours Estimated Cost:</h4>
                    </div>
                    <div class="w-2/4">
                      <p class="font-light">${{$offer->careerOpportunity->regular_hours_cost}} </p>
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="p-[30px] rounded border mt-4"
                :style="{'border-color': 'var(--primary-color)'}"
              >
                <div class="mb-4 flex items-center gap-2">
                  <i
                    class="fa-solid fa-clock-rotate-left"
                    :style="{'color': 'var(--primary-color)'}"
                  ></i>
                  <h2
                    class="text-xl font-bold"
                    :style="{'color': 'var(--primary-color)'}"
                  >
                    Offer History
                  </h2>
                </div>
                <div x-data="catalogTable()">
                  <table
                    class="min-w-full bg-white shadow-md rounded-lg overflow-hidden"
                  >
                    <thead class="bg-gray-200 text-gray-700">
                      <tr>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th
                          @click="sort('catalogName')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Offer ID
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'catalogName' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'catalogName' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('category')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Contractor Name
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'category' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'category' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('profileWorkerType')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Job ID
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'profileWorkerType' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'profileWorkerType' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Hiring Manager
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Vendor
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Offer Date
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Bill Rate
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Workorder Status
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th
                          @click="sort('status')"
                          class="py-3 px-4 text-left cursor-pointer"
                        >
                          Worker Type
                          <span class="ml-1">
                            <i
                              class="fas fa-sort-up"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'asc'}"
                            ></i>
                            <i
                              class="fas fa-sort-down"
                              :class="{'text-blue-500': sortColumn === 'status' && sortDirection === 'desc'}"
                            ></i>
                          </span>
                        </th>
                        <th class="py-3 px-4 text-left">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="item in paginatedItems" :key="item.id">
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                          <td class="py-3 px-4" x-text="item.id"></td>
                          <td class="py-3 px-4" x-text="item.catalogName"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td class="py-3 px-4" x-text="item.category"></td>
                          <td
                            class="py-3 px-4"
                            x-text="item.profileWorkerType"
                          ></td>
                          <td class="py-3 px-4" x-text="item.status"></td>
                          <td class="py-3 px-4">
                            <button
                              class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-eye"></i>
                            </button>
                            <button
                              class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-edit"></i>
                            </button>
                            <button
                              @click="deleteItem(item.id)"
                              class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent"
                            >
                              <i class="fas fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>
      function catalogTable() {
        return {
          items: Array.from({ length: 200 }, (_, i) => ({
            id: i + 1,
            catalogName: `Catalog ${String.fromCharCode(65 + (i % 26))}`,
            category: ["Electronics", "Books", "Clothing", "Home", "Sports"][
              i % 5
            ],
            profileWorkerType: ["Full-time", "Part-time", "Contract"][i % 3],
            status: i % 2 === 0 ? "Active" : "Inactive",
          })),
          sortColumn: "id",
          sortDirection: "asc",
          itemsPerPage: 10,
          customItemsPerPage: 10,
          currentPage: 1,
          itemsPerPageControl() {
            return `
                          <div class="flex items-center gap-4">
                              <label for="itemsPerPage" class="mr-2">Items per page:</label>
                              <select id="itemsPerPage" x-model="itemsPerPage" @change="updatePagination()" class="border rounded px-2 py-1 mr-2">
                                  <option>10</option>
                                  <option>20</option>
                                  <option>30</option>
                                  <option>40</option>
                                  <option>50</option>
                                  <option>100</option>
                                  <option value="custom">Custom</option>
                              </select>
                              <div x-show="itemsPerPage === 'custom'" class="flex items-center">
                                  <input 
                                      type="number" 
                                      x-model.number="customItemsPerPage" 
                                      @input="updateCustomPagination()"
                                      min="1"
                                      class="border rounded px-2 py-1 w-20"
                                      placeholder="Enter"
                                  >
                              </div>
                          <div>
                      `;
          },
          sort(column) {
            if (this.sortColumn === column) {
              this.sortDirection =
                this.sortDirection === "asc" ? "desc" : "asc";
            } else {
              this.sortColumn = column;
              this.sortDirection = "asc";
            }
          },
          getItemsPerPage() {
            return this.itemsPerPage === "custom"
              ? this.customItemsPerPage
              : parseInt(this.itemsPerPage);
          },
          get sortedItems() {
            return [...this.items].sort((a, b) => {
              let modifier = this.sortDirection === "asc" ? 1 : -1;
              if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier;
              if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier;
              return 0;
            });
          },
          get paginatedItems() {
            const startIndex = (this.currentPage - 1) * this.getItemsPerPage();
            return this.sortedItems.slice(
              startIndex,
              startIndex + this.getItemsPerPage()
            );
          },
          get totalPages() {
            return Math.ceil(this.items.length / this.getItemsPerPage());
          },
          get visiblePageNumbers() {
            const totalPageCount = this.totalPages;
            const current = this.currentPage;
            let start = Math.max(1, current - 3);
            let end = Math.min(totalPageCount, start + 7);

            if (end - start < 7) {
              start = Math.max(1, end - 7);
            }

            return Array.from({ length: end - start + 1 }, (_, i) => start + i);
          },
          get showBackwardIcon() {
            return this.currentPage > 4;
          },

          get showForwardIcon() {
            return this.currentPage < this.totalPages - 3;
          },

          movePages(direction) {
            const newPage = this.currentPage + direction * 1;
            this.goToPage(Math.max(1, Math.min(newPage, this.totalPages)));
          },
          prevPage() {
            if (this.currentPage > 1) {
              this.currentPage--;
            }
          },
          nextPage() {
            if (this.currentPage < this.totalPages) {
              this.currentPage++;
            }
          },
          goToPage(page) {
            this.currentPage = page;
          },
          deleteItem(id) {
            this.items = this.items.filter((item) => item.id !== id);
            this.updatePagination();
          },
          updatePagination() {
            this.currentPage = 1;
          },
          updateCustomPagination() {
            if (this.customItemsPerPage < 1) {
              this.customItemsPerPage = 1;
            }
          },
          applyCustomPagination() {
            this.itemsPerPage = "custom";
            this.updatePagination();
          },
        };
      }
    </script>
@endsection