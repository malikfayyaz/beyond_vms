<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/app.css" />
  </head>
  <body class="h-full bg-gray-100">
    <div class="h-screen flex items-center justify-center">
      <div
        x-data="{ 
      miniSidebar: true, 
      currentTheme: localStorage.getItem('theme') || 'theme-1',
      darkMode: localStorage.getItem('darkMode') === 'true',
      setTheme(theme) {
          this.currentTheme = theme;
          localStorage.setItem('theme', theme);
      },
      toggleDarkMode() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('darkMode', this.darkMode);
      }
    }"
        :class="[currentTheme, {'dark-mode': darkMode}]"
      >
        <!-- Sidebar -->
        <aside
          class="fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out"
          :class="{'w-64': !miniSidebar, 'w-16': miniSidebar}"
          @mouseenter="miniSidebar = false"
          @mouseleave="miniSidebar = true"
        >
          <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
            <a
              href="#"
              class="flex items-center mb-5 overflow-hidden"
              :class="{'justify-center': miniSidebar}"
            >
              <i class="fas fa-user-circle text-white text-2xl"></i>
              <span
                class="self-center text-xl font-semibold whitespace-nowrap text-white ml-2"
                x-show="!miniSidebar"
                x-cloak
                >UAT</span
              >
            </a>
            <ul class="space-y-2 font-medium">
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                  :class="{'justify-center': miniSidebar}"
                >
                  <i class="fas fa-tachometer-alt w-6 h-6"></i>
                  <span class="ml-3" x-show="!miniSidebar" x-cloak
                    >Dashboard</span
                  >
                </a>
              </li>
              <li x-data="{ open: false }">
                <button
                  @click="open = !open"
                  class="flex items-center w-full p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                  :class="{'justify-center': miniSidebar}"
                >
                  <i class="fas fa-shopping-cart w-6 h-6"></i>
                  <span
                    class="flex-1 ml-3 text-left whitespace-nowrap"
                    x-show="!miniSidebar"
                    x-cloak
                    >eCommerce</span
                  >
                  <i
                    class="fas fa-chevron-down ml-auto"
                    x-show="!miniSidebar"
                    x-cloak
                  ></i>
                </button>
                <ul x-show="open" class="py-2 space-y-2" x-cloak>
                  <li>
                    <a
                      href="#"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700"
                      >Shop</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      class="flex items-center w-full p-2 text-white transition duration-75 pl-11 hover:bg-gray-700"
                      >Details</a
                    >
                  </li>
                </ul>
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                  :class="{'justify-center': miniSidebar}"
                >
                  <i class="fas fa-envelope w-6 h-6"></i>
                  <span
                    class="flex-1 ml-3 whitespace-nowrap"
                    x-show="!miniSidebar"
                    x-cloak
                    >Email</span
                  >
                  <span
                    class="inline-flex items-center justify-center w-3 h-3 p-3 ml-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full"
                    x-show="!miniSidebar"
                    x-cloak
                    >3</span
                  >
                </a>
              </li>
              <li>
                <a
                  href="#"
                  class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 overflow-hidden"
                  :class="{'justify-center': miniSidebar}"
                >
                  <i class="fas fa-comment w-6 h-6"></i>
                  <span
                    class="flex-1 ml-3 whitespace-nowrap"
                    x-show="!miniSidebar"
                    x-cloak
                    >Chat</span
                  >
                </a>
              </li>
            </ul>
          </div>
        </aside>
      </div>
      <div class="ml-16">
        <div class="py-6 sm:px-6 lg:px-8">
          <div class="bg-white shadow overflow-hidden sm:rounded-lg py-6">
            <div  x-data="{
                isOpen: false,
                showModal: false,
                modalType: null,
                selectedUser: null,
                comment: '',
                approvers: [
                    { 
                        id: 1, 
                        name: 'Donald Campbell', 
                        initial: 'D', 
                        status: null,
                        approvedBy: null,
                        dateTime: null 
                    },
                    { 
                        id: 2, 
                        name: 'Robert Allen', 
                        initial: 'R', 
                        status: null,
                        approvedBy: null,
                        dateTime: null 
                    },
                    { 
                        id: 3, 
                        name: 'Victoria Belyak', 
                        initial: 'V', 
                        status: 'pending',
                        approvedBy: null,
                        dateTime: null 
                    },
                    { 
                        id: 4, 
                        name: 'Mark Bloom', 
                        initial: 'M', 
                        status: 'pending',
                        approvedBy: null,
                        dateTime: null 
                    },
                    { 
                        id: 5, 
                        name: 'Richard Cary', 
                        initial: 'R', 
                        status: 'pending',
                        approvedBy: null,
                        dateTime: null,
                        title: '(Backup Manager for Douglas Howell)'
                    }
                ]
            }" >
                <button 
            @click="isOpen = true; selectedUser = { 
                id: '12345',
                title: 'Software Developer',
                userName: 'John Doe',
                jobDuration: '6 months',
                status: 'Active'
            }"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
            Open Job Details
        </button>
              <div>
                <!-- Main Content -->
                <main class="py-6 sm:px-6 lg:px-8">
                  <!-- Overlay -->
        <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="isOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40"
    ></div>
                  <!-- Slide-in Window -->
                  <div
                  x-show="isOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-x-full"
            x-transition:enter-end="transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="transform translate-x-0"
            x-transition:leave-end="transform translate-x-full"
            @click.outside="isOpen = false"
            class="fixed inset-y-0 right-0 w-[700px] bg-gray-100 shadow-lg overflow-y-auto z-50 pb-24"
