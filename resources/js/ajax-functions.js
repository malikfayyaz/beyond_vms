// ajax-functions.js

function initializeDataTable(tableId, ajaxUrl, columns,getType) {
    return $(tableId).DataTable({
      processing: true,
      serverSide: true,
     
      ajax: {
        url: ajaxUrl,
       
        data: function(d) {
            if (getType) {
                    const typeData = getType(); // Retrieve the object with currentType, currentId, and subId
                    d.type = typeData.currentType;
                    d.currentId = typeData.currentId;
                    d.subId = typeData.subId;
                }
        }
    },
     
      columns: columns,
      order: [[1, 'desc']]
    });
     
  }

  function ajaxCall(url, method, functionsOnSuccess = [], form = null) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (form === null) {
            form = new FormData();
        }
        console.log('before ajax', form.values);

        $.ajax({
            url: url,
            type: method,
            async: true,
            data: form,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                functionsOnSuccess.forEach(function(funcArray) {
                    funcArray[1] = funcArray[1].map(arg => arg === "response" ? response : arg);
                    funcArray[0].apply(this, funcArray[1]);
                });
            },
            error: function(xhr, textStatus, error) {
                console.error('Error:', xhr.responseText);
                console.error('Status:', xhr.statusText);
                console.error('Text Status:', textStatus);
                console.error('Error Thrown:', error);

                // Handle error messages
                $('#error-messages').empty().show();

                try {
                    // Parse JSON response if it's a string
                    const response = JSON.parse(xhr.responseText);

                    // Check if errors is an object
                    if (typeof response.errors === 'object') {
                        // Loop through each field's errors
                        $.each(response.errors, function (field, messages) {
                            // Check if messages is an array (which it usually will be)
                            if (Array.isArray(messages)) {
                                // Append each message to the error container
                                messages.forEach(function (message) {
                                    $('.error-messages').append('<div>' + message + '</div>');
                                });
                            } else {
                                // If it's a single message, just append it
                                $('.error-messages').append('<div>' + messages + '</div>');
                            }
                        });
                    } else {
                        if(response.message) {
                         // If the response.errors is not an object, show a generic error message
                        $('.error-messages').append(response.message);
                        }else {
                            $('.error-messages').append('<div>Unknown error format.</div>');
                        }
                    }
                } catch (e) {
                    // If JSON parsing fails, show a generic error message
                    $('.error-messages').append('<div>Unable to parse error response.</div>');
                }

                $('.error-messages').css('display', 'block');
               
            }
        });
    }



  // Define the success callback function
  const onSuccess = (response) => {
    console.log(response);
    console.log("sfdfdsfsdf");
    if (response.success) {
        if (response.redirect_url) {
            window.location.href = response.redirect_url;
        } else {
            document.getElementById('success-message').innerText = response.message;
        }
    }
};


  // that response is for dropdown append options
  const updateStatesDropdown = (response, stateDropdownId) => {
    var statesDropdown = $('#' + stateDropdownId);
    // Save the "Select State" option

    // Clear only the dynamically added options
    statesDropdown.find('option:not([value=""])').remove();

    // Re-add the "Select State" option
    // statesDropdown.append(selectStateOption); // Clear the dropdown before appending new options


    $.each(response, function (key, state) {
        statesDropdown.append('<option value="' + state.id + '">' + state.name + '</option>');
    });
  };

  // that response is for dynamic record update in fields

    function updateElements(data, updates) {
      Object.entries(updates).forEach(([selector, updateType]) => {
          const element = $(selector);

          if (!element.length) return; // Skip if element does not exist

          switch (updateType.type) {
              case 'wysihtml5':
                  element.data("wysihtml5").editor.setValue(data[updateType.field]);
                  break;
              case 'html':
                  element.html(data[updateType.field]);
                  break;
              case 'value':
                  element.val(data[updateType.field]).trigger(['input', 'change', 'blur']);
                  break;
              case 'select2':
                element.val(data[updateType.field]).trigger("change.select2");

                  // element.select2('val', data[updateType.field]);
                  break;
              case 'select2append':
                if (data[updateType.field]) {
                    // Clear existing options and append new ones
                    element.empty();
                    element.append(data[updateType.field]);
                    element.trigger("change.select2");
                }
                break;
              case 'disabled':
                  element.prop('disabled', updateType.value);
                  break;

                  case 'quill':
                    const editorId = element.attr('id'); // Get the editor ID from the element

                    if (window.Quill && window.Quill[editorId]) {
                      console.log(data[updateType.field]);
                      window.Quill[editorId].root.innerHTML = data[updateType.field];
                      window.Quill[editorId].setText(data[updateType.field]);
                  }

                  break;
                  case 'date':
                    if (element.hasClass('flatpickr-input') && data[updateType.field]) {
                        // Update Flatpickr instance with new date
                        const flatpickrInstance = element[0]._flatpickr;
                        if (flatpickrInstance) {
                            flatpickrInstance.setDate(data[updateType.field], true); // Set the date and trigger change
                        }
                    } else {
                        element.val(data[updateType.field]).trigger(['input', 'change', 'blur']);
                    }
                    break;
                    case 'error':
                    if (data[updateType.field] && data[updateType.field] != "") {
                        element.text(data[updateType.field]).show(); // Show global error if exists
                        $(".submitbuttonerror").prop('disabled', true);
                    }else {
                        element.text(data[updateType.field]).hide();
                        $(".submitbuttonerror").prop('disabled', false);
                    }
                    break;

              // Add more cases as needed
          }
      });
    }

// for ajax call post and get
  window.ajaxCall = ajaxCall;
  window.onSuccess = onSuccess;
  window.updateElements = updateElements;
  window.updateStatesDropdown = updateStatesDropdown;
  window.initializeDataTable = initializeDataTable;

  $(document).ready(function() {
    // Example usage: Initialize DataTable for a specific table with dynamic columns
    // initializeDataTable('#dataTable', '/get-data', [
    //   { data: 'id', name: 'id' },
    //   { data: 'name', name: 'name' },
    //   { data: 'email', name: 'email' },
    //   { data: 'created_at', name: 'created_at' }
    // ]);

    // Form submission
    $('#submitForm').on('submit', function(e) {
      e.preventDefault();

      let form = new FormData(this);

      ajax('/submit-form', 'POST', [
        [handleSuccess, ["response"]]
      ], form);
    });

    function handleSuccess(response) {
      alert(response.message);
      $('#dataTable').DataTable().ajax.reload(); // Reload DataTable after form submission
    }
  });
// example datatable call
// initializeDataTable('#anotherTable', '/another-data-url', [
//     { data: 'id', name: 'id' },
//     { data: 'title', name: 'title' },
//     { data: 'description', name: 'description' }
// ]);
// return response example
// return response()->json([
//     'message' => 'Data saved successfully!',
//     'data' => $yourModel
// ]);
