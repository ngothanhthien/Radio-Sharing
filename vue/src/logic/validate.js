export const validateEmail=(email) =>
{
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
export const validateConfirmPassword=(password1,password2) =>{
    return password1.localeCompare(password2)==0
}