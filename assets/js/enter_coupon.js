//Init value
// let listProduct = [];
// let selectedIdProduct = [];
// function addIdProduct(index, event) {
//   let item = selectedIdProduct.find((element) => element.index === index);
//   item.id = event.target.value;
// }


// function addNumberProduct(index, event) {
//   let item = selectedIdProduct.find((element) => element.index === index);
//   item.number = parseInt(event.target.value);
// }

function getListProduct() {
  $.post("enter_coupon.php/getListProduct", { n: 1 }, (data) => {
    listProduct = data;
  })
}

// function onSubmit() {
//   var d = new Date();

//   var month = d.getMonth() + 1;
//   var day = d.getDate();

//   var currentDate = d.getFullYear() + '-' +
//     (month < 10 ? '0' : '') + month + '-' +
//     (day < 10 ? '0' : '') + day;
//   let data = {
//     supplier_id: $("#select-supplier").val(),
//     user_id: $("#staff-id").val(),
//     created_at: currentDate,
//     product_id: $("#select-name-product").val(),
//     product_qty: $("#quantity-add").val()
//   }
//   $.ajax({
//     url: "enter_coupon.php/add",
//     data: data,
//     method: "POST",
//     success: function (data) {
//       fetch_entercoupon();
//       alert('Thêm thành công');
//     }
//   })
// }
// Hiển thị phiếu nhập
function fetch_entercoupon() {
  $.ajax({
    url: "enter_coupon.php/getList",
    method: "POST",
    success: function (data) {
      $('#load-entercoupon-data').html(data);
    }
  })
}
fetch_entercoupon();
//////////////////////////////////
$(document).ready(function () {
  //
  //Cập nhật thêm sản phẩm
  $('#product-update').on('click', function () {
    // var d = new Date();

    // var month = d.getMonth() + 1;
    // var day = d.getDate();

    // var currentDate = d.getFullYear() + '-' +
    //   (month < 10 ? '0' : '') + month + '-' +
    //   (day < 10 ? '0' : '') + day;
    let data = {
      supplier_id: $("#select-supplier").val(),
      user_id: $("#staff-id").val(),
      //created_at: currentDate,
      product_id: $("#select-name-product").val(),
      product_qty: $("#product-qty").val(),
      enter_price: $("#enter-price").val(),
      profit_margin: $("#product-profit").val()
    };

    console.log(data);
    if(data.product_qty <=0 ||data.product_qty >999999)
    {
      alert('Số lượng không hợp lệ');
    }
    else
    {
      $.ajax({
        url: "enter_coupon.php/add",
        data: data,
        method: "POST",
        success: function (data) {
          fetch_entercoupon();
          alert('Thêm thành công');
        }
      });
    }
  });
  //
  $('#check-to-add-product').on('click', function () {
    var d = new Date();
  var month = d.getMonth() + 1;
  var day = d.getDate();
  var hours = d.getHours();
  var minutes = d.getMinutes();
  var seconds = d.getSeconds();

  var currentDate = d.getFullYear() + '-' +
    (month < 10 ? '0' : '') + month + '-' +
    (day < 10 ? '0' : '') + day + ' ' +
    (hours < 10 ? '0' : '') + hours + ':' +
    (minutes < 10 ? '0' : '') + minutes + ':' +
    (seconds < 10 ? '0' : '') + seconds;
    let data = {
      supplier_id: $("#select-supplier").val(),
      user_id: $("#staff-id").val(),
      created_at: currentDate
    }
    $.ajax({
      url: "enter_coupon.php/addEnterCoupon",
      method: "POST",
      data: data,
      success: function () {
      }
    });
  });
  $("#select-name-product").prop('disabled', true);
  $("#product-qty").prop('disabled', true);
  $("#enter-price").prop('disabled', true);
  $("#product-profit").prop('disabled', true);
  $('#check-to-add-product').on('click', function () {
    $("#select-name-product").prop('disabled', false);
    $("#product-qty").prop('disabled', false);
    $("#enter-price").prop('disabled', false);
    $("#product-profit").prop('disabled', false);
    $("#select-supplier").prop('disabled', true);
    $("#staff-id").prop('disabled', true);

    $.ajax({
      url: "enter_coupon.php/getListProduct",
      method: "POST",
      data: {
        supplier_id: $("#select-supplier").val(),
      },
      success: function (data) {
        listProduct = data;
        data.map((item, index) => {
          $("#select-name-product").append(`<option value=${item.id}>${item.id}-${item.name}</option>`);
        })
      }
    })
  });
  //xác nhận entercoupon
  $(document).on('click', '.del-entercoupon-data', function () {
    let id_xoa = $(this).data('id_xoa');
    let status = $(this).data('status');

    if (confirm("Bạn muốn xác nhận đơn ?")) {
      $.ajax({
        url: "enter_coupon.php/delete",
        method: "POST",
        data: { id_xoa: id_xoa, status:status },
        success: function (data) {
          fetch_entercoupon();
          alert("Xác nhận thành công");
        }
      })
    }
  });

  $(document).on('click', '.del-import-data', function () {
    let id_xoa = $(this).data('id_delete');

    if (confirm("Bạn muốn xóa đơn này ?")) {
      $.ajax({
        url: "enter_coupon.php/delete_import",
        method: "POST",
        data: { id_xoa: id_xoa },
        success: function (data) {
          fetch_entercoupon();
          alert("Xóa thành công");
        }
      })
    }
  });
  // Init list product
  // getListProduct();
  //Hiển thị danh mục sản phẩm trong thẻ select supplier
  $('#btn-add-entercoupon').click(function () {
    // var d = new Date();

    // var month = d.getMonth() + 1;
    // var day = d.getDate();

    // var currentDate = d.getFullYear() + '-' +
    //   (month < 10 ? '0' : '') + month + '-' +
    //   (day < 10 ? '0' : '') + day;
    // $("#enter-day").val(currentDate);
    $.ajax({
      url: "enter_coupon.php/displaySupplierInSelect",
      method: "POST",
      success: function (data) {
        data.map((data, index) => {
          $('#select-supplier').append(`<option value=${data.id} id="supplier-id">${data.id}-${data.name}</option>`);
        });
      }
    });
    $("#select-supplier").prop('disabled', false);
    $("#staff-id").prop('disabled', false);
    $("#select-name-product").prop('disabled', true);
    $("#product-qty").prop('disabled', true);
    $("#enter-price").prop('disabled', true);
    $("#product-profit").prop('disabled', true);
  });
  //Hiển thị sản phẩm trong thẻ select product
  $('#btn-add-entercoupon').click(function () {
  });
  //Hiển thị user trong thẻ select staff-id
  /*
  $('#btn-add-entercoupon').click(function () {
    $.ajax({
      url: "enter_coupon.php/displayUserInSelect",
      method: "POST",
      success: function (data) {
        data.map((data, index) => {
          $('#staff-id').append(`<option value=${data.id} >${data.id}-${data.name}</option>`);
        })
      }
    })
  });*/
  // //Thêm số sản phẩm và hiển thị sản phẩm trong thẻ select select-name-product
  // $(document).on("click", "#quantity-add", function () {
  //   $("#form-product-add").html('');

  //   let n = $("#n-qty").val();
  //   for (let i = 0; i < n; i++) {
  //     let idProduct = {
  //       index: i,
  //       id: listProduct[0].id,
  //       number: 0
  //     }
  //     selectedIdProduct.push(idProduct);
  //     //onchange="addIdProduct(${i}, event)"
  //     //onchange="addNumberProduct(${i}, event)"
  //   }
  //
  //Submit data
  $('#btn-add-entercoupon-submit').on("click", function(){
  });

  document.getElementById('product-profit').addEventListener('input', function () {
    let value = parseInt(this.value);
    if (value > 50) {
        this.value = 50; // Giới hạn lại giá trị
    } else if (value < 1) {
        this.value = 1; // Giới hạn lại giá trị
    }
});
});
//CHI TIẾT PHIẾU NHẬP
//Sửa phiếu nhập
$(document).on('click', '.edit-entercoupon-data', function () {
  let id_sua = $(this).data('id_sua');
  $('#id_sua').val(id_sua);
  $.ajax({
    url: "enter_coupon.php/displaySupplierInSelect",
    method: "POST",
    success: function (data) {
      data.map((data, index) => {
        $('#name-edit-entercoupon-supplier').append(`<option value=${data.id} id="supplier-id">${data.id}-${data.name}</option>`);
      })
    }
  })
  $.ajax({
    url: "enter_coupon.php/displayUserInSelect",
    method: "POST",
    success: function (data) {
      data.map((data, index) => {
        $('#name-edit-entercoupon-staff').append(`<option value=${data.id} id="supplier-id">${data.id}-${data.name}</option>`);
      })
    }
  });
  //Cập nhật phiếu nhập
  $("#btn-editentercoupon-submit").on('click', function () {
    let id = $('#id_sua').val();
    let supplier = $('#name-edit-entercoupon-supplier').val();
    let staff = $('#name-edit-entercoupon-staff').val();
    $.ajax({
      url: "enter_coupon.php/update",
      method: "POST",
      data: { id: id, supplier: supplier, staff: staff },
      success: function (data) {
        fetch_entercoupon();
        alert("Sửa thành công");
      }
    });
  });
});
//Hiển thị chi tiết phiếu nhập
$(document).on('click', '.view-data', function () {
  $("#form-view-entrydetails-container").html(``);
  let id_view = $(this).data('id_view');
  $.ajax({
    url: "enter_coupon.php/viewEntryDetails",
    method: "POST",
    data: { id_view: id_view },
    success: function (data) {
      $('#form-view-entrydetails-container').append(data);
    }
  })
});