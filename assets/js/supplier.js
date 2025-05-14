$(document).ready(function () {
  //Xem danh mục sản phẩm cung cấp
  $(document).on('click','.view-data',function(){
    let id_view=$(this).data('id_view');
    $.ajax({
      url:"supplier.php/viewCategorySupply",
      method:"POST",
      data:{id_view:id_view},
      success:function(data){
        $('#view-supplier-category').html(data);
      }
    })
  })
  // Sửa nhà cung cấp
  $(document).on('click', '.edit-supplier-data', function () {
    let id = $(this).data('id_sua');
    $.ajax({
      url: "supplier.php/getSupplierById",
      method: "POST",
      data: { id: id },
      success: function (data) {
        $('#id-edit-supplier').val(data.id);
        $('#name-edit-supplier').val(data.name);
        $('#address-edit-supplier').val(data.address);
        $('#shipping-edit-fee').val(data.shipping_fee);
        $('#discount-edit').val(data.discount);
      }
    })
  });
  $(document).on('click', '#btn-editsupplier-submit', function () {
    let id = $('#id-edit-supplier').val();
    let name_supplier = $('#name-edit-supplier').val();
    let address_supplier = $('#address-edit-supplier').val();
    let shipping_fee = $('#shipping-edit-fee').val();
    let discount_percent = $('#discount-edit').val();

    $.ajax({
      url: "supplier.php/update",
      method: "POST",
      data: { id: id, name_supplier: name_supplier, address_supplier: address_supplier, shipping_fee: shipping_fee, discount_percent: discount_percent },
      success: function (data) {
        displaysupplier();
        alert("Sửa thành công");
        displaysupplier();
      }
    });
  });
  // Hiển thị dữ liệu
  function displaysupplier() {
    $.ajax({
      url: "supplier.php/getList",
      method: "POST",
      success: function (data) {
        $("#load-supplier-data").html(data)
      }
    });
  };
  displaysupplier();
  //Xóa nhà cung cấp
  $(document).on('click', '.del-supplier-data', function () {
    let id = $(this).data('id_xoa');
    let status = $(this).data('status');
    if (confirm("Bạn muốn thay đổi trạng thái ?")) {
      $.ajax({
        url: "supplier.php/delete",
        method: "POST",
        data: { id: id, status:status },
        success: function (data) {
          alert('Xác nhận nhà cung cấp thành công');
          displaysupplier();
        }
      })
    }
  });
  // Hiển thị danh mục sản phẩm trong thẻ select-catesup
  $(document).on('click', '.option-data', function () {
    let pull_select = 1;
    let id = $(this).data('id_option');
    $.ajax({
      url: "supplier.php/displayCategoryInSelect",
      method: "POST",
      data: { pull_select: pull_select },
      success: function (data) {
        $('#NCC').val(id);
        data.map((data, index_display) => {
          $('#select-catesup').append(`<option value=${data.id} id="category-id"> ${data.name}</option>`);
        })
      }
    })
  });
  // Submit danh mục sản phẩm trong thẻ select catesup
  $('#btn-add-catesup-submit').click(function () {
    let supplier_id = $('#NCC').val();
    let category_id = $('#select-catesup').val();
    $.ajax({
      url: "supplier.php/addcatesup",
      method: "POST",
      data: { supplier_id: supplier_id, category_id: category_id },
      success: function (data) {
        $('#select-catesup').html(``);
        alert("Thêm danh mục sản phẩm thành công");
        displaysupplier();
      }
    })
  });
  // Hiển thị danh mục sản phẩm trong thẻ select
  $('#btn-add-supplier').click(function () {
    let pull_select = 1;
    $.ajax({
      url: "supplier.php/displayCategoryInSelect",
      method: "POST",
      data: { pull_select: pull_select },
      success: function (data) {
        console.log(data);
        data.map((data, index_display) => {
          $('#select').append(`<option value=${data.id} id="category-id"> ${data.name}</option>`);
        })
      }
    })
  });
  //Thêm nhà cung cấp
  $('#btn-addsupplier-submit').click(function () {
    let name_supplier = $('#name-supplier').val();
    // Regex chỉ cho phép chữ cái và khoảng trắng
    const nameRegex = /^[a-zA-Z\s]+$/;
    if (name_supplier === "") {
        alert("Tên nhà cung cấp không được để trống!");
        return;
    }
    if (!nameRegex.test(name_supplier)) {
        alert("Tên nhà cung cấp không được chứa số hoặc ký tự đặc biệt!");
        return;
    }

    let address_supplier = $('#address-supplier').val();
    const addressRegex = /^[^\d]*$|^[^a-zA-Z\s]+$/;
    if (address_supplier === "") {
      alert("Địa chỉ không được để trống!");
      return;
    }
    if (addressRegex.test(address_supplier)) {
        alert("Địa chỉ không hợp lệ! Địa chỉ phải có ít nhất một ký tự chữ.");
        return;
    }

    let shipping_fee = $('#shipping-fee').val();
    let discount_percent = $('#discount').val();
    let category_id = $('#select').val();
    if (category_id === "") {
      alert("Tên nhà cung cấp không được để trống!");
      return;
  }
    $.ajax({
      url: "supplier.php/add",
      method: "POST",
      data: { name_supplier: name_supplier, address_supplier: address_supplier, shipping_fee: shipping_fee, discount_percent: discount_percent, category_id: category_id },
      success: function (data) {
        console.log(data);
        $('#select').html(``);
        displaysupplier();
      }
    });
  });
});
