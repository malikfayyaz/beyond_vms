<div
                    x-show="submissionDetails !== null"
                    @click="submissionDetails = null"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black bg-opacity-50 z-50"
                  >
</div>
<div
                    x-show="submissionDetails !== null"
                    @click.stop
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="transform translate-x-full"
                    x-transition:enter-end="transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="transform translate-x-0"
                    x-transition:leave-end="transform translate-x-full"
                    class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24"
                  >
                    <template x-if="submissionDetails">
                      <div>
                        <div
                          class="flex justify-between items-center p-4 bg-gray-800 text-white"
                        >
                          <h2 class="text-lg font-semibold">
                          Submission:
                            <span x-text="submissionDetails.data.title"></span> (<span
                              x-text="submissionDetails.data.id"
                            ></span
                            >)
                          </h2>

                          <button
                            @click="submissionDetails = null"
                            class="text-white bg-transparent"
                          >
                            <i class="fas fa-times"></i>
                          </button>
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
                                Submission Info
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
                                    Submission Status:
                                    </td>
                                    <td class="px-4 py-3 border-b">
                                      <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="{
                                                      'bg-green-100 text-green-800': submissionDetails.data.subStatus === 'Active',
                                                      'bg-red-100 text-red-800': submissionDetails.data.subStatus === 'Inactive',
                                                      'bg-red-100 text-red-800': submissionDetails.data.subStatus === 'Rejected',
                                                      'bg-yellow-100 text-yellow-800': submissionDetails.data.subStatus === 'Pending'
                                                  }"
                                        x-text="submissionDetails.data.subStatus"
                                      ></span>
                                    </td>
                                  </tr>
                                  <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-3 border-b">
                                      Vendor:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.vendor"></td>
                                  </tr>
                                  <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-3 border-b">
                                      Email:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.email"></td>
                                  </tr>
                                  <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-3 border-b">
                                      Location:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.location">
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
                                Bill Rate ( For Vendor)
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
                                      Bill Rate:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.vendor_rate"></td>
                                  </tr>
                                  <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-3 border-b">
                                        Over Time Rate:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.overtimer_rate"></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>


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
                                Bill Rate ( For Client)
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
                                        Bill Rate:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.client_rate"></td>
                                  </tr>
                                  <tr class="hover:bg-gray-100">
                                    <td class="px-4 py-3 border-b">
                                    Over Time Rate:
                                    </td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.overtimer_rate"></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                       
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
                                Assignments
                              </h2>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                              <table
                                class="w-full bg-white shadow-white shadow-md rounded-b-lg overflow-hidden"
                              >
                                <tbody>
                                  <tr class="hover:bg-gray-100">
                                    
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.contract_title"></td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.date_range"></td>
                                    <td class="px-4 py-3 border-b" x-text="submissionDetails.data.contract_status"></td>
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
                      View Full Submission Details
                      <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                  </div>