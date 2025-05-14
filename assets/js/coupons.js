
$(document).ready(function () {

  //Sửa dữ liệu
  $(document).on('click', '.edit-data', function () {
    let id_sua = $(this).data('id_sua');
    $.ajax({
      url: "discount.php/getDiscountById",
      method: "POST",
      data: { id_sua: id_sua },
      success: function (data) {
        console.log(data);
        $('#code').val(data.id);
        $('#namecode').val(data.name);
        $('#percentcode').val(data.discount_percentage);
        $('#firstday').val(data.start_day);
        $('#finishday').val(data.finish_date);
      }
    })
  })
  $(document).on('click', '#btn-edit-submit', function () {
    let id_edit = $('#code').val();
    let name_code = $('#namecode').val();
    let percent_code = $('#percentcode').val();
    let first_day = $('#firstday').val();
    let finish_day = $('#finishday').val();
    $.ajax({
      url: "discount.php/update",
      method: "POST",
      data: { id_edit: id_edit, name_code: name_code, percent_code: percent_code, first_day: first_day, finish_day: finish_day },
      success: function (data) {
        fetch_data();
        alert("Sửa thành công");
      }
    });
  });
  //Xóa dữ liệu
  $(document).on('click', '.del_data', function () {
    let id = $(this).data('id_xoa');
    if (confirm("Bạn muốn xóa")) {
      $.ajax({
        url: "discount.php/delete",
        method: "POST",
        data: { id: id },
        success: function (data) {
          fetch_data();
          alert("Xóa thành công");
        }
      });
    }
  });
  //hiển thị dữ liệu
  function fetch_data() {
    $.ajax({
      url: "discount.php/getList",
      method: "POST",
      success: function (data) {
        $("#load_data").html(data);
      }
    });
  }
  fetch_data();
  // thêm dữ liệu
  $('#btn-submit').click(function () {
    //let dataForm = $('form').serialize();
    var d = new Date();

    var month = d.getMonth() + 1;
    var day = d.getDate();

    var currentDate = d.getFullYear() + '-' +
      (month < 10 ? '0' : '') + month + '-' +
      (day < 10 ? '0' : '') + day;
    let name_code = $('#nameadd').val();
    let percent_code = $('#percentcodeadd').val();
    let start_day = $('#startdayadd').val();
    let end_day = $('#enddayadd').val();
    if ( name_code == '' || percent_code == '' || start_day == '' || end_day == '') {
      alert("Không được bỏ trống các trường");
    } else if (percent_code < 1 || percent_code > 90) {
      alert("% giảm giá không phù hợp");
    } else if (start_day > end_day) {
      alert("Ngày bắt đầu không được lớn hơn ngày kết thúc");
    }
    else if (start_day < currentDate) {
      alert("Ngày bắt đầu không hợp lệ");
    }
    else {
      $.ajax({
        url: "discount.php/add",
        method: "POST",
        data: { name_code: name_code, percent_code: percent_code, start_day: start_day, end_day: end_day },
        success: function (data) {
          fetch_data();
          alert("Thêm thành công");
          document.getElementById("form-add-data").reset();
        }
      });
    }
  });
});
