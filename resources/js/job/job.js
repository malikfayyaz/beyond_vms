document.addEventListener('DOMContentLoaded', function() {
    console.log(window.$); // Verify jQuery is available
   if (window.$) {
       $('#jobLaborCategory').on('change', function () {
               var labour_type = $(this).val();
               var type = 10;
               let url = `/admin/load-market-job-template/${labour_type}/${type}`;

               ajaxCall(url, 'GET', [[updateStatesDropdown, ['response', 'jobTitle']]]);
           });
        $('#jobTitle, #jobLevel ').on('change', function() {
            loadBillRate();
            loadTemplate()
        });
          

        function loadBillRate() {
            var level_id = $('#jobLevel').find(':selected').val();
            var template_id = $('#jobTitle').find(':selected').val();
         
            $('#maxBillRate').val('');
            $('#billRate').val('');
            let url = `/admin/load-job-template`;
           
        
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
                ajaxCall(url,  'POST', [[updateElements, ['response', updates]]], data);
              
            }
        }
        
        
        function loadTemplate() {
            var template_id = $('#jobTitle').find(':selected').val();
           
            let url = `/admin/load-job-template/`;
            let data = new FormData();
            data.append('template_id', template_id);
            const updates = {
                '#jobDescriptionEditor': { type: 'quill', field: 'job_description' },
                '#job_family_value': { type: 'value', field: 'job_family_id' },
                // '#Job_worker_type': { type: 'disabled', value: true },
                '#Job_worker_type': { type: 'select2', field: 'worker_type' },
                // '#worker_type_value': { type: 'value', field: 'worker_type' },
                '#job_code': { type: 'value', field: 'job_code' },
                // Add more mappings as needed
            };
            ajaxCall(url,  'POST', [[updateElements, ['response', updates]]],data);
            
           
        }

        $(document).ready(function() {
            $("#ledger_type").find("option[value='31'], option[value='32']").remove();
            var isLedgerCodeRequired = $("#ledger_type :selected").val() === "3";
            $("#ledger_code").prop('required', isLedgerCodeRequired);
            $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
            $("#ledger_type").on('change', function() {
                var isLedgerCodeRequired = $(this).val() === "3";
                $("#ledger_code").prop('required', isLedgerCodeRequired);
                $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
                $(".ledger_code__").toggle(isLedgerCodeRequired);
              });
          });
             
   }
  
});