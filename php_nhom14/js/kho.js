

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
    $(".mark-form").find('.form-title').text("Sửa thông tin kho")
    bindData(val)
  }

  /**
   * Bind dữ liệu lên form
   * @param {*} val 
   */
  function bindData(val) {
    $("#StockId").val(val.Id)
    $("#StockName").val(val.StockName)
    $("#Address").val(val.Address)
  }

  function clearForm () {
    $("#StockId").val(undefined)
    $("#StockName").val(undefined)
    $("#Address").val(undefined)
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
    $(".mark-form").find('.form-title').text("Thêm kho")
    $(".mark-form").css("display", "block");
  })



