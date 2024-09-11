// ajax-functions.js

function initializeDataTable(tableId, ajaxUrl, columns) {
    $(tableId).DataTable({
      processing: true,
      serverSide: true,
      ajax: ajaxUrl,
      columns: columns
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
      error: function(xhr, textStatus, error) {
        console.error('Error:', xhr.responseText);
        console.error('Status:', xhr.statusText);
        console.error('Text Status:', textStatus);
        console.error('Error Thrown:', error);
      },
      success: function(response) {
        functionsOnSuccess.forEach(function(funcArray) {
          funcArray[1] = funcArray[1].map(arg => arg === "response" ? response : arg);
          funcArray[0].apply(this, funcArray[1]);
        });
      }
    });
  }
  // Define the success callback function
  const onSuccess = (response) => {
    if (response.success) {
      if(response.redirect_url) {
        window.location.href = response.redirect_url;
      }else {
        document.getElementById('success-message').innerText = response.message;
      }
    } else {
      $('#error-messages').empty().show();
                    // Check if errors is an array
                    if (Array.isArray(response.errors)) {
                        response.errors.forEach(function(error) {
                            $('#error-messages').append('<div>' + error + '</div>');
                        });
                    } else if (typeof response.errors === 'object') {
                        // Handle errors if response.errors is an object
                        $.each(response.errors, function(field, messages) {
                            if (Array.isArray(messages)) {
                                messages.forEach(function(message) {
                                    $('#error-messages').append('<div>' + message + '</div>');
                                });
                            } else {
                                $('#error-messages').append('<div>' + messages + '</div>');
                            }
                        });
                    } else {
                        $('#error-messages').append('<div>Unknown error format.</div>');
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
                  element.val(data[updateType.field]).trigger('input');
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
