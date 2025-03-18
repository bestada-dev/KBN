// ------------------------------------------
// ----------- maintenane by @sobari ------------
// -----------------------------------------

const dir = console.dir;
const log = console.log;
const warn = console.warn
const error = console.error;

const _ = {
    SUCCESS: 'success',
    DELETE: 'delete',
    ERROR: 'error',
    LOADING: 'Loading'
};
const _userCurrent = JSON.parse(localStorage.getItem('user') || '{}');

var table, initCompleteCalled = false; // Flag to track if initComplete has been called;

function getProjectId() {
    // Create a URL object with the current page URL
    const url = new URL(window.location.href);

    // Use URLSearchParams to get the value of 'project_id'
    const params = new URLSearchParams(url.search);
    return params.get('project_id');
}

function getCurrentToken() {
    const token = localStorage.getItem('token');
    const url = window.location.href;

    // Check if URL contains 'report'
    if (url.includes('report') && !url.includes('edit') && !url.includes('create') && !url.includes('report/kpi-recap')) {

        if (localStorage.getItem('user')) {
            const isAdmin = JSON.parse(localStorage.getItem('user'))['is_admin'];
            if (!url.includes('report/report-list') && !getProjectId()) {
                window.location.href = "/report/report-list";
            }
        }

        // If URL contains 'report', add projectId to the result
        return {
            token: token,
            project_id: getProjectId() // Get project_id from the URL
        };
    } else {
        // If URL does not contain 'report', return token only
        return {
            token: token
        };
    }
}


const __isBtnSaveOnProcessing = (elmBtnSave, isProcess = false) => {
    if (isProcess) {
        elmBtnSave.innerHTML = 'Saving ' + ___iconLoading();
        elmBtnSave.disabled = true;
    } else {
        elmBtnSave.innerHTML = 'Save';
        elmBtnSave.disabled = false;
    }
}

function initializeSelect2($elm = null) {
    if ($elm) {
        $($elm).select2();
        $($elm).on('select2:close', function (e) {
            $(this).valid();
        })
    } else {
        $('select').select2();
        $('select').on('select2:close', function (e) {
            $(this).valid();
        })
    }
}

function initializeDatepicker() {
    $('.init-datepicker').datepicker(dp_options);

    // Add custom class when input gains focus
    $(".init-datepicker").on('focus', function () {
        $('.datepicker').addClass('datepicker-custom-1');
    });

    // Handle button click to show the date picker and add custom class
    $('.datepicker-trigger').on('click', function () {
        var $input = $(this).siblings('.init-datepicker');
        // Show the date picker
        $input.datepicker('show');
        $('.datepicker').addClass('datepicker-custom-1');
    });
}


function refreshDT(e) {
    if (e) {
        e.preventDefault()
    }
    table ?.ajax.reload(null, false);
}

const propModalPreventClick = {
    backdrop: 'static',
    keyboard: false,
    show: false
}

const propDatatable = {
    pageLength: 5,
    processing: true,
    deferRender: true,
    pagingType: "simple",
    dom: '<"toolbar">frtip',
    info: false,
    autoWidth: false,
    columnDefs: [{
        searchable: false,
        orderable: false,
        targets: [0]
    }],
    language: {
        lengthMenu: "Display _MENU_ records per page",
        zeroRecords: "Tidak ada data",
        info: "Showing page _PAGE_ of _PAGES_",
        infoEmpty: "No records available",
        infoFiltered: "(filtered from _MAX_ total records)"
    }
}

const propertyDB = {
    scrollX: true,
    pageLength: 15,
    processing: true,
    bLengthChange: false,
    search: false,
    bFilter: false,
    serverSide: true,
    orderCellsTop: true,
    fixedHeader: true,
    order: [
        [0, 'DESC']
    ],
    //   "ordering": false,    // Disable ordering for all columns
    //     "searching": false,   // Disable search functionality
    //     "lengthChange": false, // Disable the "Show entries" dropdown
    //     "paging": true,       // Enable pagination
    "language": {
        "paginate": {
            "previous": "<i class='bi bi-chevron-left'></i>", // Bootstrap icon for previous
            "next": "<i class='bi bi-chevron-right'></i>" // Bootstrap icon for next
        }
    }
}

