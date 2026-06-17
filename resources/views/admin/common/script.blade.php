<script src="{{ asset('admin/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/assets/node_modules/toastr/build/toastr.min.js') }}"></script>

<script>
    $('.show_confirm').click(function(event){
            let form = $(this).closest('form');

            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                    Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                    )
                }
                })
        })
</script>
<script>
    $('.show_update').click(function(event){
            let form = $(this).closest('form');
            event.preventDefault();
            Swal.fire({
            title: "Do you want to save the changes?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Save",
            denyButtonText: `Don't save`
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.submit();
                Swal.fire("Saved!", "", "success");
            } else if (result.isDenied) {
                Swal.fire("Changes are not saved", "", "info");
            }
            });
        })
</script>
<script>
    @if(Session::has('message'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
    }
            toastr.success("{{ session('message') }}");
    @endif

    @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true
    }
            toastr.warning("{{ session('warning') }}");
    @endif
  </script>

<script>
    $(document).ready(function() {
        function formatUser(user) {
            if (!user.id) {
                return user.text;
            }

            var baseUrl = $(user.element).data('image');
            var $user = $(
                '<span><img src="' + baseUrl + '" class="img-circle" style="width:21px; height: 23px; margin-right:10px;" />' + user.text + '</span>'
            );
            return $user;
        };

        $('#user_id').select2({
            templateResult: formatUser,
            templateSelection: formatUser
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#vori, #ana, #roti, #point').on('input', function() {
            var vori = $('#vori').val() || 0;
            var ana = $('#ana').val() || 0;
            var roti = $('#roti').val() || 0;
            var point = $('#point').val() || 0;

            $.ajax({
                url: '{{ route('convert.to.gram') }}',
                method: 'POST',
                data: {
                    vori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#gram').val(response.grams.toFixed(3));
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        });
        $('#added_bhori, #added_ana, #added_roti, #added_point').on('input', function() {
            var vori = $('#added_bhori').val() || 0;
            var ana = $('#added_ana').val() || 0;
            var roti = $('#added_roti').val() || 0;
            var point = $('#added_point').val() || 0;

            $.ajax({
                url: '{{ route('convert.to.gram') }}',
                method: 'POST',
                data: {
                    vori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#added_gram').val(response.grams.toFixed(3));
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('input[name="customer_type"]').click(function() {
            var inputValue = $(this).val();

            if (inputValue == "new_customer") {
                $(".custom").addClass('d-none');
                $("#customer").removeClass('d-none');
            } else {
                $(".custom").removeClass('d-none');
                $("#customer").addClass('d-none');
            }
        });

        $('#form_sub').on('click', function(event) {
            event.preventDefault();

            if ($('input[name="location"]').length) {
                const locationRadios = document.getElementsByName('location');
                let locationSelected = false;
                for (const radio of locationRadios) {
                    if (radio.checked) {
                        locationSelected = true;
                        break;
                    }
                }

                if (!locationSelected) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select (দোকান or গুদাম).",
                    });
                    return; // Stop form submission
                }
            }
            if ($('#category_id').length) { // Check if the select element exists
                if (!$('#category_id').val()) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a Category.",
                    });
                    return;
                }
                if (!$('#product_id').val()) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a Product.",
                    });
                    return;
                }
            } else if ($('.category-select').length) {
                let allCategoriesSelected = true;
                $('.category-select').each(function() {
                    if (!$(this).val()) {
                        allCategoriesSelected = false;
                    }
                });
                if (!allCategoriesSelected) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a Category for all items.",
                    });
                    return;
                }

                let allProductsSelected = true;
                $('.product-select').each(function() {
                    if (!$(this).val()) {
                        allProductsSelected = false;
                    }
                });
                if (!allProductsSelected) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a Product for all items.",
                    });
                    return;
                }
            }

            if ($('#old_customer').is(':checked')) {
                var oldCustomerSelected = $('.old').val(); // Correctly select user_id
                if (!oldCustomerSelected) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please select a User.",
                    });
                    return; // Stop form submission
                }
            }
            

            // Collect data from user_form
            let userFormData = '';
            if ($('#new_customer').is(':checked')) {
                userFormData += `<p><strong>Role:</strong> ${$('#role_id option:selected').text()}</p>`;
                userFormData += `<p><strong>Name:</strong> ${$('#user_name').val()}</p>`;
                userFormData += `<p><strong>Phone:</strong> ${$('#user_phone').val()}</p>`;
                userFormData += `<p><strong>Address:</strong> ${$('#address').val()}</p>`;
            } else {
                userFormData += `<p><strong>User:</strong> ${$('#user_id option:selected').text()}</p>`;
            }

            // Collect data from form2
            let form2Data = '';
            $('#form2').find('input, select, textarea').each(function() {
                if ($(this).attr('name') === '_token') {
                    return; // Skip the token field
                }

                if ($(this).is('input[type="radio"]') && !$(this).is(':checked')) {
                    return; // Skip unchecked radio buttons
                }

                if ($(this).attr('name') === 'location' && $(this).is(':checked')) {
                    form2Data += `<p><strong>Location:</strong> ${$(this).val() === 'is_shop' ? 'Shop' : 'Warehouse'}</p>`;
                    return;
                }

                if ($(this).is('select')) {
                    form2Data += `<p><strong>${$(this).attr('name')}:</strong> ${$(this).find('option:selected').text()}</p>`;
                } else {
                    form2Data += `<p><strong>${$(this).attr('name')}:</strong> ${$(this).val()}</p>`;
                }
            });

            // Show confirmation popup with combined data
            Swal.fire({
                title: "Confirm Your Input",
                html: `<div>${userFormData}</div><div>${form2Data}</div>`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, submit it!",
                cancelButtonText: "No, cancel!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Check if new customer is selected
                    if ($('#new_customer').is(':checked')) {
                        if ($('#role_id').val() == 'null') {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Please select Role.",
                            });
                            return;
                        }
                        if (!$('#user_name').val()) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Please insert Name.",
                            });
                            return; // Stop form submission
                        }
                        if (!$('#user_phone').val()) {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Please insert Phone.",
                            });
                            return; // Stop form submission
                        }

                        // Create FormData object
                        var formData = new FormData($('#user_form')[0]);

                        // Append role_id to formData
                        formData.append('role_id', $('#role_id').val());

                        // Submit user_form via AJAX
                        $.ajax({
                            url: $('#user_form').attr('action'),
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response); // Debugging response
                                if (response.user_id) {
                                    // If user form submission is successful, submit form2 with the user_id
                                    $('#form2').append('<input type="hidden" name="user_id" value="' + response.user_id + '">');
                                    $('#form2').submit();
                                } else {
                                    console.error('User ID not found in response');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX error:', error);
                                console.log('Status:', status);
                                console.log('Response:', xhr.responseText);
                            }
                        });
                    } else {
                        
                        // If old customer is selected, just submit form2
                        $('#form2').submit();
                    }
                }
            });
        });
    });
