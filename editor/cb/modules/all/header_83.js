function remove(el) {
    var element = el;
    element.closest('li').remove();
}
function cart_plus() {
    var header_83_quantity = parseInt(document.getElementById("header_83_quantity").value);
    document.getElementById("header_83_quantity").value = header_83_quantity + 1;

}
function cart_minus() {
    var header_83_quantity = parseInt(document.getElementById("header_83_quantity").value);
    if(header_83_quantity > 1)
    {
        document.getElementById("header_83_quantity").value = header_83_quantity - 1;
    }
}