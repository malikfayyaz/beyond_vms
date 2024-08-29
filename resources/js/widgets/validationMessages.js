export const errorMessages = {
  jobLaborCategory: "Please select a job labor category.",
  jobTitle: "Please select a job title.",
  hiringManager: "Please select hiring manager.",
  jobLevel: "Please select job level.",
  workLocation: "Please select work location.",
  currency: "Please select currency.",
  billRate: "Please enter valid minimum bill rate.",
  maxBillRate: "Please enter valid maximum bill rate.",
  preIdentifiedCandidate:
    "Please select if there's a pre-identified candidate.",
  candidateFirstName: "Please enter candidate's first name.",
  candidateLastName: "Please enter candidate's last name.",
  candidatePhone: "Please enter valid phone number.",
  candidateEmail: "Please enter valid email address.",
  workerPayRate: "Please enter a valid pay rate.",
  engageWorkerAs: "Please select how to engage the worker.",
  laborType: "Please select a labor type.",
  startDate: "Please select a start date.",
  endDate: "Please select an end date.",
  jobDescriptionEditor: "Please enter job description.",
  qualificationSkillsEditor: "Please enter qualifications/skills.",
  additionalRequirementEditor: "Please enter additional requirements.",
  division: "Please select a division.",
  regionZone: "Please select a region/zone.",
  branch: "Please select a branch.",
  expensesAllowed: "Please select if expenses are allowed.",
  travelRequired: "Please select if travel is required.",
  glCode: "Please select a GL code.",
  clientBillable: "Please select if it's client billable.",
  requireOT: "Please select if overtime is required.",
  virtualRemote: "Please select if it's virtual/remote.",
  estimatedExpense: "Please enter a valid estimated expense.",
  clientName: "Please enter client name.",
  businessUnit: "Please select a Business Unit and enter a Budget Percentage.",
  unitOfMeasure: "Please select a unit of measure.",
  timeType: "Please select a time type.",
  estimatedHoursPerDay: "Please enter estimated hours per day.",
  workDaysPerWeek: "Please enter work days per week.",
  numberOfPositions: "Please enter number of positions.",
  businessReason: "Please select a business reason.",
  termsAccepted: "Please accept the terms and conditions.",
  // Add Submission form errors
  candidateSelection: "Please select Candidate.",
  candidateType: "Please select candidate type New or Existing",
  dobDate: "Please enter Date of Birth.",
  lastFourNationalId: "Last 4 digits of National ID must be 4 digits",
  preferredName: "Please enter preferred name.",
  gender: "Please select option in gender.",
  race: "Please select race.",
  workLocation: "Please work location.",
  availableDate: "Please select an available date",
  needSponsorship: "Please select whether the worker needs sponsorship",
  workedForGallagher:
    "Please select whether the candidate has worked for Gallagher",
  gallagherCapacity:
    "Please select the capacity in which the candidate worked for Gallagher",
  gallagherStartDate: "Please select the start date of work at Gallagher",
  gallagherLastDate: "Please select the last date of work at Gallagher",
  willingToCommute:
    "Please select if the candidate is willing to commute to office",
  virtualRemoteCandidate: "Please select if this is a virtual/remote candidate",
  rightToRepresent: "Please select if you have the right to represent",
  virtualCity: "Please enter the virtual city",
  virtualState: "Please select the virtual state/province",
  jobTitle: "Please add job title",
  laborCategory: "Please select labor category",
  profileWorkerType: "Please select profile worker type",
  workerType: "Please select worker type",
  jobFamily: "Please select job family",
  jobCatalogStatus: "Please select catalog status",
  jobDescription: "Please select job description",
};

export const fieldMapping = {
  // 'htmlId': 'formDataKey'
  jobLaborCategory: "jobLaborCategory",
  jobTitle: "jobTitle",
  hiringManager: "hiringManager",
  jobLevel: "jobLevel",
  workLocation: "workLocation",
  currency: "currency",
  billRate: "billRate",
  maxBillRate: "maxBillRate",
  preIdentifiedCandidate: "preIdentifiedCandidate",
  candidateFirstName: "candidateFirstName",
  candidateLastName: "candidateLastName",
  candidatePhone: "candidatePhone",
  candidateEmail: "candidateEmail",
  workerPayRate: "workerPayRate",
  engageWorkerAs: "engageWorkerAs",
  laborType: "laborType",
  startDate: "startDate",
  endDate: "endDate",
  jobDescriptionEditor: "jobDescriptionEditor",
  qualificationSkillsEditor: "qualificationSkillsEditor",
  additionalRequirementEditor: "additionalRequirementEditor",
  division: "division",
  regionZone: "regionZone",
  branch: "branch",
  expensesAllowed: "expensesAllowed",
  travelRequired: "travelRequired",
  glCode: "glCode",
  clientBillable: "clientBillable",
  requireOT: "requireOT",
  virtualRemote: "virtualRemote",
  clientName: "clientName",
  estimatedExpense: "estimatedExpense",
  unitOfMeasure: "unitOfMeasure",
  timeType: "timeType",
  estimatedHoursPerDay: "estimatedHoursPerDay",
  workDaysPerWeek: "workDaysPerWeek",
  numberOfPositions: "numberOfPositions",
  businessReason: "businessReason",
  termsAccepted: "Please accept terms and condition!",
  candidateSelection: "candidateSelection",
  candidateType: "candidateType",
  dobDate: "dobDate",
  lastFourNationalId: "lastFourNationalId",
  preferredName: "preferredName",
  gender: "gender",
  race: "race",
  workLocation: "workLocation",
  availableDate: "availableDate",
  needSponsorship: "needSponsorship",
  workedForGallagher: "workedForGallagher",
  gallagherCapacity: "gallagherCapacity",
  gallagherStartDate: "gallagherStartDate",
  gallagherLastDate: "gallagherLastDate",
  willingToCommute: "willingToCommute",
  virtualRemoteCandidate: "virtualRemoteCandidate",
  rightToRepresent: "rightToRepresent",
  virtualCity: "virtualCity",
  virtualState: "virtualState",
  jobTitle: "jobTitle",
  laborCategory: "laborCategory",
  workerType: "workerType",
  jobCatalogStatus: "jobCatalogStatus",
  jobDescription: "jobDescription",
};

export function getErrorMessageById(id, customErrorMessages) {
  const formDataKey = fieldMapping[id] || id;
  return customErrorMessages[formDataKey] || "This field is required.";
}

export function isFieldValid(id, formData, validationFunctions) {
  const formDataKey = fieldMapping[id] || id;

  // Special case validations
  if (formDataKey === "businessUnit") {
    return validationFunctions.isBusinessUnitValid();
  }
  if (
    ["billRate", "maxBillRate", "estimatedExpense", "workerPayRate"].includes(
      formDataKey
    )
  ) {
    return validationFunctions.isValidBillRate(formData[formDataKey]);
  }
  if (formDataKey === "candidatePhone") {
    return validationFunctions.isValidPhone(formData[formDataKey]);
  }
  if (formDataKey === "candidateEmail") {
    return validationFunctions.isValidEmail(formData[formDataKey]);
  }

  return formData[formDataKey] !== "" && formData[formDataKey] !== undefined;
}
