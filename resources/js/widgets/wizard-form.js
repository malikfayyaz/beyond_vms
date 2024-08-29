import {
  errorMessages,
  getErrorMessageById,
  isFieldValid,
} from "./validationMessages.js";

export default function wizardForm() {
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

    // Form Data
    formData: {
      jobLaborCategory: "",
      jobTitle: "",
      hiringManager: "",
      jobLevel: "",
      workLocation: "",
      currency: "",
      billRate: "0.00",
      maxBillRate: "0.00",
      preIdentifiedCandidate: "",
      candidateFirstName: "",
      candidateMiddleName: "",
      candidateLastName: "",
      candidatePhone: "",
      candidateEmail: "",
      workerPayRate: "0.00",
      engageWorkerAs: "",
      laborType: "",
      startDate: "",
      endDate: "",
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
      workerType: "default", // Set default value
      clientBillable: "",
      requireOT: "",
      virtualRemote: "",
      estimatedExpense: "0.00",
      clientName: "",
      businessUnits: [],
      unitOfMeasure: "",
      timeType: "",
      estimatedHoursPerDay: "",
      workDaysPerWeek: "",
      numberOfPositions: "",
      businessReason: "",
      regularCost: "0.00",
      singleResourceCost: "0.00",
      allResourcesRegularCost: "0.00",
      allResourcesCost: "0.00",
      regularHours: "8",
      numberOfWeeks: "0 Weeks 1 Days",
      termsAccepted: false,
    },
    selectedBusinessUnit: "",
    budgetPercentage: "",
    businessUnitErrorMessage: "",
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
        unit: businessUnitText,
        percentage: parseFloat(this.budgetPercentage),
      });

      this.selectedBusinessUnit = "";
      this.budgetPercentage = "";
      $(this.$refs.businessUnitSelect).val("").trigger("change");
      this.showErrors = false;
      this.businessUnitErrorMessage = "";
    },

    removeBusinessUnit(index) {
      this.formData.businessUnits.splice(index, 1);
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
      this.initSelect2();
      this.initQuill([
        "jobDescriptionEditor",
        "qualificationSkillsEditor",
        "additionalRequirementEditor",
      ]);
      this.initFlatpickr();
    },

    initFlatpickr() {
      flatpickr("#startDate", {
        dateFormat: "Y/m/d",
        onChange: (selectedDates, dateStr) => {
          this.formData.startDate = dateStr;
          this.endDatePicker.set("minDate", dateStr);
        },
      });

      this.endDatePicker = flatpickr("#endDate", {
        dateFormat: "Y/m/d",
        onChange: (selectedDates, dateStr) => {
          this.formData.endDate = dateStr;
        },
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
            this.formData.maxBillRate !== ""
          );
        case 2:
          return (
            this.formData.preIdentifiedCandidate !== "" &&
            (this.formData.preIdentifiedCandidate !== "yes" ||
              (this.formData.candidateFirstName.trim() !== "" &&
                this.formData.candidateLastName.trim() !== "" &&
                this.isValidPhone(this.formData.candidatePhone) &&
                this.isValidEmail(this.formData.candidateEmail) &&
                this.isValidPayRate(this.formData.workerPayRate) &&
                this.formData.engageWorkerAs !== "")) &&
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
            (this.formData.expensesAllowed !== "yes" ||
              this.isValidEstimatedExpense(this.formData.estimatedExpense)) &&
            (this.formData.clientBillable !== "yes" ||
              this.formData.clientName.trim() !== "") &&
            this.isBusinessUnitValid
          );
        case 4:
          return (
            this.formData.unitOfMeasure !== "" &&
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
        (this.formData.expensesAllowed !== "yes" ||
          this.isValidEstimatedExpense(this.formData.estimatedExpense)) &&
        (this.formData.clientBillable !== "yes" ||
          this.formData.clientName.trim() !== "") &&
        this.isBusinessUnitValid &&
        this.formData.unitOfMeasure !== "" &&
        this.formData.timeType !== "" &&
        this.formData.estimatedHoursPerDay !== "" &&
        this.formData.workDaysPerWeek !== "" &&
        this.formData.numberOfPositions !== "" &&
        this.formData.businessReason !== "" &&
        this.formData.termsAccepted
      );
    },
    submitForm() {
      this.showErrors = true;
      if (this.isFormValid) {
        console.log("Form submitted:", this.formData);
        this.showSuccessMessage = true;
        this.resetForm();
        this.currentStep = 1;
        this.highestStepReached = 1;
        this.formSubmitted = true;
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
        engageWorkerAs: "",
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
        workerType: "default",
        clientBillable: "",
        requireOT: "",
        virtualRemote: "",
        estimatedExpense: "0.00",
        clientName: "",
        businessUnits: [],
        unitOfMeasure: "",
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
