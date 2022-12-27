$(document).ready(function () {
    //$(".nav-item").removeClass("active");
    //$(".nav-link").removeClass("active");
    $("#section_table").DataTable();
    $("#category_table").DataTable();
    $("#brand_table").DataTable();
    $("#product_table").DataTable();

    //check admin password is correct or not
    $("#current_password").keyup(function () {
        var current_password = $("#current_password").val();
        //alert(current_password);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/check-current-password",
            data: { current_password: current_password },
            success: function (resp) {
                //alert(resp);
                if (resp == "false") {
                    $("#check_password").html(
                        "<font color = 'red'> Current Password is Incorrect!</font>"
                    );
                } else if (resp == "true") {
                    $("#check_password").html(
                        "<font color = 'green'> Current Password is Correct!</font>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //Update Admin Status
    // $(document).on("click", ".updateAdminStatus", function () {
    //     var status = $(this).children("label").attr("status");
    //     var admin_id = $(this).attr("admin_id");

    //     $.ajax({
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         type: "post",
    //         url: "/admin/update-admin-status",
    //         data: { status: status, admin_id: admin_id },
    //         success: function (resp) {
    //             //alert(resp);
    //             if (resp["status"] == 0) {
    //                 $("#admin-" + admin_id).html(
    //                     "<label class='badge badge-danger' status='Inactive'>Inactive</label>"
    //                 );
    //             } else if (resp["status"] == 1) {
    //                 $("#admin-" + admin_id).html(
    //                     "<label class='badge badge-success' status='Active'>Active</label>"
    //                 );
    //             }
    //         },
    //         error: function () {
    //             alert("Error");
    //         },
    //     });
    // });

    //Update Section Status
    $(document).on("click", ".updateSectionStatus", function () {
        var status = $(this).children("label").attr("status");
        var section_id = $(this).attr("section_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-section-status",
            data: { status: status, section_id: section_id },
            success: function (resp) {
                //alert(resp);
                if (resp["status"] == 0) {
                    $("#section-" + section_id).html(
                        "<label class='badge badge-danger' status='Inactive'>Inactive</label>"
                    );
                } else if (resp["status"] == 1) {
                    $("#section-" + section_id).html(
                        "<label class='badge badge-success' status='Active'>Active</label>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });
    //Update Brand Status
    $(document).on("click", ".updateBrandStatus", function () {
        var status = $(this).children("label").attr("status");
        var brand_id = $(this).attr("brand_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-brand-status",
            data: { status: status, brand_id: brand_id },
            success: function (resp) {
                //alert(resp);
                if (resp["status"] == 0) {
                    $("#brand-" + brand_id).html(
                        "<label class='badge badge-danger' status='Inactive'>Inactive</label>"
                    );
                } else if (resp["status"] == 1) {
                    $("#brand-" + brand_id).html(
                        "<label class='badge badge-success' status='Active'>Active</label>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });
    //Update Category Status
    $(document).on("click", ".updateCategoryStatus", function () {
        var status = $(this).children("label").attr("status");
        var category_id = $(this).attr("category_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-category-status",
            data: { status: status, category_id: category_id },
            success: function (resp) {
                //alert(resp);
                if (resp["status"] == 0) {
                    $("#category-" + category_id).html(
                        "<label class='badge badge-danger' status='Inactive'>Inactive</label>"
                    );
                } else if (resp["status"] == 1) {
                    $("#category-" + category_id).html(
                        "<label class='badge badge-success' status='Active'>Active</label>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });
    //Update Product Status
    $(document).on("click", ".updateProductStatus", function () {
        var status = $(this).children("label").attr("status");
        var product_id = $(this).attr("product_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "post",
            url: "/admin/update-product-status",
            data: { status: status, product_id: product_id },
            success: function (resp) {
                //alert(resp);
                if (resp["status"] == 0) {
                    $("#product-" + product_id).html(
                        "<label class='badge badge-danger' status='Inactive'>Inactive</label>"
                    );
                } else if (resp["status"] == 1) {
                    $("#product-" + product_id).html(
                        "<label class='badge badge-success' status='Active'>Active</label>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    //Delete section,category,brand,product Status
    // $(document).on("click", ".confirmDelete", function () {
    //     var module = $(this).attr("module");
    //     var module_id = $(this).attr("module_id");
    //     alert(module_id);

    //     const swalWithBootstrapButtons = Swal.mixin({
    //         customClass: {
    //             confirmButton: "btn btn-success",
    //             cancelButton: "btn btn-danger",
    //         },
    //         buttonsStyling: false,
    //     });

    //     swalWithBootstrapButtons
    //         .fire({
    //             title: "Are you sure?",
    //             text: "You won't be able to revert this!",
    //             icon: "warning",
    //             showCancelButton: true,
    //             confirmButtonText: "Yes, delete it!",
    //             cancelButtonText: "No, cancel!",
    //             reverseButtons: true,
    //         })
    //         .then((result) => {
    //             if (result.isConfirmed) {
    //                 swalWithBootstrapButtons.fire(
    //                     "Deleted!",
    //                     "Your file has been deleted.",
    //                     "success"
    //                 );
    //                 window.location = module + "-delete/" + module_id;
    //             } else if (
    //                 /* Read more about handling dismissals below */
    //                 result.dismiss === Swal.DismissReason.cancel
    //             ) {
    //                 swalWithBootstrapButtons.fire(
    //                     "Cancelled",
    //                     "Your file is safe :)",
    //                     "error"
    //                 );
    //             }
    //         });
    // });
    // $("#section_id").change(function () {
    //     var section_id = $(this).val();
    //     $.ajax({
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         type: "get",
    //         url: "/admin/append-categories-level",
    //         data: { section_id: section_id },
    //         success: function (resp) {
    //             $("#appendCategoriesLevel").html(resp);
    //         },
    //         error: function () {
    //             alert("error");
    //         },
    //     });
    // });
});