const optGraph = {
    fontName: 'Arial',
    height: 300,
    fontSize: 12,
    animation: {
        duration: 600,
        easing: "out",
        startup: true
    },
    chartArea: {
        left: '10%',
        width: '100%',
        height: 260
    },
    backgroundColor: 'transparent',
    tooltip: {
        textStyle: {
            fontName: 'Arial',
            fontSize: 13
        },
        isHtml: true
    },
    // curveType: 'function',
    pointSize: 5,
    pointShape: 'square',
    lineWidth: 1.6,
    vAxis: {
        title: 'Value',
        titleTextStyle: {
            fontSize: 12,
            italic: false,
            color: '#333'
        },
        textStyle: {
            color: '#333'
        },
        baselineColor: '#ccc',
        gridlines: {
            color: '#eee',
            count: 10
        },
        minValue: 0,
        maxValue: 5.0,
        format: '#.##'
    },
    hAxis: {
        textStyle: {
            color: '#333'
        }
    },
    legend: {
        position: 'top',
        alignment: 'center',
        textStyle: {
            color: '#333'
        }
    },
};

function getBaseId(elementId) {
    // Split the ID by the underscore
    let parts = elementId.split('_');

    // Remove the last part (index)
    parts.pop();

    // Join the remaining parts back together
    return parts.join('_');
}
const rulesValidateGlobal = {
    onfocusout: (elm) => {
        return $(elm).valid();
    },
    ignore: [],
    errorClass: "help-inline text-danger",
    errorElement: "span",
    highlight: (elm, errorClass, validClass) => {
        // const elementId = $(elm).attr('id');
        // const baseId = getBaseId(elementId);
        // const container = $(`#${baseId}-container`);
        // if (container) {
        //     container.addClass('has-error');
        // } else {
        if (!($(elm).attr('type') == 'checkbox' || $(elm).attr('type') == 'radio')) {
            $(elm).addClass('has-error');
        }

        if (elm.id === 'password') {
            $('#togglePassword').addClass('has-error');
        }
        if ($(elm).attr("type") == "file") {
            const $dropifyWrapper = $(elm).closest('.dropify-wrapper');
            $dropifyWrapper.addClass('has-error');

            // Check for all file inputs and apply errors based on sibling relationships
            const allFileInputs = $('input[type="file"]');
            allFileInputs.each(function (index) {
                const $input = $(this);
                // debugger

                // Use siblings and other criteria to add or remove error classes
                const hasError = !$input.val(); // Simple check if input is empty
                if (hasError && $input.attr('required') === 'true') {
                    $input.siblings('.help-inline').text('This field is required').show();
                    $input.closest('.dropify-wrapper').addClass('has-error');
                } else {
                    $input.siblings('.help-inline').text('').hide();
                    $input.closest('.dropify-wrapper').removeClass('has-error');
                }
            });


            const $fileContainer = $(elm).closest('.file-upload-container');
            if ($fileContainer.length) { // Check if $fileContainer exists
                const allFileInputs = $('input[type="file"]'); // Find file inputs within this container
                allFileInputs.each(function () {
                    const $input = $(this);
                    const hasError = !$input.val(); // Check if input is empty

                    // Check if the input is required and empty
                    if (hasError && ($input.attr('required') === 'true' || $input.attr('required') === 'required')) {
                        console.log(hasError, $input.val());
                        // Add error class to the container and show the error message
                        const $errorMessage = $input.siblings('.help-inline'); // Get the sibling error message
                        $errorMessage.text('This field is required').show(); // Show the error message
                        // $fileContainer.addClass('has-error'); // Add error class to the container
                        $input.closest('.file-upload-container').addClass('has-error');
                    } else {
                        // Clear the error message and remove the error class if no error
                        const $errorMessage = $input.siblings('.help-inline'); // Get the sibling error message
                        $errorMessage.text('').hide(); // Hide the error message
                        // $fileContainer.removeClass('has-error'); // Remove error class from the container
                        $input.closest('.file-upload-container').removeClass('has-error');
                        console.log('NOT');
                    }
                });
            }
        }
        if ($(elm).hasClass('init-datepicker')) {
            $(elm).parent().addClass('has-error');
            $(elm).siblings('.datepicker-trigger').addClass('has-error');
        }
        // }
    },
    unhighlight: (elm, errorClass, validClass) => {
        // const elementId = $(elm).attr('id');
        // const baseId = getBaseId(elementId);
        // const container = $(`#${baseId}-container`);
        // if (container) {
        //     container.removeClass('has-error');
        // }
        if (($(elm).attr('type') == 'checkbox' || $(elm).attr('type') == 'radio')) {
            $(elm).parent().parent().nextAll('.help-inline.text-danger').remove();
        } else {
            $(elm).removeClass('has-error');
        }


        if (elm.id === 'password') {
            $('#togglePassword').removeClass('has-error');
        }
        if ($(elm).attr("type") == "file") {
            const $dropifyWrapper = $(elm).closest('.dropify-wrapper');
            $dropifyWrapper.removeClass('has-error');

            // Check if all file inputs are valid and remove collective errors
            const allFileInputs = $('input[type="file"]');
            allFileInputs.each(function (index) {
                const $input = $(this);
                const hasError = !$input.val(); // Simple check if input is empty
                if (hasError && $input.attr('required') === 'true') {
                    $input.siblings('.help-inline').text('This field is required').show();
                    $input.closest('.dropify-wrapper').addClass('has-error');
                } else {
                    $input.siblings('.help-inline').text('').hide();
                    $input.closest('.dropify-wrapper').removeClass('has-error');
                }
            });


            const $fileContainer = $(elm).closest('.file-upload-container');
            if ($fileContainer.length) { // Check if $fileContainer exists
                const allFileInputs = $('input[type="file"]'); // Find file inputs within this container
                allFileInputs.each(function () {
                    const $input = $(this);
                    const hasError = !$input.val(); // Check if input is empty

                    // Check if the input is required and empty
                    if (hasError && ($input.attr('required') === 'true' || $input.attr('required') === 'required')) {
                        console.log(hasError, $input.val());
                        // Add error class to the container and show the error message
                        const $errorMessage = $input.siblings('.help-inline'); // Get the sibling error message
                        $errorMessage.text('This field is required').show(); // Show the error message
                        // $fileContainer.addClass('has-error'); // Add error class to the container
                        $input.closest('.file-upload-container').addClass('has-error');
                    } else {
                        // Clear the error message and remove the error class if no error
                        const $errorMessage = $input.siblings('.help-inline'); // Get the sibling error message
                        $errorMessage.text('').hide(); // Hide the error message
                        // $fileContainer.removeClass('has-error'); // Remove error class from the container
                        $input.closest('.file-upload-container').removeClass('has-error');
                        console.log('NOT');
                    }
                });
            }
        }
        if ($(elm).hasClass('init-datepicker')) {
            $(elm).parent().removeClass('has-error');
            $(elm).siblings('.datepicker-trigger').removeClass('has-error');
        }

    },
    errorPlacement: function (error, elm) {
        const elementId = $(elm).attr('id');
        const baseId = getBaseId(elementId);
        const container = $(`#${baseId}-container`);

        // if(container) {
        //     container.append(error);
        // }
        if ($(elm).attr('type') === 'checkbox' || $(elm).attr('type') === 'radio') {
            // Find the parent container where the error message will be inserted
            let $parentContainer = $(elm).parent().parent(); // Adjust to target the correct container

            // Remove all existing error messages in this context
            $parentContainer.nextAll('.help-inline.text-danger').remove();

            // Create a new error message element
            let errorMessage = "This field is required."; // The error message to display
            let $errorElement = $('<span class="help-inline text-danger">' + errorMessage + '</span>');

            // Insert the new error message directly after the parent container
            $errorElement.insertAfter($parentContainer);
        } else if (elm.hasClass('select2-hidden-accessible')) {
            error.insertAfter(elm.next('.select2.select2-container.select2-container--default'));
        } else if (elm.attr('id') === 'password') {
            error.insertAfter(elm.parent().next());
        } else if ($(elm).hasClass('init-datepicker')) {
            error.insertAfter($(elm).parent());
            //   const error = $('<span class="help-inline text-danger">This field is required</span>');
            // const allFileInputs = $('input[class="init-datepicker"]');
            // debugger;
            //
            // allFileInputs.each(function(index) {
            //     const $input = $(this);
            //     const targetElement = $input.closest('.init-group'); // Adjust to your specific needs
            //     error.insertAfter(targetElement);
            // });
        } else if (elm.attr("type") == "file") {
            // error.insertAfter($(elm).closest('.dropify-wrapper'));

            const allFileInputs = $('input[type="file"]');
            allFileInputs.each(function (index) {
                const $input = $(this);
                const hasError = !$input.val(); // Simple check if input is empty
                if (hasError && $input.attr('required') === 'true') {
                    // $input.siblings('.help-inline').text('This field is required').show();
                    // $input.closest('.dropify-wrapper').addClass('has-error');
                    error.insertAfter($($input));
                    /*debugger*/
                    ;
                } else {
                    // $input.siblings('.help-inline').text('').hide();
                    // $input.closest('.dropify-wrapper').removeClass('has-error');
                }
            });
        } else {
            error.insertAfter(elm);
        }
    },
};

