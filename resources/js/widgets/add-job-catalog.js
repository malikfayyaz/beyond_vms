import {
  errorMessages,
  getErrorMessageById,
  isFieldValid,
} from "./validationMessages.js";

export default function jobCatalog() {
  return {
    showErrors: false,
    showSuccessMessage: false,
    formSubmitted: false,
    errorMessages,
    getErrorMessageById(id) {
      return getErrorMessageById(id, this.errorMessages);
    },
    isFieldValid(id) {
      return isFieldValid(id, this.formData);
    },

    formData: {
      jobTitle: "",
      laborCategory: "",
      profileWorkerType: "",
      workerType: "",
      jobCode: "",
      jobFamily: "",
      jobCatalogStatus: "",
      jobDescription: "",
      jobCatalogRateCards: [],
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
        });
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
              case "jobDescription":
                this.formData.jobDescription =
                  this.quill[editorId].root.innerHTML.trim();
                break;
            }
          });
        });
      });
    },
    init() {
      this.initSelect2();
      this.initQuill(["jobDescription"]);
    },
    validateStep(step) {
      this.showErrors = true;
    },

    isRateCardValid() {
      return this.formData.jobCatalogRateCards.length > 0;
    },

    updateJobCatalogRateCards(entries) {
      this.formData.jobCatalogRateCards = entries;
    },

    validateForm() {
      const requiredFields = [
        "jobTitle",
        "laborCategory",
        "profileWorkerType",
        "workerType",
        "jobFamily",
        "jobCatalogStatus",
        "jobDescription",
      ];

      let isValid = true;

      requiredFields.forEach((field) => {
        if (!this.isFieldValid(field)) {
          isValid = false;
        }
      });

      // Validate Job Catalog Rate Card entries
      // if (this.formData.jobCatalogRateCards.length === 0) {
      //   isValid = false;
      //   this.showErrors = true;
      //   console.error("At least one Job Catalog Rate Card entry is required");
      // }

      return isValid;
    },

    submitForm() {
      this.showErrors = true;
      if (this.validateForm()) {
        let form = document.getElementById('addjobformwizard');
        let formRecord = new FormData(form);
        console.log("Form submitted:", formRecord);
        let jobCatalogRateCardsJson = JSON.stringify(this.formData.jobCatalogRateCards);
        // Append the JSON string to FormData
        formRecord.append('jobCatalogRateCards', jobCatalogRateCardsJson);
        ajaxCall('/admin/job/catalog', 'POST', [[onSuccess, ['response']]], formRecord);
        this.showSuccessMessage = true;
        this.resetForm();
        this.formSubmitted = true;
        setTimeout(() => {
          this.showSuccessMessage = false;
        }, 5000);
      } else {
        console.log("Form is invalid. Please check the errors.");
      }
    },
    resetForm() {
      this.formData = {
        jobTitle: "",
        laborCategory: "",
        profileWorkerType: "",
        workerType: "",
        jobCode: "",
        jobFamily: "",
        jobCatalogStatus: "",
        jobDescription: "",
        jobCatalogRateCards: [],
      };
      this.showErrors = false;
      this.$dispatch("reset-rate-card");
    },
    updateJobCatalogRateCards(entries) {
      this.formData.jobCatalogRateCards = entries;
    },
  };
}