>
                    <template x-if="selectedUser">
                      <div>
                        <!-- Top Bar -->
                        <div
                          class="flex justify-between items-center p-4 bg-gray-800 text-white"
                        >
                        <h2 class="text-lg font-semibold">Additional Budget Request (<span x-text="selectedUser.id" ></span>)</h2>
                          <button
                            @click="selectedUser = null"
                            class="text-gray-500 hover:text-gray-700"
                          >
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                        <!-- User Details -->
                        <div class="p-4 bg-gray-200 flex justify-between items-center">
                            <div>
                                <h2 x-text="selectedUser.userName" class="text-lg font-semibold"></h2>
                                <a href="">Business Analyist (<span x-text="selectedUser.id"></span>)</a>
                            </div>
                            <div>
                                <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="{
                                                      'bg-green-100 text-green-800': selectedUser.status === 'Active',
                                                      'bg-red-100 text-red-800': selectedUser.status === 'Inactive',
                                                      'bg-yellow-100 text-yellow-800': selectedUser.status === 'Pending'
                                                  }"
                                        x-text="selectedUser.status"
                                      ></span>
                            </div>
                        </div>
                        <!-- Additional budget request details -->
                         <div class="p-4">
                            <table class="border w-full">
                                <thead>
                                <tr class="text-left border-b">
                                    <th class=" p-2">
                                    Additional Budget Request Details</th>
                                </tr>
                            </thead>
                                <tbody>
                                <tr>
                                    <td class="p-2">Request Amount	RT :</td>
                                    <td class="p-2 text-green-800 font-bold"> $20,000,000.00</td>
                                </tr>
                            </tbody>
                            </table>
                         </div>
                         <!-- Reason for Additional Budget Request & Notes -->
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

                        <!-- Table Data -->

                        <div class="container mx-auto p-6">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="text-left p-4 font-semibold">Approver Name</th>
                                        <th class="text-left p-4 font-semibold">Approved/Rejected By</th>
                                        <th class="text-left p-4 font-semibold">Date & Time</th>
                                        <th class="text-left p-4 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="approver in approvers" :key="approver.id">
                                        <tr class="border-t">
                                            <td class="p-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white mr-3">
                                                        <span x-text="approver.initial"></span>
                                                    </div>
                                                    <div>
                                                        <span x-text="approver.name"></span>
                                                        <span x-text="approver.title" class="text-gray-600 text-sm block"></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-4" x-text="approver.approvedBy || ''"></td>
                                            <td class="p-4" x-text="approver.dateTime || ''"></td>
                                            <td class="p-4">
                                                <div class="flex gap-2">
                                                    <template x-if="approver.status === 'pending'">
                                                        <span class="px-3 py-1 bg-orange-400 text-white rounded">Pending</span>
                                                    </template>
                                                    <template x-if="approver.status === null">
                                                        <div class="flex gap-2">
                                                            <button 
                                                                @click="showModal = true; modalType = 'approve'; selectedUser = approver"
                                                                class="p-2 bg-green-400 text-white rounded hover:bg-green-500"
                                                            >
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button 
                                                                @click="showModal = true; modalType = 'reject'; selectedUser = approver"
                                                                class="p-2 bg-red-400 text-white rounded hover:bg-red-500"
                                                            >
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <button class="p-2 bg-blue-400 text-white rounded hover:bg-blue-500">
                                                                <i class="fas fa-envelope"></i>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Modal -->
                        <div
                            x-show="showModal"
                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                            @click.self="showModal = false"
                        >
                            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                                <div class="flex justify-between items-center mb-4">
                                     <div>
            <h3 class="text-lg font-semibold" x-text="modalType === 'approve' ? 'Approve Request' : 'Reject Request'"></h3>
            <p class="text-sm text-gray-600">Request ID: <span x-text="selectedUser?.id || ''"></span></p>
        </div>
                                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Comments
                                    </label>
                                    <textarea 
                                        x-model="comment"
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                        rows="4"
                                    ></textarea>
                                </div>
                                
                                <div class="mb-6">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                        Attachment
                                    </label>
                                    <input 
                                        type="file" 
                                        class="w-full px-3 py-2 border rounded text-gray-700 focus:outline-none focus:shadow-outline"
                                    >
                                </div>
                                
                                <div class="flex justify-end gap-2">
                                    <button 
                                        @click="showModal = false"
                                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded"
                                    >
                                        Close
                                    </button>
                                    <button 
                                        @click="
                                            if (selectedUser) {
                                                selectedUser.status = modalType === 'approve' ? 'approved' : 'rejected';
                                                selectedUser.approvedBy = 'Current User';
                                                selectedUser.dateTime = new Date().toLocaleString();
                                                showModal = false;
                                                comment = '';
                                            }
                                        "
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded"
                                        :class="{ 'bg-green-500 hover:bg-green-600': modalType === 'approve', 'bg-red-500 hover:bg-red-600': modalType === 'reject' }"
                                    >
                                        Save
                                    </button>
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
          </div>
        </div>
      </div>
    </div>
    <script type="module" src="../assets/js/app.js"></script>
  </body>
</html>
