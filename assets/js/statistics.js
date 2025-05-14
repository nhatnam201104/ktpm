function chart() {
     var sd = new Date();

     var sDate = 2022 + '-' + 10 + '-' + 10;
     let start_day = sDate;
     var d = new Date();

     var month = d.getMonth() + 1;
     var day = d.getDate();

     var currentDate = d.getFullYear() + '-' +
          (month < 10 ? '0' : '') + month + '-' +
          (day < 10 ? '0' : '') + day;
     let end_day = currentDate;
     totalSum(start_day, end_day);
     $('#listProSold').html(``);

     listProSold(start_day, end_day);
     $.ajax({
          url: "statistics.php/displayChart",
          method: "POST",
          data: { start_day: start_day, end_day: end_day },
          success: function (response) {
               let indexX = 0;
               let xValues = [];
               let yValues = [];
               let indexobj = 0;
               let obj = [];
               for (var key in response) {
                    obj[indexobj] = response[key];
                    indexobj++;
               }
               for (var index in obj) {
                    xValues[indexX] = obj[index]['created_date'];
                    yValues[indexX] = obj[index]['total_money'];
                    indexX++;
               }
               console.log(xValues);
               new Chart("myChart", {
                    type: "line",
                    data: {
                         labels: xValues,
                         datasets: [{
                              fill: false,
                              lineTension: 0,
                              backgroundColor: "rgba(0,0,255,1.0)",
                              borderColor: "rgba(0,0,255,0.1)",
                              data: yValues
                         }]
                    },
                    options: {
                         legend: { display: false },
                         title: {
                              display: true,
                              text: "Thống kê theo thời gian"
                         },
                    }
               });
          }
     });
};
function totalSum(start_day, end_day) {
     $.ajax({
          url: "statistics.php/totalSum",
          method: "POST",
          data: { start_day: start_day, end_day: end_day },
          success: function (response) {
               $('#totalSum').val(response);
          }
     })
}
function listProSold(start_day, end_day) {
     // if (limit == null || limit == '') {
     //      limit = 10;
     // }
     // if (limit < 1) {
     //      confirm('Lấy phải lớn hơn 0!');
     // } else {
          $.ajax({
               url: "statistics.php/listProSold",
               method: "POST",
               data: { start_day: start_day, end_day: end_day},
               success: function (response) {
                    $('#listProSold').append(response);
               }
          })
     // }
}
function listProSoldCate(start_day, end_day, selectCategory) {
     // if (limit == null || limit == '') {
     //      limit = 10;
     // }
     // if (limit < 1) {
     //      confirm('Lấy phải lớn hơn 0!');
     // } else {
          $.ajax({
               url: "statistics.php/listProSoldCate",
               method: "POST",
               data: { start_day: start_day, end_day: end_day, selectCategory: selectCategory},
               success: function (response) {
                    $('#listProSold').append(response);
               }
          })
     // }
}
function listProSoldAllCate(start_day, end_day) {
     // if (limit == null || limit == '') {
     //      limit = 10;
     // }
     // if (limit < 1) {
     //      confirm('Lấy phải lớn hơn 0!');
     // } else {
          $.ajax({
               url: "statistics.php/listProSoldAllCate",
               method: "POST",
               data: { start_day: start_day, end_day: end_day},
               success: function (response) {
                    $('#listProSold').append(response);
               }
          })
     // }
}
function categoryInSelect() {
     let pull_select = 1;
     $('#select-category').html(``);
     $('#select-category').html(`<option value="-1">Chọn danh mục</option>
     <option value="-2">Tất cả danh mục</option>`);
     $.ajax({
          url: "../supplier/supplier.php/displayCategoryInSelect",
          method: "POST",
          data: { pull_select: pull_select },
          success: function (data) {
               data.map((data, index_display) => {
                    $('#select-category').append(`<option value=${data.id} id="category-id"> ${data.name}</option>`);
               })
          }
     })
};
$(document).ready(function () {
     categoryInSelect();
     chart();
     $('#statistics').click(function () {
          console.log("ok");
          let selectCategory = $('#select-category').val();
          let start_day = $('#s-day').val();
          let end_day = $('#e-day').val();
          if (!start_day || !end_day) {
               alert("Xin hãy nhập đúng ngày");
           }
          else {
               // let limit = $('#limit').val();
               if (selectCategory == -1) {
                    totalSum(start_day, end_day);
                    $('#listProSold').html(``);
                    listProSold(start_day, end_day);
                    $.ajax({
                         url: "statistics.php/displayChart",
                         method: "POST",
                         data: { start_day: start_day, end_day: end_day },
                         success: function (response) {
                              let indexX = 0;
                              let xValues = [];
                              let yValues = [];
                              let indexobj = 0;
                              let obj = [];
                              for (var key in response) {
                                   obj[indexobj] = response[key];
                                   indexobj++;
                              }
                              for (var index in obj) {
                                   xValues[indexX] = obj[index]['created_date'];
                                   yValues[indexX] = obj[index]['total_money'];
                                   indexX++;
                              }
                              console.log(xValues);
                              // const context = canvas.getContext('2d');
                              // context.clearRect(0, 0, canvas.width, canvas.height);
                              new Chart("myChart", {
                                   type: "line",
                                   data: {
                                        labels: xValues,
                                        datasets: [{
                                             fill: false,
                                             lineTension: 0,
                                             backgroundColor: "rgba(0,0,255,1.0)",
                                             borderColor: "rgba(0,0,255,0.1)",
                                             data: yValues
                                        }]
                                   },
                                   options: {
                                        legend: { display: false },
                                        title: {
                                             display: true,
                                             text: "Thống kê theo thời gian"
                                        },
                                   }
                              });
                         }
                    });
               } else if (selectCategory == -2) {
                    let totalSum = 0;
                    $.ajax({
                         url: "statistics.php/displayChartAllCate",
                         method: "POST",
                         data: { start_day: start_day, end_day: end_day },
                         success: function (response) {
                              let indexX = 0;
                              let xValues = [];
                              let yValues = [];
                              let indexobj = 0;
                              let obj = [];
                              console.log(response);
                              for (var key in response) {
                                   obj[indexobj] = response[key];
                                   indexobj++;
                              }
                              for (var index in obj) {
                                   xValues[indexX] = obj[index]['name'];
                                   yValues[indexX] = obj[index]['total_money'];
                                   totalSum = totalSum + Number(yValues[indexX]);
                                   indexX++;
                              }
                              $('#totalSum').val(totalSum);
     
                              new Chart("myChart", {
                                   type: "bar",
                                   data: {
                                        labels: xValues,
                                        datasets: [{
                                             backgroundColor: "rgba(0,0,255,1.0)",
                                             borderColor: "rgba(0,0,255,0.1)",
                                             data: yValues
                                        }]
                                   },
                                   options: {
                                        legend: { display: false },
                                        title: {
                                             display: true,
                                             text: "Thống kê tất cả danh mục sản phẩm"
                                        },
                                   }
                              });
                         }
                    });
                    $('#listProSold').html(``);
                    listProSoldAllCate(start_day, end_day);
               }
               else {
                    let totalSum = 0;
                    $.ajax({
                         url: "statistics.php/displayChartCate",
                         method: "POST",
                         data: { start_day: start_day, end_day: end_day, selectCategory: selectCategory },
                         success: function (response) {
                              let indexX = 0;
                              let xValues = [];
                              let yValues = [];
                              let indexobj = 0;
                              let obj = [];
                              console.log(response);
                              for (var key in response) {
                                   obj[indexobj] = response[key];
                                   indexobj++;
                              }
                              for (var index in obj) {
                                   xValues[indexX] = obj[index]['name'];
                                   yValues[indexX] = obj[index]['total_money'];
                                   totalSum = totalSum + Number(yValues[indexX]);
                                   indexX++;
                              }
                              $('#totalSum').val(totalSum);
                              // const context = canvas.getContext('2d');
                              // context.clearRect(0, 0, canvas.width, canvas.height);
                              new Chart("myChart", {
                                   type: "bar",
                                   data: {
                                        labels: xValues,
                                        datasets: [{
                                             backgroundColor: "rgba(0,0,255,1.0)",
                                             borderColor: "rgba(0,0,255,0.1)",
                                             data: yValues
                                        }]
                                   },
                                   options: {
                                        legend: { display: false },
                                        title: {
                                             display: true,
                                             text: "Thống kê theo danh mục sản phẩm"
                                        },
                                   }
                              });
                         }
                    });
                    $('#listProSold').html(``);
                    listProSoldCate(start_day, end_day, selectCategory);
               }
          }
     })
});