function __getId(name) {
    return document.getElementById(name);
}

function __getClass(name) {
    return document.getElementsByClassName(name);
}

function __querySelectorAll(tag) {
    return document.querySelectorAll(tag);
}

function __querySelector(tag) {
    return document.querySelector(tag);
}

function __toRp(money) {
    return new Intl.NumberFormat('id').format(money)
}

function __gramToKg(gram, total) {
    return ((gram * total) / 1000)
}

function __serializeForm(form) {

    let obj = {};
    let formData = new FormData(form)

    formData.forEach((value, key) => {
        // debugger;
        obj[key] = value
    });
    let json = JSON.stringify(obj);
    return json
    // return new URLSearchParams(new FormData(form)).toString()
}

Number.prototype.format = function (n, x, s, c) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
        num = this.toFixed(Math.max(0, ~~n));

    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
};

function porpertyPOST(body) {
    return {
        headers: __headers(),
        method: 'POST',
        body: JSON.stringify(body)
    }
}


function customPost(data) {
    return Object.assign({}, porpertyPOST(), {
        body: JSON.stringify(Object.assign(getCurrentToken(), data))
    })
}


function __headers() {
    return {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Accept': 'application/json, text/plain, */*',
        "Content-type": "application/json"
    }
}

function __swalSuccess(msg) {
    return swal(msg, {
        icon: "success",
    });
}

function __swalInfo(msg) {
    return swal(msg, {
        icon: "info",
    });
}

function __swalConfirmation(command, title = 'Delete Data?', text = 'The data that you delete will be disappear from the system', confirmButtonText = 'Delete', cancelButtonText = 'No', commandTwo) {
    return Swal.fire({
        title: title,
        text: text,
        // icon: "warning",
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        dangerMode: true,
    }).then((result) => {
        if (result.isConfirmed) {
            if (commandTwo) {
                commandTwo();
            } else {
                command();
            }
        }
    });
}


// function __swalConfirmation(command, title = 'Apakah anda yakin ?', text = 'Apakah anda yakin ingin menghapusnya ?') {
//     return swal({
//         title: title,
//         text: text,
//         icon: "warning",
//         buttons: true,
//         dangerMode: true,
//     })
//     .then(async (willDelete) => {
//         if (willDelete) {
//
//             eval(command);
//
//             /*try {
//
//                 let res = await fetch(__getUrl(`api/service/delete/service-exam/${id_service_exam}`), {
//                     headers: __headers(),
//                     method: 'DELETE',
//                 })
//
//                 let result = await res.json();
//
//                 const {status, message} = result;
//
//                 if(status) {
//                     if ($.fn.DataTable.isDataTable("#daftar-layanan-table")) {
//                         $('#daftar-layanan-table').DataTable().clear().destroy();
//                         dl_dt = $('#daftar-layanan-table').DataTable(propLayananDT).draw();
//                     }
//                     toastr.success(message, { fadeAway: 10000 });
//                 } else {
//                     toastr.error('Ops.. something went wrong!',  { fadeAway: 50000 });
//                     console.error(message)
//                 }
//             } catch (error) {
//                 console.error(error);
//             }*/
//         }
//     })
// }

function __dateYYYYMMDD(value) {
    return moment(new Date(value)).format('YYYY-MM-DD');
}

function __dateYYYYMMDDHis(value) {
    return moment(new Date(value)).format('YYYY-MM-DD H:mm:s');
}

function __dateNOW() {
    return moment(new Date()).format('YYYY-MM-DD');
}

const ___createOpt = (value, title) => {
    let opt = document.createElement("option");
    opt.value = value;
    opt.innerHTML = title;
    return opt;
};

function elm_choose(msg = 'Choose') {
    return `<option value=""> --- ${msg}--- </option>`;
}

// function setDefaultFile() {
//     $('.dropify').each(function() {
//         var $input = $(this);
//         var defaultFile = $input.data('default-file');
//         debugger
//         if (defaultFile) {
//             var dropify = $input.data('dropify');
//             dropify.settings.defaultFile = defaultFile;
//             $input.dropify('destroy').dropify();
//         }
//     });
// }


function setDefaultFile() {
    // $('.dropify').each(function() {
    //     var $input = $(this);
    //     var defaultFile = $input.data('default-file');
    //     debugger
    //     if (defaultFile) {
    //         // Destroy and reinitialize Dropify
    //         $input.dropify('destroy').dropify();
    //
    //         // Add or update the image preview
    //         var $render = $(this).find('.dropify-render')
    //         // $(this).find('.dropify-render > img').attr('src', defautlFile);
    //         var $img = $render.find('img');
    //
    //         if ($img.length === 0) {
    //             // If no img element exists, create one
    //             $img = $('<img>').attr('src', defaultFile).appendTo($render)
    //             $(this).find('.dropify-render > img').attr('src', 'https://i.ytimg.com/vi/0Bdy3nJRTyg/mqdefault.jpg');
    //         } else {
    //             // If img element exists, update its src
    //             // $img.attr('src', defaultFile);
    //             $(this).find('.dropify-render > img').attr('src', defautlFile);
    //         }
    //
    //         // Trigger a Dropify event to update the preview
    //         $input.trigger('change');
    //     }
    // });

    // INIT DROPIFY
    var drInstances = initializeDropify();
    // Rebind the 'beforeClear' event handler
    bindDropifyEvents(true);

    // Update Dropify instance settings and preview
    drInstances.each(function () {
        var drInstance = $(this).data('dropify');
        var defaultFile = $(this).data('default-file');
        /*debugger*/
        ;
        // debugger;
        if (drInstance) {
            drInstance.settings.defaultFile = defaultFile;
            drInstance.resetPreview();
            drInstance.clearElement();
            drInstance.init();

        }
    });

    bindDropifyEvents(false);

    // VERSI 2
    // Function to initialize Dropify
    // function initializeDropify() {
    //     return $('.dropify').dropify();
    // }
    //
    // // INIT DROPIFY
    // var drInstances = initializeDropify();
    //
    // // Rebind the 'beforeClear' event handler
    // bindDropifyEvents(true);
    //
    // // Update Dropify instance settings and preview
    // drInstances.each(function() {
    //     var $dropifyElement = $(this);
    //     var defaultFile = $dropifyElement.data('default-file');
    //
    //     // Destroy the existing Dropify instance
    //     var drInstance = $dropifyElement.data('dropify');
    //     if (drInstance) {
    //         drInstance.destroy();
    //     }
    //
    //     // Reinitialize Dropify with the default file
    //     $dropifyElement.attr('data-default-file', defaultFile);
    //     drInstance = $dropifyElement.dropify({
    //         defaultFile: defaultFile
    //     }).data('dropify');
    //
    //     if (defaultFile) {
    //         // Update the Dropify preview
    //         drInstance.resetPreview();
    //         drInstance.clearElement();
    //         drInstance.settings.defaultFile = defaultFile;
    //         drInstance.init();
    //
    //         // Manually trigger the change event
    //         $dropifyElement.trigger('change');
    //     }
    // });
    //
    // bindDropifyEvents(false);

    // VERSI 3
    //     var drInstances = $('.dropify').dropify(); // Initialize Dropify
    //
    // drInstances.each(function() {
    //     var $dropifyElement = $(this);
    //     var defaultFile = $dropifyElement.data('default-file');
    //
    //     // Destroy the current Dropify instance
    //     var drInstance = $dropifyElement.data('dropify');
    //     drInstance.destroy(); // Destroy it
    //
    //     // Set the data-default-file attribute again
    //     $dropifyElement.attr('data-default-file', defaultFile);
    //
    //     // Reinitialize Dropify
    //     $dropifyElement.dropify({
    //         defaultFile: defaultFile
    //     });
    //
    //     // Manually trigger change and check if it's working
    //     $dropifyElement.trigger('change');
    // });


}


function __iconPlus() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square link-icon"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>'
}

function ___iconLoading(color = '#006BB7', width = '25') {
    return `<svg width=${width} viewBox="-2 -2 42 42" xmlns="http://www.w3.org/2000/svg" stroke=${color} class="w-4 h-4 ml-3">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="4">
                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"></circle>
                        <path d="M36 18c0-9.94-8.06-18-18-18" transform="rotate(114.132 18 18)">
                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform>
                        </path>
                    </g>
                </g>
            </svg>`;
}

function insertAdjHTML(elm_name, position = 'afterbegin', html) {
    __getId(`${elm_name}`).insertAdjacentHTML(`${position}`, `${html}`);
}

function eventListener(elm_name, callback, type = 'click') {
    __getId(`${elm_name}`).addEventListener(type, callback);
}


function __newPromise(api_name, api_url, option = null) {

    // console.log(api_name);

    let process = new Promise(async (resolve, reject) => {

        // console.warn(`Promise started ${api_name}`);

        try {

            let data = null;

            if (option) {
                data = await (await fetch(`${api_url}`, option)).json();
            } else {
                data = await (await fetch(`${api_url}`)).json();
            }

            // console.log(data)
            resolve(data)

        } catch (e) {

            reject(e);

        }
    });

    let json = process.then(
        (msg) => {
            // console.log("Resolved: ", msg);
            return msg;
        },
        (err) => {
            // console.error("Rejected: ", err);
            return err;
        }
    );

    return json;


}



function __modalManage(name, type) {
    switch (type) {
        case 'hide':
            $(`${name}`).modal("hide");
            break;
        case 'show':
            $(`${name}`).modal("show");
            break;
    }
}


function __manageError(elm_name) {
    $(`${elm_name} input.has-error`).removeClass('has-error');
    $(`${elm_name} textarea.has-error`).removeClass('has-error');
    $(`${elm_name} select.has-error`).removeClass('has-error');
    $(`${elm_name} .help-inline.text-danger`).remove()

    $('.dropify-error').empty();
    $('.dropify-errors-container').empty();
}


// function __newPromise(api_name, api_url, option) {

//     let process = new Promise(async (resolve, reject) => {

//         console.warn(`Promise started get ${api_name}`);

//         try{

//             const data =  await fetch(api_url,option);

//             let res = data.json();
//             resolve(res);

//         } catch(e) {

//             reject(e);

//         }
//     });

//     let json = process.then(
//         (result) => {
//             return result;
//         },
//         (err) => {
//             return err;
//         }
//     );

//     return json;
// }


function __resetForm(elm_name) {
    for (const elm of elm_name) {
        elm.value = '';
        if (elm.type == 'select-one') {
            elm.dispatchEvent(new Event("change", {
                bubbles: true,
            }));
        }
    }
}

function __loadForm(elmId, elmForm, data) {
    // debugger;
    elmId.value = data.id;
    for (const key in data) {
        for (const elm of elmForm) {
            if (key == elm.name) {
                if (elm.type === 'select-one' || elm.type === 'checkbox') {
                    log(key, elm.name, data[key]);
                    elm.value = data[key];
                    elm.dispatchEvent(new Event("change", {
                        bubbles: true,
                    }));
                }
                if (elm.type == 'text' || elm.type == 'email' || elm.type == 'hidden' || elm.type == 'textarea') {
                    elm.value = data[key];
                }
                if (elm.type == 'number') {
                    elm.value = data[key] ? parseInt(data[key]) : data[key];
                }
                // Datepicker
                // if(elm.id === 'datepicker') {
                //     $(`#${elm.id}`).datepicker('setDate', data[key]);
                // }
                if ($(elm).hasClass('init-datepicker')) {

                    if ($.fn.datepicker) {
                        // Update all elements with the 'datepicker' class
                        const classSelector = $(elm).attr('class').split(' ').map(cls => `.${cls}`).join('');
                        // debugger;
                        $(classSelector).datepicker('setDate', data[key]);
                    }
                }
            }
        }
    }
}


function sTop() {
    return $('html, body').animate({
        scrollTop: '0px'
    }, 500);
}

function validateForm(rules_validation, elm_name, callback_update, callback_save) {

    return {
        rules: {
            ...rules_validation,
        },
        ...rulesValidateGlobal,
        submitHandler: (form, e) => {
            e.preventDefault();

            const id = __getId(`${elm_name}`) && __getId(`${elm_name}`).value ? __getId(`${elm_name}`).value : null;

            if (id) {
                callback_update(e, id)
            } else {
                callback_save(e)
            }

            return false;
        }
    }
}

function __getParam($param) {
    const url = new URLSearchParams(window.location.search);
    const par = url.get($param)
    return par;
}

// Function to initialize Dropify
function initializeDropify(options = {
    messages: {
        default: 'Drag or drop to select an image',
        replace: 'Replace',
        remove: 'Remove',
        error: 'Error'
    }
}) {

    $('.dropify-error').empty();
    $('.dropify').dropify('destroy');
    $('.dropify').val('');

    // Destroy existing Dropify instances
    $('.dropify').each(function () {
        var drInstance = $(this).data('dropify');
        if (drInstance) {
            drInstance.destroy();
        }
    });

    // Reinitialize Dropify with provided options
    var drInstances = $('.dropify').dropify(options);
    return drInstances;
}

// Function to bind Dropify events
function bindDropifyEvents(skipBeforeClearConfirmation = false) {
    // debugger;
    // Ensure previous handlers are removed
    $('.dropify').off('dropify.fileReady dropify.errors dropify.beforeClear dropify.afterClear');

    // Bind event handlers
    $('.dropify').on('dropify.fileReady', function (event, element) {
        var name = $(this).attr('id');
        $(`#${name}-error`).remove();
    });

    $('.dropify').on('dropify.errors', function (event, element) {
        var name = $(this).attr('id');
        $(`#${name}-error`).remove();
    });

    $('.dropify').on('dropify.beforeClear', function (event, element) {
        if (skipBeforeClearConfirmation) {
            return true;
        }
        return confirm("Do you really want to delete?");
    });

    $('.dropify').on('dropify.afterClear', function (event, element) {
        var name = $(this).attr('id');
        $(`#${name}-error`).remove();
    });
}

// Encode UTF-8 string to Base64
function base64EncodeUnicode(str) {
    // Encode the string as a Uint8Array
    const utf8Bytes = new TextEncoder().encode(str);
    // Convert bytes to a binary string
    let binaryString = '';
    utf8Bytes.forEach(byte => binaryString += String.fromCharCode(byte));
    // Encode the binary string to Base64
    return btoa(binaryString);
}
/*
// Example usage
const jsonString = JSON.stringify(row);
const encodedData = base64EncodeUnicode(jsonString);
console.log("Encoded Data:", encodedData);*/
// Decode Base64 to UTF-8 string
function base64DecodeUnicode(base64Str) {
    // Decode from Base64 to binary string
    const binaryString = atob(base64Str);
    // Convert binary string to Uint8Array
    const utf8Bytes = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
        utf8Bytes[i] = binaryString.charCodeAt(i);
    }
    // Decode bytes to UTF-8 string
    return new TextDecoder().decode(utf8Bytes);
}

