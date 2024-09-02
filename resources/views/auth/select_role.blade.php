

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([ 'resources/css/app.css'])
    @vite(['resources/js/ajax-functions.js', 'resources/js/app.js'])
    <title>Tailwind + Alpine + Vite</title>
  </head>
  <body class="bg-gray-100">
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


      <div class="ml-16">


        <div class="bg-white mx-4 my-8 rounded p-8">
          <!-- Select Account Type -->
          <div
            x-data
            class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 pt-4"
          >
            <div
              class="w-full max-w-md bg-white shadow-md rounded-lg overflow-hidden"
            >
              <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">
                  Select Account
                </h3>
                <p class="text-sm text-gray-500 mb-1">
                  Select a type of Account
                </p>
                <p class="text-xs text-gray-400 mb-4">
                  Choose an account type, then move to next page.
                </p>
                <div class="relative mb-4">
                  <input
                    type="text"
                    x-model="$store.accountSelection.searchTerm"
                    placeholder="Search accounts..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <div
                    class="absolute left-3 top-1/2 transform -translate-y-1/2"
                  >
                    <i class="fas fa-search text-gray-400"></i>
                  </div>
                </div>
                <div class="space-y-2">
                  <template
                    x-for="account in $store.accountSelection.filteredAccounts"
                    :key="account.id"
                  >
                    <div
                      @click="$store.accountSelection.selectAccount(account)"
                      class="flex items-center p-2 rounded-lg cursor-pointer"
                      :class="{ 'bg-blue-100': $store.accountSelection.selectedAccount === account, 'hover:bg-gray-100': $store.accountSelection.selectedAccount !== account }"
                    >
                      <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                      <div
                        :class="account.color + ' w-12 h-12 rounded-full flex items-center justify-center text-white font-bold mr-3'"
                        x-text="$store.accountSelection.getInitials(account.title)"
                      ></div>
                      <div>
                        <p class="font-medium" x-text="account.title"></p>
                        <p
                          class="text-xs text-gray-500"
                          x-text="account.id"
                        ></p>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <button
                  type="button"
                  @click="$store.accountSelection.confirmSelection()"
                  :disabled="!$store.accountSelection.selectedAccount"
                  class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  :class="{ 'opacity-50 cursor-not-allowed': !$store.accountSelection.selectedAccount }"
                >
                  Confirm
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>


             // Example usage
              // Example usage


      document.addEventListener("alpine:init", () => {
        function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
        }
        const accountsObject = @json($roles);

        const accounts = Object.keys(accountsObject).map(key => ({
            type: key,
            title: capitalizeFirstLetter(key), // Set title if needed
            id: capitalizeFirstLetter(key), // Set ID if needed
            color: '' // Will be set later
        }));

            // Define colors
            const colors = [
                'bg-blue-500',
                'bg-green-500',
                'bg-yellow-500',
                'bg-purple-500',
                'bg-gray-500' // default color if needed
            ];

            // Assign colors based on the index
            accounts.forEach((account, index) => {
                account.color = colors[index] || colors[colors.length - 1];
            });
            // accounts.forEach(account => {
            //     console.log(`Type: ${account.type}, Title: ${account.title}, ID: ${account.id}, Color: ${account.color}`);
            //     // Use the account data in your application
            // });
        Alpine.store("accountSelection", {
          searchTerm: "",
          selectedAccount: null,

          accounts: accounts,
          get filteredAccounts() {
            return this.accounts.filter((account) =>
              account.title
                .toLowerCase()
                .includes(this.searchTerm.toLowerCase())
            );
          },
          getInitials(title) {
            return title
              .split(" ")
              .map((word) => word[0].toUpperCase())
              .join("");
          },
          selectAccount(account) {
            this.selectedAccount = account;
          },
          confirmSelection() {
                if (this.selectedAccount) {
                    // Log the selected account (for debugging purposes)
                    console.log("Selected account:", this.selectedAccount);

                    // Prepare the data to be sent
                    const selectedRole = this.selectedAccount.type;

                    // Send the data using Fetch API or Axios
                      // Prepare the data to be sent
                        const formData = new FormData();
                        formData.append('role', this.selectedAccount.type);



                        // Call the custom ajax function
                        ajaxCall('/selectrolepost', 'POST', [[onSuccess, ['response']]], formData);

                }
            },

        });
      });
    </script>
    <!-- @vite(['resources/js/ajax-functions.js', 'resources/js/app.js']) -->


  </body>
</html>

