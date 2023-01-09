

let typeForm = "insert"

/**
 * Đóng form \
 */
  $(".close-form").click(() => {
    $(".mark-form").css("display", "none");
    clearForm()
  })

  /**
   * Mở form sửa sản phẩm
   * @param {*} val 
   */
  function openForm(val) {
    typeForm = "update"
    document.cookie = `typeForm=${typeForm}`;
    $(".mark-form").css("display", "block");
    $(".mark-form").find('.form-title').text("Sửa thông tin nhà phân phối")
    bindData(val)
  }

  /**
   * Bind dữ liệu lên form
   * @param {*} val 
   */
  function bindData(val) {
    $("#SupplierId").val(val.Id)
    $("#SupplierName").val(val.SuplierName)
    $("#Address").val(val.Address)
    $('#PhoneNumber').val(val.PhoneNumber)
    $('#Email').val(val.Email)
  }

  function clearForm () {
    $("#SupplierId").val(undefined)
    $("#SupplierName").val(undefined)
    $("#Address").val(undefined)
    $('#PhoneNumber').val(undefined)
    $('#Email').val(undefined)
  }


  /**
   * Lưu data
   */
  $(".btn-save").click( (e) => {
    e.preventDefault();

    if(validationController()) {
      $("#form").submit()
      // setTimeout(() => {
      //   $("#search").submit()
      // }, 500)

      clearForm()
    } 
  })

  /**
   * Validate các control trong form
   */
  function validationController () {


    let control = $(".require").parent().find('input, textarea, select')

    let readyToSend = true

    control.each((index,item)  => {
      if(!$(item).val()) {
        readyToSend = false
        $(item).parent().parent().find('.error-mess').html(`<span style='color: red;'>${$(item).parent().parent().find('.require').text()} không được để trống</span>`);
      } else {
        $(item).parent().parent().find('.error-mess').html('');
      }
    });

    return readyToSend
  }

  /**
   * Mở form thêm sản phẩm
   */
  $(".btn-add").click((e) => {
    clearForm()
    typeForm = "insert"
    document.cookie = `typeForm=${typeForm}`;
    $(".btn-save").attr("name",typeForm)
    $(".mark-form").find('.form-title').text("Thêm nhà phân phối")
    $(".mark-form").css("display", "block");
  })