// Example usage
// const decodedData = base64DecodeUnicode(encodedData);
// try {
//     const rowData = JSON.parse(decodedData);
//     console.log("Parsed Data:", rowData);
// } catch (error) {
//     console.error("Error parsing JSON:", error);
// }


/*
 // Handle Multiple Array Fields
    function handleArrayFields() {
      $('.add-item').click(function() {
        let container = $(this).siblings('.array-fields');
        let fieldName = container.data('field-name');
        let index = container.find('.array-item').length;
        let newItem = container.find('.array-item').first().clone();
        newItem.find('input').each(function() {
          let name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
          $(this).attr('name', name).val('');
        });
        container.append(newItem);
      });

      $(document).on('click', '.remove-item', function() {
        $(this).closest('.array-item').remove();
      });
    }
    handleArrayFields();

    // Pre-fill data for each array field
    let arrayFieldNames = @json($arrayFieldNames);
    arrayFieldNames.forEach(fieldName => {
      let data = @json(old(fieldName, []));
      if (data.length > 0) {
        data.forEach((item, index) => {
          if (index > 0) {
            $(`.array-fields[data-field-name="${fieldName}"] .add-item`).click();
          }
          Object.keys(item).forEach(key => {
            $(`#${fieldName}_${index}_${key}`).val(item[key]);
          });
        });
      }
    });*/


