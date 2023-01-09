

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
    $(".mark-form").find('.form-title').text("Sửa thông tin sản phẩm")
    bindData(val)
  }

  /**
   * Bind dữ liệu lên form
   * @param {*} val 
   */
  function bindData(val) {
    $("#productId").val(val.Id)
    $("#productName").val(val.ProductName)
    $("#quantity").val(val.Quantity)
    $('#stock').val(val.StockId)
    $('#subpier').val(val.SuplierId)
    $('#unit').val(val.UnitId)
    $('#price').val(val.UnitPrice)
    $('#description').val(val.Description)
  }

  function clearForm () {
    $("#productId").val(undefined)
    $("#productName").val(undefined)
    $("#quantity").val(undefined)
    $('#stock').val(undefined)
    $('#subpier').val(undefined)
    $('#unit').val(undefined)
    $('#price').val(undefined)
    $('#description').val(undefined)
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
    $(".mark-form").find('.form-title').text("Thêm sản phẩm")
    $(".mark-form").css("display", "block");
  })



