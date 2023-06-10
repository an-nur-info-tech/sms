/*   CHECK PASSWORD STRENGTH ON PROFILE SETTINGS*/
const check_Password_stregth = () => {
    let password = getById("password").value;
    let confirm_password = getById("confirm_password").value;
    let passwordBtn = getById("passwordBtn");

    if ((password.length > 8) && (confirm_password.length > 8) && (password.match(/[a-zA-Z][0-9]/g))) // TODO RegEx
    {
        passwordBtn.removeAttribute('disabled');
    } else {
        passwordBtn.setAttribute('disabled', '');
    }

}
  /*-------x---- CHECK PASSWORD STRENGTH ON PROFILE SETTINGS -------x----*/

