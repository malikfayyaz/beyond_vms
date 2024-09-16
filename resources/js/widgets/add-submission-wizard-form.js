import {
  errorMessages,
  getErrorMessageById,
  isFieldValid,
} from "./validationMessages.js";

export default function addSubWizarForm() {
  return {
    currentStep: 1,
    showErrors: false,
    showSuccessMessage: false,
    formSubmitted: false,
    // Validation Error Messages start from here
    errorMessages,
    getErrorMessageById(id) {
      if (id === "payRate") {
        return `Please enter a valid pay rate greater than 0.00 and not exceeding ${this.formData.exceedBillRate}`;
      } else if (id === "billRate") {
        return `Please enter a valid bill rate greater than the pay rate and not exceeding ${this.formData.exceedBillRate}`;
      }
      return getErrorMessageById(id, this.errorMessages);
    },
    isFieldValid(id) {
      if (id === "payRate") {
        return this.isValidPayRate(this.formData.payRate);
      } else if (id === "billRate") {
        return this.isValidBillRate(this.formData.billRate);
      } else if (
        [
          "gender",
          "race",
          "workLocation",
          "preferredName",
          
          "supplierAccountManager",
          "availableDate",
          "needSponsorship",
          "workedForGallagher",
          "gallagherCapacity",
        ].includes(id)
      ) {
        return this.formData[id] && this.formData[id].trim() !== "";
      } else if (id === "candidateEmail") {
        return this.isValidEmail(this.formData.candidateEmail);
      } else if (id === "phoneNumber") {
        return (
          this.formData.phoneNumber === "" ||
          this.isValidPhone(this.formData.phoneNumber)
        );
      } else if (id === "resumeUpload") {
        return this.formData.resumeUpload !== null;
      } else if (id === "gallagherStartDate" || id === "gallagherLastDate") {
        return this.formData[id] && this.formData[id].trim() !== "";
      } else if (
        [
          "willingToCommute",
          "virtualRemoteCandidate",
          "rightToRepresent",
          "virtualCity",
          "virtualState",
        ].includes(id)
      ) {
        if (id === "virtualCity" || id === "virtualState") {
          return this.formData.virtualRemoteCandidate === "yes"
            ? this.formData[id] && this.formData[id].trim() !== ""
            : true;
        }
        return this.formData[id] && this.formData[id].trim() !== "";
      }
      // Add any other specific field validations here
      return true; // Default to true for fields without specific validation
    },
    // Validation Error Messages end from here

    steps: [
      "New Candidate/Existing",
      "Rates Information",
      "Candidate Information",
      "Other Information",
    ],
    highestStepReached: 1,

    // Form Data
    formData: {
      candidateType: "",
      candidateSelection: "",
      dobDate: "",
      lastFourNationalId: "",
      payRate: "0.00",
      billRate: "0.00",
      exceedBillRate: "75.00",
      preferredName: "",
      gender: "",
      race: "",
      adjustedMarkup:"0.00",
      jobId : "",
      vendorMarkup:"0.00",
      workLocation: "",
      preferredLanguage: "",
      candidateEmail: "",
      phoneNumber: "",
      supplierAccountManager: "",
      resumeUpload: null,
      additionalDocUpload: null,
      availableDate: "",
      needSponsorship: "",
      workedForGallagher: "",
      gallagherCapacity: "",
      gallagherStartDate: "",
      gallagherLastDate: "",
      willingToCommute: "",
      virtualRemoteCandidate: "",
      rightToRepresent: "",
      virtualCity: "",
      virtualState: "",
      availToInterviewNotes: "",
      comment: "",
    },

    // selectedSkills: [],

    // addSkill() {
    //   this.formData.skills = [
    //     ...new Set([...this.formData.skills, ...this.selectedSkills]),
    //   ];
    //   this.selectedSkills = [];
    //   this.$nextTick(() => {
    //     $("#skills").val(null).trigger("change");
    //   });
    // },

    // removeSkill(skill) {
    //   this.formData.skills = this.formData.skills.filter((s) => s !== skill);
    // },
    handleResumeUpload(event) {
      const file = event.target.files[0];
      this.formData.resumeUpload = file || null;
    },

    handleAdditionalDocUpload(event) {
      const file = event.target.files[0];
      this.formData.additionalDocUpload = file || null;
    },

    errors: {
      payRate: "",
      billRate: "",
    },
    // Formate National ID Last 4 digits
    formatNationalId() {
      // Remove any non-digit characters
      this.formData.lastFourNationalId =
        this.formData.lastFourNationalId.replace(/\D/g, "");

      // Limit to 4 digits
      if (this.formData.lastFourNationalId.length > 4) {
        this.formData.lastFourNationalId =
          this.formData.lastFourNationalId.slice(0, 4);
      }
    },
    isValidNationalId(value) {
      return /^\d{4}$/.test(value);
    },

    // Rates fields of step 2

    formatExceedBillRate(value) {
      this.formData.exceedBillRate = this.formatRateValue(value);
      this.validateAllRates();
    },

    formatPayRate(value) {
      this.formData.payRate = this.formatRateValue(value);
      this.validateAllRates();
    },

    formatBillRate(value) {
      this.formData.billRate = this.formatRateValue(value);
      console.log(this.formData.billRate );
      
      this.validateAllRates();
    },

    formatRateValue(value) {
      let numeric = value.replace(/[^\d.]/g, "");
      let parts = numeric.split(".");
      if (parts.length > 2) {
        numeric = parts[0] + "." + parts.slice(1).join("");
      }
      return parseFloat(numeric || 0).toFixed(2);
    },

    validateAllRates() {
      this.validateBillRate();
      this.validatePayRate();
      
    },

    validatePayRate() {
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      const payRate = parseFloat(this.formData.payRate);

      if (payRate > exceedBillRate) {
        this.errors.payRate = `Pay Rate cannot exceed ${this.formData.exceedBillRate}`;
      } else if (payRate <= 0) {
        this.errors.payRate = "Pay Rate must be greater than 0.00";
      } else {
        this.errors.payRate = "";
      }
    },

    validateBillRate() {
      this.errors.billRate =  "";
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      const payRate = parseFloat(this.formData.payRate);
      const billRate = parseFloat(this.formData.billRate);
      console.log(billRate+"dsfsdfsdf");
      console.log(payRate+"payRate");
      if (billRate > payRate) {
        this.errors.billRate = `Bill Rate must be at least ${this.formData.payRate}`;
      } else if (billRate > exceedBillRate) {
        this.errors.billRate = `Bill Rate cannot exceed ${this.formData.exceedBillRate}`;
      } else if (billRate <= 0) {
        this.errors.billRate = "Bill Rate must be greater than 0.00";
      } else {
        this.errors.billRate = "";
      }
    },

    isValidPayRate(value) {
      const payRate = parseFloat(value);
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      return payRate > 0 && payRate <= exceedBillRate;
    },

    isValidBillRate(value) {
      const billRate = parseFloat(value);
      const payRate = parseFloat(this.formData.payRate);
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      return billRate > 0 && billRate >= payRate && billRate <= exceedBillRate;
    },

    validateRates() {
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      const payRate = parseFloat(this.formData.payRate);

      // Validate Pay Rate
      if (payRate > exceedBillRate) {
        this.errors.payRate = `Pay Rate cannot exceed ${this.formData.exceedBillRate}`;
      } else {
        this.errors.payRate = "";
      }

      // Validate Bill Rate
      this.validateBillRate();
    },

    isValidRate(value) {
      return /^\d+(\.\d{2})?$/.test(value) && parseFloat(value) > 0;
    },
    // validateBillRate() {
    //   const exceedBillRate = parseFloat(this.formData.exceedBillRate);
    //   const payRate = parseFloat(this.formData.payRate);
    //   const billRate = parseFloat(this.formData.billRate);

    //   if (billRate < payRate) {
    //     this.errors.billRate = `Bill Rate must be at least ${this.formData.payRate}`;
    //   } else if (billRate > exceedBillRate) {
    //     this.errors.billRate = `Bill Rate cannot exceed ${this.formData.exceedBillRate}`;
    //   } else {
    //     this.errors.billRate = "";
    //   }
    // },

    isValidPayRate(value) {
      return this.isValidRate(value);
    },

    isValidBillRate(value) {
      const billRate = parseFloat(value);
      const payRate = parseFloat(this.formData.payRate);
      const exceedBillRate = parseFloat(this.formData.exceedBillRate);
      return (
        this.isValidRate(value) &&
        billRate >= payRate &&
        billRate <= exceedBillRate
      );
    },

    // Format Phone Number
    formatPhoneNumber() {
      let phoneNumber = this.formData.phoneNumber.replace(/\D/g, "");
      if (phoneNumber.length > 10) {
        phoneNumber = phoneNumber.slice(0, 10);
      }
      if (phoneNumber.length >= 6) {
        phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(
          4,
          7
        )}-${phoneNumber.slice(7)}`;
      } else if (phoneNumber.length >= 4) {
        phoneNumber = `(${phoneNumber.slice(0, 4)}) ${phoneNumber.slice(4)}`;
      }
      this.formData.phoneNumber = phoneNumber;
    },

    isValidPhone(phone) {
      return /^\(\d{4}\)\s\d{3}-\d{4}$/.test(phone);
    },

    isValidPayRate(rate) {
      return this.isValidBillRate(rate);
    },

    // formatBillRate(value) {
    //   this.formData.billRate = this.formatBillingValue(value);
    // },

    // formatMaxBillRate(value) {
    //   this.formData.maxBillRate = this.formatBillingValue(value);
    // },

    formatEstimatedExpense(value) {
      this.formData.estimatedExpense = this.formatBillingValue(value);
    },

    isValidEstimatedExpense(value) {
      return this.isValidBillRate(value);
    },

    formatBillingValue(value) {
      // Remove non-numeric characters
      let numeric = value.replace(/[^\d.]/g, "");

      // Ensure only one decimal point
      let parts = numeric.split(".");
      if (parts.length > 2) {
        numeric = parts[0] + "." + parts.slice(1).join("");
      }

      // Format to two decimal places
      return parseFloat(numeric || 0).toFixed(2);
    },

    isValidBillRate(value) {
      return /^\d+(\.\d{2})?$/.test(value) && parseFloat(value) > 0;
    },

    isValidMaxBillRate(value) {
      return (
        this.isValidBillRate(value) &&
        parseFloat(value) >= parseFloat(this.formData.billRate)
      );
    },

    formatCost(field) {
      this.formData[field] = this.formatBillingValue(this.formData[field]);
    },

    showErrors: false,
    mounted() {
      // console.log(window.$); // Verify jQuery is available
      if (window.$) {
        $('#candidateSelection').on('change', () => {
          this.loadExistingCandidate();
        });
        const myObject = {
          disabledFields: (disable = true) => {
          // Empty the input values and clear formData
          if (!disable) {
            $('#candidateSelection').val('').trigger("change.select2");
            this.formData.candidateSelection = '';
              $('#candidateFirstName').val('');
              $('#candidateMiddleName').val('');
              $('#candidateLastName').val('');
              $('#dobDate').val('');
              $('#lastFourNationalId').val('');
              // console.log("dsfsdf");
              
              this.formData.candidateFirstName = '';
              this.formData.candidateMiddleName = '';
              this.formData.candidateLastName = '';
              this.formData.dobDate = '';
              this.formData.lastFourNationalId = '';
          }
          
          // Disable or enable the fields based on the parameter
          $('#candidateFirstName').prop('disabled', disable);
          $('#candidateMiddleName').prop('disabled', disable);
          $('#candidateLastName').prop('disabled', disable);
          $('#dobDate').prop('disabled', disable);
          $('#lastFourNationalId').prop('disabled', disable);
      }
      };

      $('#candidateType').on('change', function() {
        
        if($(this).val() == 1) {
          myObject.disabledFields(false);
        }
    });
      
        this.loadExistingCandidate = () => {
          var candidate_id = $('#candidateSelection').find(':selected').val();
          myObject.disabledFields(true);
         
          let url = `/consultant-id`;

          if (candidate_id != '') {
            let data = new FormData();
            data.append('candidate_id', candidate_id);
            const updates = {
              '#candidateFirstName': { type: 'value', field: 'candidateFirstName' },
              '#candidateMiddleName': { type: 'value', field: 'candidateMiddleName' },
              '#candidateLastName': { type: 'value', field: 'candidateLastName' },
              '#dobDate': { type: 'date', field: 'dobDate' },
              '#lastFourNationalId': { type: 'value', field: 'lastFourNationalId' },
              // Add more mappings as needed
            };
            ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
            setTimeout(() => {
              
             this.formData.candidateFirstName =  $('#candidateFirstName').val();
             this.formData.candidateMiddleName = $('#candidateMiddleName').val();
             this.formData.candidateLastName = $('#candidateLastName').val();
             this.formData.dobDate = $('#dobDate').val();
             this.formData.lastFourNationalId = $('#lastFourNationalId').val();
            //  console.log(this.formData);
             
            }, 500);
          }
        };

        $('#billRate,#payRate').on('change', (event) => {
          // Ensure formData is accessible
          let formData = this.formData || {};
          
          let payRate = formData.payRate || $('#payRate').val();
          let billRate = formData.billRate || $('#billRate').val();
          let adjustedMarkup = formData.adjustedMarkup || $('#adjustedMarkup').val();
          let markup = formData.vendorMarkup || $('#vendorMarkup').val();
          let jobid = formData.jobid || $('#jobid').val();
          let url = `/show-vendor-markup`;
          let data = new FormData();
      
          data.append('payRate', payRate);
          data.append('billRate', billRate);
          data.append('adjustedMarkup', adjustedMarkup);
          data.append('markup', markup);
          data.append('jobid', jobid);
      
          var rateField = '';
          if ($(event.target).attr('id') == 'billRate') {
              rateField = 'bill_rate_changed';
          }
          if ($(event.target).attr('id') == 'VendorJobSubmission_adjusted_makrup') {
              rateField = 'adjusted_markup_changed';
          }
          data.append('rateField', rateField);
      
          const updates = {
              '#adjustedMarkup': { type: 'value', field: 'adjustedMarkup' },
              // Add more mappings as needed
          };
      
          ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
      
          setTimeout(() => {
              formData.adjustedMarkup = $('#adjustedMarkup').val();
              // console.log(formData.adjustedMarkup);
          }, 500);
      });
      
      }
    },

    

    

    initSelect2() {
      this.$nextTick(() => {
        $(".select2-single").each((index, element) => {
          const fieldName = $(element).data("field");
          $(element)
            .select2({
              width: "100%",
            })
            .on("select2:select", (e) => {
              this.formData[fieldName] = e.params.data.id;
            })
            .on("select2:unselect", () => {
              this.formData[fieldName] = "";
            });

          $("#skills")
            .select2({
              width: "100%",
              tags: true,
              tokenSeparators: [",", " "],
            })
            .on("change", (e) => {
              this.selectedSkills = $(e.target).val() || [];
            });
        });
      });
    },

    init() {
      this.initSelect2();
      this.initFlatpickr();
    },

    initFlatpickr() {
      this.$nextTick(() => {
        // Get the user's locale
        const userLocale = navigator.language || navigator.userLanguage;

        // Function to detect system date format
        function detectDateFormat() {
          const options = { year: "numeric", month: "2-digit", day: "2-digit" };
          const formatter = new Intl.DateTimeFormat(userLocale, options);
          const parts = formatter.formatToParts(new Date(2000, 0, 2)); // January 2, 2000
          let format = "";
          let separator = "";
          parts.forEach((part) => {
            if (part.type === "literal") {
              separator = part.value;
            } else {
              switch (part.type) {
                case "year":
                  format += "Y";
                  break;
                case "month":
                  format += "m";
                  break;
                case "day":
                  format += "d";
                  break;
              }
            }
          });
          return format.split("").join(separator);
        }

        // Detect the system date format
        const systemDateFormat = detectDateFormat();
        // console.log("Detected system date format:", systemDateFormat);

        this.flatpickrInstance = flatpickr("#dobDate", {
          dateFormat: systemDateFormat,
          altInput: true,
          altFormat: systemDateFormat,
          maxDate: "today",
          onChange: (selectedDates, dateStr) => {
            this.formData.dobDate = dateStr;
          },
          onReady: (selectedDates, dateStr, instance) => {
            // Force an update of the alt input to ensure it uses the correct format
            instance.setDate(instance.selectedDates[0] || new Date(), true);
          },
        });

        flatpickr(this.$refs.availableDatePicker, {
          dateFormat: "Y-m-d",
          minDate: "today",
          onChange: (selectedDates, dateStr) => {
            this.formData.availableDate = dateStr;
          },
        });

        // Gallagher Start Date Picker
        flatpickr(this.$refs.gallagherStartDatePicker, {
          dateFormat: "Y-m-d",
          onChange: (selectedDates, dateStr) => {
            this.formData.gallagherStartDate = dateStr;
          },
        });

        // Gallagher Last Date Picker
        flatpickr(this.$refs.gallagherLastDatePicker, {
          dateFormat: "Y-m-d",
          onChange: (selectedDates, dateStr) => {
            this.formData.gallagherLastDate = dateStr;
          },
        });
      });
    },

    nextStep() {
      if (!this.formSubmitted) {
        this.validateStep(this.currentStep);
        if (this.isStepValid) {
          this.showErrors = false;
          this.currentStep++;
          this.highestStepReached = Math.max(
            this.highestStepReached,
            this.currentStep
          );
        }
      }
    },

    validateStep(step) {
      this.showErrors = true;
      switch (step) {
        case 1:
          return (
            this.formData.candidateType !== "" &&
            (this.formData.candidateType !== "2" ||
              this.formData.candidateSelection !== "") &&
            this.formData.dobDate.trim() !== "" &&
            this.isValidNationalId(this.formData.lastFourNationalId)
          );
        case 2:
          return (
            this.isValidPayRate(this.formData.payRate) &&
            this.isValidBillRate(this.formData.billRate) &&
            !this.errors.payRate &&
            !this.errors.billRate
          );
        case 3:
          return (
            this.isFieldValid("preferredName") &&
            this.isFieldValid("gender") &&
            this.isFieldValid("race") &&
            this.isFieldValid("workLocation") &&
            
            this.isFieldValid("candidateEmail") &&
            this.isFieldValid("supplierAccountManager") &&
            this.isFieldValid("resumeUpload") &&
            (this.formData.phoneNumber === "" ||
              this.isFieldValid("phoneNumber"))
          );
        case 4:
          let isValid =
            this.isFieldValid("availableDate") &&
            this.isFieldValid("needSponsorship") &&
            this.isFieldValid("workedForGallagher");
          this.isFieldValid("willingToCommute") &&
            this.isFieldValid("virtualRemoteCandidate") &&
            this.isFieldValid("rightToRepresent");
          if (this.formData.workedForGallagher === "yes") {
            isValid =
              isValid &&
              this.isFieldValid("gallagherCapacity") &&
              this.isFieldValid("gallagherStartDate") &&
              this.isFieldValid("gallagherLastDate") &&
              this.isFieldValid("virtualCity");
          }
          return isValid;
        default:
          return true;
      }
    },

    goToStep(step) {
      if (!this.formSubmitted && step <= this.highestStepReached) {
        this.showErrors = false;
        this.currentStep = step;
      }
    },

    isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },

    get isStepValid() {
      return this.validateStep(this.currentStep);
    },
    get isFormValid() {
      return (
        this.validateStep(1) &&
        this.validateStep(2) &&
        this.validateStep(3) &&
        this.validateStep(4)
      );
    },
    submitForm() {
      this.showErrors = true;
      if (this.isFormValid) {
        console.log("Form submitted:", this.formData);
        let formData = new FormData();
        Object.keys(this.formData).forEach((key) => {
          if (Array.isArray(this.formData[key])) {
            // If the key is an array (like businessUnits), handle each item
            this.formData[key].forEach((item, index) => {
              formData.append(`${key}[${index}]`, JSON.stringify(item));
            });
          } else {
            formData.append(key, this.formData[key]);
          }
        });
        formData.append("additionaldoc", this.formData.additionalDocUpload);
        formData.append("resume", this.formData.resumeUpload);
        console.log(formData);
        return false;
        this.showSuccessMessage = true;
        // this.resetForm();
        // this.currentStep = 1;
        // this.highestStepReached = 1;
        // this.formSubmitted = true;
        setTimeout(() => {
          this.showSuccessMessage = false;
        }, 5000);
      } else {
        console.log("Form is invalid. Please check the errors.");
      }
    },
    resetForm() {
      this.currentStep = 1;
      this.highestStepReached = 1;
      this.showErrors = false;
      this.formSubmitted = false;
      this.formData = {
        candidateType: "",
        candidateSelection: "",
        payRate: "0.00",
        billRate: "0.00",
        preferredName: "",
        // skills: [],
       
        candidateEmail: "",
        phoneNumber: "",
        supplierAccountManager: "",
        availableDate: "",
        needSponsorship: "",
        workedForGallagher: "",
        gallagherCapacity: "",
        gallagherStartDate: "",
        gallagherLastDate: "",
        willingToCommute: "",
        virtualRemoteCandidate: "",
        rightToRepresent: "",
        virtualCity: "",
        virtualState: "",
        availToInterviewNotes: "",
        comment: "",
      };
      if (document.getElementById("resumeUpload")) {
        document.getElementById("resumeUpload").value = "";
      }
      if (document.getElementById("additionalDocUpload")) {
        document.getElementById("additionalDocUpload").value = "";
      }
      // this.selectedSkills = [];
      // Reset Select2 dropdowns
      this.$nextTick(() => {
        $(".select2-single").val(null).trigger("change");
      });
    },
  };
}
