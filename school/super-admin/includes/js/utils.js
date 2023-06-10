var loadFile = function (event) {
    var image = document.getElementById('image');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.onload = function () {
        URL.revokeObjectURL(image.src)
    }
};

const getById = ele => {
    return document.getElementById(ele);
}
const getBySelector = ele => {
    return document.querySelector(ele);
}
const getBySelectAll = ele => {
    return document.querySelectorAll(ele);
}

const add_spinner = () => {
    let spinner_btn = getBySelector(".spinner_btn");
    let span = document.createElement("span");

    span.classList.add("spinner-border");
    span.classList.add("spinner-border-sm");
    span.setAttribute('role', 'status');
    span.setAttribute('aria-hidden', 'true');
    spinner_btn.innerHTML = " ";
    spinner_btn.appendChild(span);
    // spinner_btn.setAttribute('disabled', '');
}

