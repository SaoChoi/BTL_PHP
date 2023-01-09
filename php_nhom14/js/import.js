$('.icon-edit').click(e => {
    e.stopPropagation();
    const id = $('#receiptId').val();

    document.cookie = `receiptId=${id}`;

    $(e.target).find('.mark-form').css('display','flex')
})


$('.btn-close').click(e => {
    $(e.target).parent().parent().parent().parent().parent().css('display','none')
})

$('.icon-delete-product').click(e => {
    $(e.target).parent().parent().remove()
})

$('#product').val('')

$('#product').change(e => {
    $(e).data()
})

function deletProductItem(e) {
    debugger
}

$('.add-product').click(e => {
    $('.table-form').append(`<tr>
    <td class="col-stt-form"></td>
    <td class="col-product-name-form"></td>
    <td class=""></td>
    <td class="col-supplier-form"></td>
    <td class="col-quantity-form"></td>
    <td class="col-action-form">
        <i class="fa-solid fa-trash icon-delete-product" click="deletProductItem"></i>
    </td>
</tr>
    `)
})