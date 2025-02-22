import Alpine from "alpinejs";
// import "../css/app.css";
import jQuery from "jquery";
// import 'datatables.net';

import select2 from "select2";
import "select2/dist/css/select2.min.css";
import Quill from "quill";
import "quill/dist/quill.snow.css";
import wizardForm from "./widgets/add-job-wizard-form";
import addSubWizarForm from "./widgets/add-submission-wizard-form";
import jobCatalog from "./widgets/add-job-catalog";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";


// import './bootstrap';
import 'laravel-datatables-vite';
// import 'datatables.net';
// import "datatables.net/css/jquery.dataTables.min.css";

// For rich text editor
window.Quill = Quill;
// For calendars
window.flatpickr = flatpickr;

// Font Awesome
import { library, dom } from "@fortawesome/fontawesome-svg-core";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";




// Add the icons to the library
library.add(fas, far);

dom.watch();

window.$ = window.jQuery = jQuery;
import 'jquery-ui/ui/widget'; // Base widget module
import 'jquery-ui/ui/widgets/mouse'; // Mouse interactions
import 'jquery-ui/ui/widgets/sortable'; // Sortable widget
import 'jquery-ui/themes/base/all.css'; // jQuery UI CSS

import 'formBuilder/dist/form-builder.min.js'; // FormBuilder JS
import 'formBuilder/dist/form-render.min.js';
// import "datatables.net";
// import "datatables.net-dt/css/jquery.dataTables.min.css";
select2();
window.Alpine = Alpine;
console.log($.fn.jquery); // Should print "3.7.1"
console.log($.ui);
//Initialize Select2


$(".js-example-basic-single").select2();

Alpine.data("wizardForm", wizardForm);
Alpine.data("addSubWizarForm", addSubWizarForm);
Alpine.data("jobCatalog", jobCatalog);
Alpine.start();

$("#addjobformwizard .select2-single").select2({
  minimumResultsForSearch: Infinity,
  theme: "default",
});

// $('#fb-editor').formBuilder();