</script>

<script>
    // For Create
        // To Get Product Data
        const getProducts = (category_id, selected = null) => {
            if(window.location.href.includes('/admin/sells')){
                    // console.log(window.location.href);
                    axios.get(`${window.location.origin}/get-products-shop/${category_id}`).then(res => {
                        let products = res.data
                        let element = $('#product_id')
                        element.removeAttr('disabled')
                        element.empty()
                        element.append(`<option>এখানে নির্বাচন করুন</option>`)
                        products.map((product, index) => {
                            // console.log(product)
                            element.append(
                                `<option value="${product.id}" ${selected == product.id ?'selected' : ''}>${product.product_name}</option>`
                            )
                        })
                    })
                }else{
                    axios.get(`${window.location.origin}/get-products/${category_id}`).then(res => {
                        let products = res.data
                        let element = $('#product_id')
                        element.removeAttr('disabled')
                        element.empty()
                        element.append(`<option>এখানে নির্বাচন করুন</option>`)
                        products.map((product, index) => {
                            // console.log(product)
                            element.append(
                                `<option value="${product.id}" ${selected == product.id ?'selected' : ''}>${product.product_name}</option>`
                            )
                        })
                    })
                }
        }
 
        $('#category_id').on('change', function() {
            getProducts($(this).val())
        })
</script>
