import {
  errorMessages,
  getErrorMessageById,
  isFieldValid,
} from "./validationMessages.js";


export default function wizardForm(careerOpportunity = null,businessUnitsData = null) {
  return {
    currentStep: 1,
    showErrors: false,
    showSuccessMessage: false,
    formSubmitted: false,
    // Validation Error Messages start from here
    errorMessages,
    getErrorMessageById(id) {
      return getErrorMessageById(id, this.errorMessages);
    },

    isFieldValid(id) {
      return isFieldValid(id, this.formData, {
        isBusinessUnitValid: () => this.isBusinessUnitValid,
        isValidBillRate: (value) => this.isValidBillRate(value),
        isValidMaxBillRate: (value) => this.isValidMaxBillRate(value),
        isValidPhone: (value) => this.isValidPhone(value),
        isValidEmail: (value) => this.isValidEmail(value),
      });
    },

    // Validation Error Messages end from here

    steps: [
      "Basic Info",
      "Duration & Description",
      "Additional Info",
      "Other Details",
    ],
    highestStepReached: 1,
    // Property to store the selected file
    attachmentFile: careerOpportunity?.attachment || null,
    // Form Data
    formData: {
      jobLaborCategory:careerOpportunity?.cat_id || "",
      jobTitle:careerOpportunity?.template_id || "",
      hiringManager:careerOpportunity?.hiring_manager || "",
      jobLevel:careerOpportunity?.job_level || "",
      workLocation:careerOpportunity?.location_id || "",
      currency:careerOpportunity?.currency_id || "",
      billRate: careerOpportunity?.min_bill_rate || "0.00",
      maxBillRate: careerOpportunity?.max_bill_rate || "0.00",
      preIdentifiedCandidate:careerOpportunity?.pre_candidate || "",
      candidateFirstName:careerOpportunity?.pre_name || "",
      candidateMiddleName:careerOpportunity?.pre_middle_name || "",
      candidateLastName:careerOpportunity?.pre_last_name || "",
      candidatePhone:careerOpportunity?.candidate_phone || "",
      candidateEmail:careerOpportunity?.candidate_email || "",
      workerPayRate: careerOpportunity?.pre_current_rate || "0.00",
      jobTitleEmailSignature:careerOpportunity?.alternative_job_title || "",
      // engageWorkerAs: careerOpportunity?.job_title || "",
      laborType: careerOpportunity?.labour_type || "",
      startDate: careerOpportunity?.start_date || "",
      endDate: careerOpportunity?.end_date || "",
      jobDescriptionEditor: careerOpportunity?.description || "",
      qualificationSkillsEditor: careerOpportunity?.skills || "",
      additionalRequirementEditor: careerOpportunity?.internal_notes || "",
      division: careerOpportunity?.division_id || "",
      regionZone: careerOpportunity?.region_zone_id || "",
      branch: careerOpportunity?.branch_id || "",
      expensesAllowed: careerOpportunity?.expenses_allowed || "",
      travelRequired: careerOpportunity?.travel_required || "",
      glCode: careerOpportunity?.gl_code_id || "",
      subLedgerType: careerOpportunity?.ledger_type_id || "",
      subLedgerCode: careerOpportunity?.ledger_code || "",
      workerType: careerOpportunity?.worker_type_id || "", // Set default value
      clientBillable: careerOpportunity?.client_billable || "",
      requireOT: careerOpportunity?.background_check_required || "",
      virtualRemote: careerOpportunity?.remote_option || "",
      estimatedExpense: careerOpportunity?.expense_cost || "0.00",
      clientName: careerOpportunity?.client_name || "",
      job_code:careerOpportunity?.job_code || "",
      businessUnits: businessUnitsData || [],
      payment_type: careerOpportunity?.payment_type || "",
      timeType: careerOpportunity?.type_of_job || "",
      estimatedHoursPerDay: careerOpportunity?.hours_per_day || "",
      workDaysPerWeek: careerOpportunity?.day_per_week || "",
      numberOfPositions: careerOpportunity?.num_openings || "",
      businessReason: careerOpportunity?.hire_reason_id || "",
      regularCost: careerOpportunity?.regular_hours_cost || "0.00",
      singleResourceCost: careerOpportunity?.single_resource_total_cost || "0.00",
      allResourcesRegularCost: careerOpportunity?.regular_hours_cost || "0.00",
      allResourcesCost: careerOpportunity?.all_resources_total_cost || "0.00",
      regularHours: careerOpportunity?.regular_hours || "0.00",
      numberOfWeeks: careerOpportunity?.hours_per_week || "0 Weeks 1 Days",
      termsAccepted: false,
    },
    selectedBusinessUnit: "",
    budgetPercentage: "",
    businessUnitErrorMessage: "",

    mounted() {
      console.log(window.$); // Verify jQuery is available
      if (window.$) {
        $('#jobLaborCategory').on('change', () => {
          var labour_type = $('#jobLaborCategory').val();
          var type = 10;
          let url = `/load-market-job-template/${labour_type}/${type}`;

          ajaxCall(url, 'GET', [[updateStatesDropdown, ['response', 'jobTitle']]]);
        });

        $('#jobTitle, #jobLevel').on('change', () => {
          this.loadBillRate();
          this.loadTemplate();
        });

        $("#ledger_type").find("option[value='31'], option[value='32']").remove();
        var isLedgerCodeRequired = $("#ledger_type :selected").val() === "33";
        $("#ledger_code").prop('required', isLedgerCodeRequired);
        $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);

        $("#ledger_type").on('change', function () {
          // console.log($(this));

          var isLedgerCodeRequired = $(this).val() === 33;
          $("#ledger_code").prop('required', isLedgerCodeRequired);
          $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
          $(".ledger_code__").toggle(isLedgerCodeRequired);
        });

        this.loadBillRate = () => {
          var level_id = $('#jobLevel').find(':selected').val();
          var template_id = $('#jobTitle').find(':selected').val();

          $('#maxBillRate').val('');
          $('#billRate').val('');
          let url = `/load-job-template`;

          if (level_id != '' && template_id != '') {
            let data = new FormData();
            data.append('template_id', template_id);
            data.append('level_id', level_id);
            const updates = {
              '#maxBillRate': { type: 'value', field: 'max_bill_rate' },
              '#billRate': { type: 'value', field: 'min_bill_rate' },
              '#currency': { type: 'select2', field: 'currency' },
              // '#currency': { type: 'value', field: 'currency_class' },
              // Add more mappings as needed
            };
            ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
            setTimeout(() => {
             this.formData.billRate =  $('#billRate').val();
             this.formData.maxBillRate = $('#maxBillRate').val();
             this.formData.currency = $('#currency').val();
            }, 1000);
          }
        };

        this.loadTemplate = () => {
          var template_id = $('#jobTitle').find(':selected').val();

          let url = `/load-job-template/`;
          let data = new FormData();
          data.append('template_id', template_id);
          const updates = {
            '#jobDescriptionEditor': { type: 'quill', field: 'job_description' },
            // '#job_family_value': { type: 'value', field: 'job_family_id' },
            // '#Job_worker_type': { type: 'disabled', value: true },
            '#Job_worker_type': { type: 'select2', field: 'worker_type' },
            // '#worker_type_value': { type: 'value', field: 'worker_type' },
            '#job_code': { type: 'value', field: 'job_code' },
            // Add more mappings as needed
          };
          ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
          setTimeout(() => {
            // this.formData.jobDescriptionEditor =  $('#jobDescriptionEditor').val();
            this.formData.workerType = $('#Job_worker_type').val();
            this.formData.job_code = $('#job_code').val();
           }, 500);
        };

        this.calculateRate = () => {
          var bill_rate = $('#billRate').val();
          var payment_type = $('#payment_type').val();
          var hours_per_week = $('#workDaysPerWeek').val();
          var Job_start_date = $('#startDate').val();
          var Job_end_date = $('#endDate').val();
          var openings = $("#num_openings").val();
          var hours_per_day = $("#hours_per_day").val();

          $("#job_duration").html(Job_start_date + ' - ' + Job_end_date);
          $("#job_duration1").html(Job_start_date + ' - ' + Job_end_date);

          var sumOfEstimates = 0;
          $('.addCost').each(function() {
            var addedValue = $(this).val().replace(/,/g, '');
            sumOfEstimates += (isNaN(parseFloat(addedValue))) ? 0.00 : parseFloat(addedValue);
          });
          let data = new FormData();
          data.append('bill_rate', bill_rate);
          data.append('other_amount_sum', sumOfEstimates);
          data.append('payment_type', payment_type);
          data.append('start_date', Job_start_date);
          data.append('end_date', Job_end_date);
          data.append('opening', openings);
          data.append('hours_per_day', hours_per_day);
          data.append('days_per_week', hours_per_week);
            let url;
            if (sessionrole === 'admin') {
                url = `/admin/job-rates/`;
            } else if (sessionrole === 'client') {
                url = `/client/job-rates/`;
            }
            else if (sessionrole === 'vendor') {
                url = `/vendor/job-rates/`;
            }
            else if (sessionrole === 'consultant') {
                url = `/consultant/job-rates/`;
            }
            /*          let url = `/admin/job-rates/`;*/
          const updates = {
            '#regular_cost': { type: 'value', field: 'regularBillRate' },
            '#single_resource_cost': { type: 'value', field: 'singleResourceCost' },
            '#all_resources_span': { type: 'value', field: 'regularBillRateAll'},
            '#all_resources_input': { type: 'value', field: 'allResourceCost' },
            '#regular_hours': { type: 'value', field: 'totalHours'},
            '#numOfWeeks': { type: 'value', field: 'numOfWeeks' }
          };
          ajaxCall(url, 'POST', [[updateElements, ['response', updates]]], data);
          setTimeout(() => {
            this.formData.regularCost =  $('#regular_cost').val();
            this.formData.singleResourceCost = $('#single_resource_cost').val();
            this.formData.allResourcesRegularCost = $('#all_resources_span').val();
            this.formData.regularHours =  $('#regular_hours').val();
            this.formData.allResourcesCost = $('#all_resources_input').val();
            this.formData.numberOfWeeks = $('#numOfWeeks').val();
           }, 500);
        };

        this.formatNumber = (num) => {
          return parseFloat(num).toFixed(2);
        };
      }

    },


     // Define the formatDate method
     formatDate(dateStr) {
      if (!dateStr) return "";
      const date = new Date(dateStr);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}/${month}/${day}`;
    },
    // Business unit script start from here
    addBusinessUnit() {
      if (!this.selectedBusinessUnit || !this.budgetPercentage) {
        this.showErrors = true;
        this.businessUnitErrorMessage =
          "Please select a Business Unit and enter a Budget Percentage.";
        return;
      }

      const newTotal =
        this.getTotalPercentage() + parseFloat(this.budgetPercentage);
      if (newTotal > 100) {
        this.businessUnitErrorMessage =
          "Percentage Value cannot be more than 100%";
        return;
      }

      const selectedOption = this.$refs.businessUnitSelect.querySelector(
        `option[value="${this.selectedBusinessUnit}"]`
      );
      const businessUnitText = selectedOption
        ? selectedOption.textContent
        : this.selectedBusinessUnit;

      this.formData.businessUnits.push({
        id:this.selectedBusinessUnit,
        unit: businessUnitText,
        percentage: parseFloat(this.budgetPercentage),
      });
      console.log(this.formData.businessUnits);

      let url = `/division-load`;
      let data = new FormData();
                data.append('bu_id', this.selectedBusinessUnit);

                const updates = {
                    '#regionZone': { type: 'select2append', field: 'zone' },
                    '#branch': { type: 'select2append', field: 'branch' },
                    '#division': { type: 'select2append', field: 'division' },
                    // '#currency': { type: 'value', field: 'currency_class' },
                    // Add more mappings as needed
                };
                ajaxCall(url,  'POST', [[updateElements, ['response', updates]]], data);
      this.selectedBusinessUnit = "";
      this.budgetPercentage = "";
      // $(this.$refs.businessUnitSelect).val("").trigger("change");
      this.showErrors = false;
      this.businessUnitErrorMessage = "";
    },

    removeBusinessUnit(index) {
      this.formData.businessUnits.splice(index, 1);
    },

    // Handle file selection
    handleFileUpload(event) {
      this.attachmentFile = event.target.files[0]; // Store the selected file
    },

    getTotalPercentage() {
      return this.formData.businessUnits.reduce(
        (total, bu) => total + bu.percentage,
        0
      );
    },
    // Business unit script end from here
    // Format Phone Number
    formatPhoneNumber(input) {
      let phoneNumber = input.value.replace(/\D/g, "");
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
      input.value = phoneNumber;
    },

    isValidPhone(phone) {
      return /^\(\d{4}\)\s\d{3}-\d{4}$/.test(phone);
    },

    isValidPayRate(rate) {
      return this.isValidBillRate(rate);
    },
    get isBusinessUnitValid() {
      const total = this.getTotalPercentage();
      if (total < 100) {
        this.businessUnitErrorMessage =
          "Percentage Value cannot be less than 100%";
        return false;
      }
      if (total > 100) {
        this.businessUnitErrorMessage =
          "Percentage Value cannot be more than 100%";
        return false;
      }
      return true;
    },

    formatBillRate(value) {


      this.formData.billRate = this.formatBillingValue(value);
    },

    formatMaxBillRate(value) {
      this.formData.maxBillRate = this.formatBillingValue(value);
    },

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
    initSelect2() {
      this.$nextTick(() => {
        $(".select2-single").each((index, element) => {
          const fieldName = $(element).data("field");
          $(element)
            .select2({
              width: "100%",
            })
            .on("select2:select", (e) => {
              if (fieldName === "businessUnit") {
                this.selectedBusinessUnit = e.params.data.id;
              } else {
                this.formData[fieldName] = e.params.data.id;
              }
            })
            .on("select2:unselect", () => {
              if (fieldName === "businessUnit") {
                this.selectedBusinessUnit = "";
              } else {
                this.formData[fieldName] = "";
              }
            });
        });

        // Initialize the Business Unit select
        $(this.$refs.businessUnitSelect)
          .val(this.selectedBusinessUnit)
          .trigger("change");
      });
    },
    // Rich Text Editors
    initQuill(editorIds) {
      // In your component's mounted hook or wherever you initialize Quill
      this.$nextTick(() => {
        if (typeof Quill === "undefined") {
          console.error("Quill is not defined");
          return;
        }

        editorIds.forEach((editorId) => {
          // Check if Quill is already initialized for this editor
          if (this.quill && this.quill[editorId]) {
            console.log(`Quill already initialized for ${editorId}`);
            return;
          }

          // Remove any existing toolbar for this editor
          const existingToolbar = document.querySelector(
            `#${editorId} .ql-toolbar`
          );
          if (existingToolbar) {
            existingToolbar.remove();
          }

          if (!this.quill) {
            this.quill = {};
          }

          this.quill[editorId] = new Quill(`#${editorId}`, {
            theme: "snow",
            modules: {
              toolbar: [
                ["bold", "italic", "underline", "strike"],
                ["blockquote", "code-block"],
                [{ header: 1 }, { header: 2 }],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ script: "sub" }, { script: "super" }],
                [{ indent: "-1" }, { indent: "+1" }],
                [{ direction: "rtl" }],
                [{ size: ["small", false, "large", "huge"] }],
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                [{ color: [] }, { background: [] }],
                [{ font: [] }],
                [{ align: [] }],
                ["clean"],
              ],
            },
          });

          this.quill[editorId].on("text-change", () => {
            switch (editorId) {
              case "jobDescriptionEditor":
                this.formData.jobDescriptionEditor =
                  this.quill[editorId].root.innerHTML.trim();
                break;
              case "qualificationSkillsEditor":
                this.formData.qualificationSkillsEditor =
                  this.quill[editorId].root.innerHTML.trim();
                break;
              case "additionalRequirementEditor":
                this.formData.additionalRequirementEditor =
                  this.quill[editorId].root.innerHTML.trim();
                break;
            }
          });
        });
      });
    },
    init() {
      console.log(this.formData);

      this.initSelect2();
      this.initQuill([
        "jobDescriptionEditor",
        "qualificationSkillsEditor",
        "additionalRequirementEditor",
      ]);
      this.initFlatpickr();

             // If in edit mode, populate the form with initialData
      if (careerOpportunity.id) {

        if (this.formData.startDate) {
          this.formData.startDate = this.formatDate(this.formData.startDate);
        }
        if (this.formData.endDate) {
          this.formData.endDate = this.formatDate(this.formData.endDate);
        }
            var isLedgerCodeRequired = careerOpportunity.ledger_type_id == "33";
            console.log(isLedgerCodeRequired);
            $("#ledger_code").prop('required', isLedgerCodeRequired);
            $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
            $(".ledger_code__").toggle(isLedgerCodeRequired);
        // If the quill editors need to load data
        this.$nextTick(() => {

          this.calculateRate();
          if (this.quill) {
            if (this.quill.jobDescriptionEditor) {
              this.quill.jobDescriptionEditor.root.innerHTML =
                careerOpportunity.description || "";

            }
            if (this.quill.qualificationSkillsEditor) {
              this.quill.qualificationSkillsEditor.root.innerHTML =
                careerOpportunity.skills || "";
            }
            if (this.quill.additionalRequirementEditor) {
              this.quill.additionalRequirementEditor.root.innerHTML =
                careerOpportunity.internal_notes || "";
            }
          }
        });
      }

    },

    initFlatpickr() {
      flatpickr("#startDate", {
        dateFormat: "Y/m/d",
        defaultDate: this.formData.startDate || null,
        onChange: (selectedDates, dateStr) => {
          this.formData.startDate = dateStr;
          this.endDatePicker.set("minDate", dateStr);
        },
      });

      this.endDatePicker = flatpickr("#endDate", {
        dateFormat: "Y/m/d",
        defaultDate: this.formData.endDate || null,
        onChange: (selectedDates, dateStr) => {
          this.formData.endDate = dateStr;
        },
      });
    },

    nextStep() {
        if (!this.formSubmitted) {
            this.validateStep(this.currentStep);
//            console.log(`Current Step (${this.currentStep}) Data:`, this.formData);
            if (this.isStepValid) {
                this.showErrors = false;
                this.saveDraft();
//                console.log('Draft saved:', localStorage.getItem('formDraft'));
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
    },

    goToStep(step) {
      if (!this.formSubmitted && step <= this.highestStepReached) {
        this.showErrors = false;
        this.currentStep = step;
      }
    },
saveDraft() {
  // Save the current form data to a draft object
  const draft = {
    step: this.currentStep,
    formData: this.formData,
    attachmentFile: this.attachmentFile || null,
  };

  // Save the draft to localStorage
  localStorage.setItem('formDraft', JSON.stringify(draft));
  this.submitForm(draft);
//  console.log('Draft saved:', localStorage.getItem('formDraft'));
},
postDraftToServer(draft) {
  let formData = new FormData();
  formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
  formData.append('step', draft.step);

  // Loop through formData to append the form fields
  Object.keys(draft.formData).forEach((key) => {
    if (Array.isArray(draft.formData[key])) {
      // Handle array items (like business units)
      draft.formData[key].forEach((item, index) => {
        formData.append(`${key}[${index}]`, JSON.stringify(item));
      });
    } else {
      formData.append(key, draft.formData[key]);
    }
  });

  // Add the file (if any)
  if (draft.attachmentFile) {
    formData.append('attachmentFile', draft.attachmentFile);
  }

  // Determine the correct method type and URL for the AJAX call
  let url = '/admin/career-opportunities/save-draft';  // Change the URL as needed
  let methodType = 'POST';  // Use 'PUT' if updating existing data

  // Call your ajaxCall function with the necessary arguments
  ajaxCall(url, methodType, [[this.onDraftSaveSuccess, ['response']]], formData);

  // Optionally display a success message after calling
  this.showSuccessMessage = true;
},

onDraftSaveSuccess(response) {
  console.log('Draft successfully saved to server:', response);
  // Add any other success handling code here
},


loadDraft() {
  // Load the draft from localStorage (if it exists)
  const draft = localStorage.getItem('formDraft');
  if (draft) {
    const parsedDraft = JSON.parse(draft);
    this.currentStep = parsedDraft.step || 1;
    this.formData = parsedDraft.formData || {};
    this.attachmentFile = parsedDraft.attachmentFile || null;
  }
},
    isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },

    get isStepValid() {
      switch (this.currentStep) {
        case 1:
          return (
            this.formData.jobLaborCategory !== "" &&
            this.formData.jobTitle !== "" &&
            this.formData.hiringManager !== "" &&
            this.formData.jobLevel !== "" &&
            this.formData.workLocation !== "" &&
            this.formData.currency !== "" &&
            this.formData.billRate !== "" &&
            this.formData.maxBillRate !== "" &&
            this.isValidBillRate(this.formData.billRate) &&  
            this.isValidMaxBillRate(this.formData.maxBillRate) 
          );
        case 2:
          return (
            this.formData.preIdentifiedCandidate !== "" &&
            (this.formData.preIdentifiedCandidate !== "Yes" ||
              (this.formData.candidateFirstName.trim() !== "" &&
                this.formData.candidateLastName.trim() !== "" &&
                this.isValidPhone(this.formData.candidatePhone) &&
                this.isValidEmail(this.formData.candidateEmail) &&
                this.isValidPayRate(this.formData.workerPayRate)
                )) &&
            this.formData.laborType !== "" &&
            this.formData.startDate.trim() !== "" &&
            this.formData.endDate.trim() !== "" &&
            this.formData.jobDescriptionEditor.trim() !== "" &&
            this.formData.qualificationSkillsEditor.trim() !== "" &&
            this.formData.additionalRequirementEditor.trim() !== ""
          );
        case 3:
          return (
            this.formData.division !== "" &&
            this.formData.regionZone !== "" &&
            this.formData.branch !== "" &&
            this.formData.expensesAllowed !== "" &&
            this.formData.travelRequired !== "" &&
            this.formData.glCode !== "" &&
            this.formData.workerType !== "" &&
            this.formData.clientBillable !== "" &&
            this.formData.requireOT !== "" &&
            this.formData.virtualRemote !== "" &&
            (this.formData.expensesAllowed !== "Yes" ||
              this.isValidEstimatedExpense(this.formData.estimatedExpense))
              &&
            (this.formData.subLedgerType !== 33 ||
              this.formData.subLedgerCode.trim() !== "") &&
            (this.formData.clientBillable !== "Yes" ||
              this.formData.clientName.trim() !== "") &&
            this.isBusinessUnitValid
          );
        case 4:
          return (
            this.formData.payment_type !== "" &&
            this.formData.timeType !== "" &&
            this.formData.estimatedHoursPerDay !== "" &&
            this.formData.workDaysPerWeek !== "" &&
            this.formData.numberOfPositions !== "" &&
            this.formData.businessReason !== "" &&
            this.formData.termsAccepted
          );
        default:
          return false;
      }
    },
    get isFormValid() {
      return (
        this.formData.jobLaborCategory !== "" &&
        this.formData.jobTitle !== "" &&
        this.formData.hiringManager !== "" &&
        this.formData.jobLevel !== "" &&
        this.formData.workLocation !== "" &&
        this.formData.currency !== "" &&
        this.formData.billRate !== "" &&
        this.formData.maxBillRate !== "" &&
        this.formData.preIdentifiedCandidate !== "" &&
        this.formData.laborType !== "" &&
        this.formData.jobDescriptionEditor.trim() !== "" &&
        this.formData.qualificationSkillsEditor.trim() !== "" &&
        this.formData.additionalRequirementEditor.trim() !== "" &&
        this.formData.division !== "" &&
        this.formData.regionZone !== "" &&
        this.formData.branch !== "" &&
        this.formData.expensesAllowed !== "" &&
        this.formData.travelRequired !== "" &&
        this.formData.glCode !== "" &&
        this.formData.workerType !== "" &&
        this.formData.clientBillable !== "" &&
        this.formData.requireOT !== "" &&
        this.formData.virtualRemote !== "" &&
        (this.formData.expensesAllowed !== "Yes" ||
          this.isValidEstimatedExpense(this.formData.estimatedExpense)) &&
        (this.formData.clientBillable !== "Yes" ||
          this.formData.clientName.trim() !== "") &&
          (this.formData.subLedgerType !== 33 ||
            this.formData.subLedgerCode.trim() !== "") &&
        this.isBusinessUnitValid &&
        this.formData.payment_type !== "" &&
        this.formData.timeType !== "" &&
        this.formData.estimatedHoursPerDay !== "" &&
        this.formData.workDaysPerWeek !== "" &&
        this.formData.numberOfPositions !== "" &&
        this.formData.businessReason !== "" &&
        this.formData.termsAccepted
      );
    },
    submitForm(draft) {

  this.showErrors = true;
  console.log("Form submitted:", draft.formData.job_code);

  const formData = new FormData();
  Object.keys(draft.formData).forEach((key) => {
    if (Array.isArray(draft.formData[key])) {
      // If the key is an array (like businessUnits), handle each item
      draft.formData[key].forEach((item, index) => {
        formData.append(`${key}[${index}]`, JSON.stringify(item));
      });
    } else {
      formData.append(key, draft.formData[key]);
    }
  });

  formData.append("jobTitleEmailSignature", draft.formData.jobTitleEmailSignature);

  // Append the file (if any)
  if (draft.attachmentFile) {
    formData.append("attachment", draft.attachmentFile);
  }

  // Determine method and URL based on session role and careerOpportunity ID
  const methodType = careerOpportunity.id ? 'POST' : 'POST';
 let url = 'admin/career-opportunities/save-draft';   // Append dynamic fields to form data
  //const url = '{{ route('admin.career-opportunities.saveDraft') }}';
  const dynamicFields = document.querySelectorAll(".render-wrap [name]");
  dynamicFields.forEach((field) => {
    const fieldName = field.name;
    const fieldValue = field.type === "checkbox" || field.type === "radio" ? field.checked : field.value;
    formData.append(fieldName, fieldValue);
  });

  // Debugging: Log all form data entries
  console.log("Final FormData:");
  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }
  console.log("Request URL:", url);

  // Make the AJAX call
  ajaxCall(url, 'POST', [[onSuccess, ['response']]], formData);

  this.showSuccessMessage = true;
    },