function setupFileUploadHandlers() {
    // Function to format file size in MB/KB
    function formatFileSize(bytes) {
        const sizeInMB = (bytes / (1024 * 1024)).toFixed(2);
        return sizeInMB < 1 ? (bytes / 1024).toFixed(2) + ' KB' : sizeInMB + ' MB';
    }


    if (document.querySelectorAll('.file-upload-box').length && document.querySelectorAll('.file-input').length && document.querySelectorAll('.reset-button').length && document.querySelectorAll('.view-button').length) {
        // Add event listener to trigger the hidden file input when clicking on the file-upload-box
        document.querySelectorAll('.file-upload-box').forEach(function (box) {
            box.addEventListener('click', function () {
                // Find the closest file input within the same file-upload-container
                const fileInput = this.closest('.file-upload-container').querySelector('.file-input');
                fileInput.click(); // Trigger the file input click
            });
        });


        // Logic to update file name and size
        document.querySelectorAll('.file-input').forEach(function (input) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                const fileNameSpan = this.closest('.file-upload-container').querySelector('.file-name-size');
                const fileSizeSpan = this.closest('.file-upload-container').querySelector('.file-size');

                // Get max file size from data attribute
                const maxSizeInMB = parseInt(this.getAttribute('data-max-file-size'));
                const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // Convert MB to Bytes
                if (file) {
                    // Check file size
                    if (file.size > maxSizeInBytes) {
                        alert('The file is too large. Please upload a file smaller than ' + maxSizeInMB + ' MB.');
                        // Reset the file input and related display elements
                        this.value = ''; // Clear the input
                        fileNameSpan.textContent = 'Pilih file';
                        fileSizeSpan.textContent = '';
                    } else {
                        // Update the file name and size if within the limit
                        fileNameSpan.textContent = file.name;
                        fileSizeSpan.textContent = formatFileSize(file.size);
                    }
                } else {
                    // If no file is selected, reset to default
                    fileNameSpan.textContent = 'Pilih file';
                    fileSizeSpan.textContent = '';
                }
            });
        });

        // Handle reset button functionality
        document.querySelectorAll('.reset-button').forEach(function (button) {
            button.addEventListener('click', function () {
                const fileUploadBox = this.closest('.file-upload-container');
                const fileInput = fileUploadBox.querySelector('.file-input');
                const fileNameElem = fileUploadBox.querySelector('.file-name-size');
                const fileSizeElem = fileUploadBox.querySelector('.file-size');

                // Reset file input and text
                fileInput.value = '';
                const beforee = fileInput;
                fileInput.defaultValue = '';
                const aftertt = fileInput;
                debugger
                fileInput.setAttribute('required', true)
                fileNameElem.textContent = 'Pilih file';
                fileSizeElem.textContent = '';
            });
        });

        // Add event listener to handle the "View" button click
        document.querySelectorAll('.view-button').forEach(function (button) {
            button.addEventListener('click', function () {
                // Get the file input within the same file-upload-container
                const fileInput = this.closest('.file-upload-container').querySelector('.file-input');
                debugger;
                const modalImagePreview = document.getElementById('modalImagePreview');
                const modalVideoPreview = document.getElementById('modalVideoPreview');
                const videoSrc = document.getElementById('videoSource');
                const noImageText = document.getElementById('noImageText');

                // Check if a file is selected
                const file = fileInput.files[0];

                function displayMedia(src, type) {
                    console.log(src, type)
                    if (type.startsWith('image/')) {
                        modalImagePreview.src = src;
                        modalImagePreview.style.display = 'block';
                        modalVideoPreview.style.display = 'none';
                    } else if (type.startsWith('video/')) {
                        videoSrc.src = src; // Set the source of the video
                        modalVideoPreview.load(); // Load the new video source
                        modalVideoPreview.style.display = 'block';
                        modalImagePreview.style.display = 'none';
                    }
                    noImageText.style.display = 'none'; // Hide the "no image" text
                }

                function hideAll() {
                    modalImagePreview.style.display = 'none';
                    modalVideoPreview.style.display = 'none';
                    noImageText.style.display = 'block'; // Show the "no image" text
                }

                // OLD
                // if (file) {
                //     // Use FileReader to read the file as a data URL and display it in the modal
                //     const reader = new FileReader();
                //     reader.onload = function (e) {
                //         modalImagePreview.src = e.target.result; // Set the image source in the modal
                //         modalImagePreview.style.display = 'block'; // Show the image in the modal
                //         noImageText.style.display = 'none'; // Hide the "no image" text
                //     };
                //     reader.readAsDataURL(file);
                // } else {
                //     // If no file is selected, hide the image and show the "no image" text
                //     // modalImagePreview.style.display = 'none';
                //     console.dir(fileInput.defaultValue, 'SOBARI , Contact Developer muhamadsobari198@gmail.com')
                //     if (fileInput.defaultValue) {
                //         modalImagePreview.src = fileInput.defaultValue; // Set the image source in the modal
                //         modalImagePreview.style.display = 'block'; // Show the image in the modal
                //         noImageText.style.display = 'none'; // Hide the "no image" text
                //     } else {
                //         modalImagePreview.src = ''; // Set the image source in the modal
                //         modalImagePreview.style.display = 'none'; // Show the image in the modal
                //         noImageText.style.display = 'block';
                //     }
                // }

                // NEWW
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        displayMedia(e.target.result, file.type); // Display the file
                    };

                    reader.readAsDataURL(file);
                } else {
                    // No file selected, check for default values
                    const defaultValue = fileInput.defaultValue;

                    if (defaultValue) {
                        const validExtensions = {
                            image: {
                                extensions: ['.jpg', '.jpeg', '.png', '.gif'],
                                type: 'image/jpeg' // Default to JPEG for images
                            },
                            video: {
                                extensions: ['.mp4', '.webm', '.ogg'],
                                type: 'video/mp4' // Default to MP4 for videos
                            }
                        };

                        let found = false;

                        // Check for image
                        for (const ext of validExtensions.image.extensions) {
                            if (defaultValue.endsWith(ext)) {
                                displayMedia(defaultValue, validExtensions.image.type);
                                found = true;
                                break;
                            }
                        }

                        // Check for video if not found
                        if (!found) {
                            for (const ext of validExtensions.video.extensions) {
                                if (defaultValue.endsWith(ext)) {
                                    displayMedia(defaultValue, validExtensions.video.type);
                                    found = true;
                                    break;
                                }
                            }
                        }

                        if (!found) {
                            hideAll(); // Invalid file type
                        }
                    } else {
                        hideAll(); // No default value, show "no image" text
                    }
                }

            });
        });
    }
}
