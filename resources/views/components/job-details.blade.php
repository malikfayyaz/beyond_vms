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
                            <span x-text="selectedUser.data.title"></span> (<span
                              x-text="selectedUser.data.id"
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
                            <span x-text="selectedUser.data.hiringManager"></span> on
                            <span x-text="selectedUser.data.jobDuration"></span>
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
                                                      'bg-green-100 text-green-800': selectedUser.data.status === 'Active',
                                                      'bg-red-100 text-red-800': selectedUser.data.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': selectedUser.data.status === 'Pending'
                                                  }"
                                        x-text="selectedUser.data.status"
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
                                        x-text="selectedUser.data.jobDuration"
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