/*    submitForm() {

      this.showErrors = true;
      if (this.isFormValid) {

        console.log("Form submitted:", this.formData.job_code);

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

        formData.append("jobTitleEmailSignature", this.formData.jobTitleEmailSignature);
        // Append the file (if any)
      if (this.attachmentFile) {
        formData.append("attachment", this.attachmentFile);
      }
      if(careerOpportunity.id) {
      formData.append('_method', 'PUT');
      }
      let methodtype = careerOpportunity.id ? 'POST' : 'POST';
      let url;
          if (sessionrole === 'admin') {
              url = careerOpportunity.id
                  ? `/admin/career-opportunities/${careerOpportunity.id}`
                  : "/admin/career-opportunities";
          } else if (sessionrole === 'client') {
              url = careerOpportunity.id
                  ? `/client/career-opportunities/${careerOpportunity.id}`
                  : "/client/career-opportunities";
          }

          const dynamicFields = document.querySelectorAll(".render-wrap [name]");
          dynamicFields.forEach((field) => {
              const fieldName = field.name;
              const fieldValue = field.type === "checkbox" || field.type === "radio" ? field.checked : field.value;
              formData.append(fieldName, fieldValue);
          });

          // Debugging: Log all form data entries
           console.log("Final FormData:");
           for (let [key, value] of formData.entries()) {
               console.log(`${key}: ${value}`);
           }
        ajaxCall(url,methodtype, [[onSuccess, ['response']]], formData);
        this.showSuccessMessage = true;
        // this.resetForm();
        // this.currentStep = 1;
        // this.highestStepReached = 1;
        // this.formSubmitted = true;
        // setTimeout(() => {
        //   this.showSuccessMessage = false;
        // }, 5000);
      } else {
        console.log("Form is invalid. Please check the errors.");
      }
    },
*/    autoSubmitDraft() {
      window.addEventListener('beforeunload', (event) => {
        if (!this.formSubmitted) {
          // Call method to save the draft automatically
          this.saveDraft();
        }
      });
    },
    resetForm() {
      this.currentStep = 1;
      this.highestStepReached = 1;
      this.showErrors = false;
      this.formSubmitted = false;
      this.formData = {
        jobLaborCategory: "",
        jobTitle: "",
        hiringManager: "",
        jobLevel: "",
        workLocation: "",
        currency: "",
        billRate: "",
        maxBillRate: "",
        preIdentifiedCandidate: "",
        candidateFirstName: "",
        candidateMiddleName: "",
        candidateLastName: "",
        candidatePhone: "",
        candidateEmail: "",
        workerPayRate: "0.00",
        jobTitleEmailSignature:"",
        // engageWorkerAs: "",
        laborType: "",
        jobDescriptionEditor: "",
        qualificationSkillsEditor: "",
        additionalRequirementEditor: "",
        division: "",
        regionZone: "",
        branch: "",
        expensesAllowed: "",
        travelRequired: "",
        glCode: "",
        subLedgerType: "",
        subLedgerCode: "",
        workerType: "",
        clientBillable: "",
        requireOT: "",
        virtualRemote: "",
        estimatedExpense: "0.00",
        clientName: "",
        job_code:"",
        businessUnits: [],
        payment_type: "",
        timeType: "",
        estimatedHoursPerDay: "",
        workDaysPerWeek: "",
        numberOfPositions: "",
        businessReason: "",
        regularCost: "0.00",
        singleResourceCost: "0.00",
        allResourcesRegularCost: "0.00",
        allResourcesCost: "0.00",
        regularHours: "0",
        numberOfWeeks: "0",
        termsAccepted: false,
      };
      this.selectedBusinessUnit = "";
      this.budgetPercentage = "";
      this.businessUnitErrorMessage = "";
      // Reset Select2 dropdowns
      this.$nextTick(() => {
        $(".select2-single").val(null).trigger("change");
      });

      // Reset Quill editors
      if (this.quill) {
        Object.keys(this.quill).forEach((editorId) => {
          this.quill[editorId].setText("");
        });
      }
    },
  };

}


