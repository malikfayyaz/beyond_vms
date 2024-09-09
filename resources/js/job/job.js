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

        function calculateRate() {
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
                sumOfEstimates += (isNaN(parseFloat(addedValue))) ? 0.00 : parseFloat(
                    addedValue); // Or this.innerHTML, this.innerText
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
            let url = `/admin/job-rates/`;
            const updates = {
                '#regular_cost': { type: 'value', field: 'regularBillRate' },
                '#single_resource_cost': { type: 'value', field: 'singleResourceCost' },
                '#all_resources_span': { type: 'value', field: 'regularBillRateAll'},
                '#all_resources_input': { type: 'value', field: 'allResourceCost' },
                '#regular_hours': { type: 'value', field: 'totalHours' },
                '#numOfWeeks': { type: 'value', field: 'numOfWeeks' },
                
                // Add more mappings as needed
            };
            ajaxCall(url,  'POST', [[updateElements, ['response', updates]]],data);
        
        
        }

        $(document).ready(function() {
            calculateRate();
            $("#ledger_type").find("option[value='31'], option[value='32']").remove();
            var isLedgerCodeRequired = $("#ledger_type :selected").val() === "33";
            $("#ledger_code").prop('required', isLedgerCodeRequired);
            $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
            $("#ledger_type").on('change', function() {
                var isLedgerCodeRequired = $(this).val() === "33";
                $("#ledger_code").prop('required', isLedgerCodeRequired);
                $(".ledger_code_").toggleClass('fa-asterisk', isLedgerCodeRequired);
                $(".ledger_code__").toggle(isLedgerCodeRequired);
              });
          });

        
          window.calculateRate = calculateRate;  
             
   }
  
});